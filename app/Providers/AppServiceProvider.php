<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $normalizeReferenceCode = static function (Request $request): string {
            $rawReferenceCode = (string) ($request->route('referenceCode') ?? $request->query('reference_code', ''));
            $normalizedReferenceCode = strtoupper((string) preg_replace('/[^A-Za-z0-9]/', '', $rawReferenceCode));

            return $normalizedReferenceCode !== '' ? $normalizedReferenceCode : 'UNKNOWN';
        };

        RateLimiter::for('track-send-code', function (Request $request) use ($normalizeReferenceCode): array {
            $referenceCode = $normalizeReferenceCode($request);

            return [
                Limit::perMinute(12)->by('track-send-minute:' . $referenceCode . '|' . $request->ip()),
                Limit::perMinutes(15, 60)->by('track-send-window:' . $referenceCode . '|' . $request->ip()),
            ];
        });

        RateLimiter::for('track-verify-code', function (Request $request) use ($normalizeReferenceCode): array {
            $referenceCode = $normalizeReferenceCode($request);

            return [
                Limit::perMinute(30)->by('track-verify-minute:' . $referenceCode . '|' . $request->ip()),
                Limit::perMinutes(15, 120)->by('track-verify-window:' . $referenceCode . '|' . $request->ip()),
            ];
        });

        RateLimiter::for('track-public', function (Request $request) use ($normalizeReferenceCode): array {
            $referenceCode = $normalizeReferenceCode($request);

            return [
                Limit::perMinute(60)->by('track-public-minute:' . $referenceCode . '|' . $request->ip()),
                Limit::perMinutes(15, 450)->by('track-public-window:' . $referenceCode . '|' . $request->ip()),
            ];
        });
    }
}
