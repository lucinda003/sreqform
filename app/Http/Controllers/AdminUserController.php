<?php

namespace App\Http\Controllers;

use App\Mail\SendCreatedAccountCredentials;
use App\Models\DepartmentCode;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeAdmin();

        $departmentCodes = $this->availableDepartmentCodes();

        $users = User::query()
            ->where(function ($query): void {
                $query
                    ->whereNull('department')
                    ->orWhereRaw('UPPER(department) <> ?', ['ADMIN']);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'username', 'email', 'department', 'department_status']);

        $selectedUserId = $request->query('user_id');
        $selectedUser = null;

        if ($selectedUserId !== null && $selectedUserId !== '') {
            $selectedUser = $users->firstWhere('id', (int) $selectedUserId);
            $selectedUserId = $selectedUser?->id;
        }

        return view('admin.users.index', [
            'users' => $users,
            'departmentCodes' => $departmentCodes,
            'selectedUser' => $selectedUser,
            'selectedUserId' => $selectedUserId,
        ]);
    }

    public function indexAjax(Request $request): JsonResponse
    {
        $this->authorizeAdmin();

        $departmentCodes = $this->availableDepartmentCodes();

        $users = User::query()
            ->where(function ($query): void {
                $query
                    ->whereNull('department')
                    ->orWhereRaw('UPPER(department) <> ?', ['ADMIN']);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'username', 'email', 'department', 'department_status']);

        $selectedUserId = $request->query('user_id');
        $selectedUser = null;

        if ($selectedUserId !== null && $selectedUserId !== '') {
            $selectedUser = $users->firstWhere('id', (int) $selectedUserId);
            $selectedUserId = $selectedUser?->id;
        }

        $html = view('admin.users.index-content', [
            'users' => $users,
            'departmentCodes' => $departmentCodes,
            'selectedUser' => $selectedUser,
            'selectedUserId' => $selectedUserId,
        ])->render();

        return response()->json(['html' => $html]);
    }

    public function storeDepartmentCode(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:30'],
        ]);

        $departmentCode = strtoupper(trim($validated['code']));

        if ($departmentCode === 'ADMIN') {
            return back()
                ->withErrors(['code' => 'ADMIN is reserved for super admin and cannot be added.'])
                ->withInput();
        }

        if ($departmentCode === '') {
            return back()
                ->withErrors(['code' => 'Department code is required.'])
                ->withInput();
        }

        if (DepartmentCode::query()->whereRaw('UPPER(code) = ?', [$departmentCode])->exists()) {
            return back()
                ->withErrors(['code' => 'Department code already exists.'])
                ->withInput();
        }

        DepartmentCode::create([
            'code' => $departmentCode,
        ]);

        return redirect()->route('admin.users.index')->with('status', 'Department code added successfully.');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:super admin,admin,supervisor,technical support'],
            'department_code' => ['required', 'string', 'max:30', Rule::in($this->availableDepartmentCodes())],
        ]);

        $departmentCode = strtoupper(trim($validated['department_code']));
        $departmentStatus = 'pending';

        if ($departmentCode === '') {
            return back()
                ->withErrors(['department_code' => 'Department code is required.'])
                ->withInput();
        }

        // Generate username from email (part before @)
        $emailPrefix = explode('@', $validated['email'])[0];
        $username = $emailPrefix;
        $counter = 1;
        
        // Ensure username uniqueness
        while (User::where('username', $username)->exists()) {
            $username = $emailPrefix . $counter;
            $counter++;
        }

        // Generate a random password
        $generatedPassword = Str::random(12);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $username,
            'email' => $validated['email'],
            'password' => Hash::make($generatedPassword),
            'role' => $validated['role'],
            'department' => $departmentCode,
            'department_status' => $departmentStatus,
            'email_verified_at' => now(),
            'password_changed_at' => null,
        ]);

        // Send the credentials via email
        Mail::to($user->email)->send(new SendCreatedAccountCredentials($user, $username, $generatedPassword));

        return redirect()->route('admin.users.index')->with('status', 'Account created successfully. Credentials sent by email. Status is pending until first password setup is completed.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['nullable', 'in:super admin,admin,supervisor,technical support'],
            'department_code' => ['required', 'string', 'max:30', Rule::in($this->availableDepartmentCodes(strtoupper((string) $user->department) === 'ADMIN'))],
            'department_status' => ['required', 'in:approved,pending'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $departmentCode = strtoupper(trim($validated['department_code']));
        $departmentStatus = $validated['department_status'];

        if ($departmentCode === '') {
            return back()
                ->withErrors(['department_code' => 'Department code is required.'])
                ->withInput();
        }

        if (
            $departmentCode === 'ADMIN'
            && User::query()
                ->whereRaw('UPPER(department) = ?', ['ADMIN'])
                ->whereKeyNot($user->id)
                ->exists()
        ) {
            return back()
                ->withErrors(['department_code' => 'Only one ADMIN account is allowed.'])
                ->withInput();
        }

        if ($departmentCode === 'ADMIN') {
            $departmentStatus = 'approved';
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->department = $departmentCode;
        $user->department_status = $departmentStatus;

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('status', 'Account updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorizeAdmin();

        if (strtoupper((string) $user->department) === 'ADMIN') {
            return back()->withErrors(['delete' => 'Cannot delete an ADMIN account.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'Account deleted successfully.');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(strtoupper((string) auth()->user()?->department) === 'ADMIN', 403);
    }

    /**
     * @return array<int, string>
     */
    private function availableDepartmentCodes(bool $includeAdmin = false): array
    {
        $codes = DepartmentCode::query()
            ->pluck('code')
            ->map(fn (string $code): string => strtoupper(trim($code)))
            ->filter(fn (string $code): bool => $code !== '')
            ->unique()
            ->values()
            ->all();

        if (! $includeAdmin) {
            $codes = array_values(array_filter(
                $codes,
                fn (string $code): bool => $code !== 'ADMIN'
            ));
        } elseif (! in_array('ADMIN', $codes, true)) {
            $codes[] = 'ADMIN';
        }

        sort($codes);

        return $codes;
    }
}
