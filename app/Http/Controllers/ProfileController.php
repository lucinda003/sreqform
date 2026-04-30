<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Support\EncryptedSignature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function editAjax(Request $request): JsonResponse
    {
        $html = view('profile.edit-content', [
            'user' => $request->user(),
        ])->render();

        return response()->json(['html' => $html]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->safe()->only(['name', 'email']));

        if ($this->hasProfileSignatureAccess($request)) {
            $request->user()->profile_signature = $this->profileSignatureValue(
                (string) $request->input('profile_signature_drawn', ''),
                (string) ($request->user()->profile_signature ?? ''),
                (bool) $request->boolean('profile_signature_clear')
            );
        }

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function sendSignatureCode(Request $request): RedirectResponse
    {
        $user = $request->user();
        $recipientEmail = trim((string) ($user?->email ?? ''));

        if ($recipientEmail === '' || filter_var($recipientEmail, FILTER_VALIDATE_EMAIL) === false) {
            return Redirect::route('profile.edit')->withErrors([
                'profile_signature_code' => 'Please save a valid email address before requesting an unlock code.',
            ]);
        }

        $cooldownKey = $this->profileSignatureCooldownCacheKey((int) $user->id);
        if (Cache::has($cooldownKey)) {
            return Redirect::route('profile.edit')
                ->with('status', 'Signature unlock code already sent. Please wait before requesting another one.');
        }

        $verificationCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(8);

        try {
            Mail::raw(
                "Your DOH profile signature unlock code is {$verificationCode}.\n\nThis code expires in 8 minutes.",
                function ($message) use ($recipientEmail): void {
                    $message
                        ->to($recipientEmail)
                        ->subject('DOH Profile Signature Unlock Code');
                }
            );
        } catch (\Throwable $exception) {
            report($exception);

            return Redirect::route('profile.edit')->withErrors([
                'profile_signature_code' => 'Unable to send unlock code right now. Please try again later.',
            ]);
        }

        Cache::put($this->profileSignatureCodeCacheKey((int) $user->id), [
            'code_hash' => $this->hashProfileSignatureCode($verificationCode),
            'attempts' => 0,
            'expires_at' => $expiresAt->timestamp,
        ], $expiresAt);
        Cache::put($cooldownKey, true, now()->addSeconds(60));

        return Redirect::route('profile.edit')->with('status', 'Signature unlock code sent to your email.');
    }

    public function verifySignatureCode(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'profile_signature_code' => ['required', 'digits:6'],
        ]);

        $userId = (int) $request->user()->id;
        $codeKey = $this->profileSignatureCodeCacheKey($userId);
        $lockKey = $this->profileSignatureLockCacheKey($userId);

        if (Cache::has($lockKey)) {
            return Redirect::route('profile.edit')->withErrors([
                'profile_signature_code' => 'Too many invalid attempts. Please request a new code after 15 minutes.',
            ]);
        }

        $payload = Cache::get($codeKey);
        if (! is_array($payload)) {
            return Redirect::route('profile.edit')->withErrors([
                'profile_signature_code' => 'Unlock code is invalid or expired. Please request a new code.',
            ]);
        }

        $expiresAt = (int) ($payload['expires_at'] ?? 0);
        if ($expiresAt <= now()->timestamp) {
            Cache::forget($codeKey);

            return Redirect::route('profile.edit')->withErrors([
                'profile_signature_code' => 'Unlock code is invalid or expired. Please request a new code.',
            ]);
        }

        $codeMatches = hash_equals(
            (string) ($payload['code_hash'] ?? ''),
            $this->hashProfileSignatureCode((string) $validated['profile_signature_code'])
        );

        if (! $codeMatches) {
            $attempts = ((int) ($payload['attempts'] ?? 0)) + 1;

            if ($attempts >= 5) {
                Cache::forget($codeKey);
                Cache::put($lockKey, true, now()->addMinutes(15));

                return Redirect::route('profile.edit')->withErrors([
                    'profile_signature_code' => 'Too many invalid attempts. Please request a new code after 15 minutes.',
                ]);
            }

            $payload['attempts'] = $attempts;
            Cache::put($codeKey, $payload, now()->addSeconds(max(1, $expiresAt - now()->timestamp)));

            return Redirect::route('profile.edit')->withErrors([
                'profile_signature_code' => 'Unlock code is invalid or expired. Please try again.',
            ]);
        }

        Cache::forget($codeKey);
        Cache::forget($lockKey);
        $request->session()->put($this->profileSignatureSessionKey($userId), now()->addMinutes(15)->timestamp);

        return Redirect::route('profile.edit')->with('status', 'Signature unlocked for 15 minutes.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    private function profileSignatureValue(string $providedSignature, string $existingSignature, bool $clearRequested): ?string
    {
        if ($clearRequested) {
            $this->deleteProfileSignatureFile($existingSignature);
            return null;
        }

        $signatureValue = trim($providedSignature);
        if ($signatureValue === '') {
            return trim($existingSignature) !== '' ? $existingSignature : null;
        }

        $decoded = $this->decodeImageDataUri($signatureValue);
        if (! is_array($decoded)) {
            return trim($existingSignature) !== '' ? $existingSignature : null;
        }

        $binary = (string) ($decoded['binary'] ?? '');
        if ($binary === '') {
            return trim($existingSignature) !== '' ? $existingSignature : null;
        }

        $newPath = EncryptedSignature::storeBinary(
            $binary,
            (string) ($decoded['mime'] ?? 'image/png')
        );

        if ($newPath !== '') {
            $this->deleteProfileSignatureFile($existingSignature);

            return $newPath;
        }

        return trim($existingSignature) !== '' ? $existingSignature : null;
    }

    private function hasProfileSignatureAccess(Request $request): bool
    {
        $expiresAt = (int) $request->session()->get($this->profileSignatureSessionKey((int) $request->user()->id), 0);

        if ($expiresAt <= now()->timestamp) {
            $request->session()->forget($this->profileSignatureSessionKey((int) $request->user()->id));

            return false;
        }

        return true;
    }

    private function profileSignatureSessionKey(int $userId): string
    {
        return 'profile_signature_unlocked_until:' . $userId;
    }

    private function profileSignatureCodeCacheKey(int $userId): string
    {
        return 'profile-signature-code:' . $userId;
    }

    private function profileSignatureCooldownCacheKey(int $userId): string
    {
        return 'profile-signature-code-cooldown:' . $userId;
    }

    private function profileSignatureLockCacheKey(int $userId): string
    {
        return 'profile-signature-code-lock:' . $userId;
    }

    private function hashProfileSignatureCode(string $code): string
    {
        return hash_hmac('sha256', $code, (string) config('app.key'));
    }

    private function decodeImageDataUri(?string $value): ?array
    {
        $raw = trim((string) $value);
        if ($raw === '') {
            return null;
        }

        if (preg_match('/^data:(image\/[a-zA-Z0-9.+-]+);base64,(.+)$/s', $raw, $matches) !== 1) {
            return null;
        }

        $binary = base64_decode((string) $matches[2], true);
        if ($binary === false || $binary === '') {
            return null;
        }

        return [
            'mime' => strtolower(trim((string) $matches[1])) ?: 'image/png',
            'binary' => $binary,
        ];
    }

    private function deleteProfileSignatureFile(?string $signaturePath): void
    {
        $path = trim((string) $signaturePath);

        if ($path === '' || ! str_starts_with($path, 'service-request-signatures/')) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
