<?php

namespace App\Http\Controllers;

use App\Mail\SendCreatedAccountCredentials;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeAdmin();

        $users = User::query()
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'department', 'department_status']);

        $selectedUserId = $request->query('user_id');
        $selectedUser = null;

        if ($selectedUserId !== null && $selectedUserId !== '') {
            $selectedUser = $users->firstWhere('id', (int) $selectedUserId);
            $selectedUserId = $selectedUser?->id;
        }

        return view('admin.users.index', [
            'users' => $users,
            'selectedUser' => $selectedUser,
            'selectedUserId' => $selectedUserId,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'department_code' => ['required', 'string', 'max:30'],
            'department_status' => ['required', 'in:approved,pending'],
        ]);

        $departmentCode = strtoupper(trim($validated['department_code']));
        $departmentStatus = $validated['department_status'];

        if ($departmentCode === '') {
            return back()
                ->withErrors(['department_code' => 'Department code is required.'])
                ->withInput();
        }

        if ($departmentCode === 'ADMIN' && User::query()->whereRaw('UPPER(department) = ?', ['ADMIN'])->exists()) {
            return back()
                ->withErrors(['department_code' => 'Only one ADMIN account is allowed.'])
                ->withInput();
        }

        if ($departmentCode === 'ADMIN') {
            $departmentStatus = 'approved';
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
            'department' => $departmentCode,
            'department_status' => $departmentStatus,
            'email_verified_at' => now(),
            'password_changed_at' => null,
        ]);

        // Send the credentials via email
        Mail::to($user->email)->send(new SendCreatedAccountCredentials($user, $username, $generatedPassword));

        return redirect()->route('admin.users.index')->with('status', 'Account created successfully. Credentials have been sent to the email address.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'department_code' => ['required', 'string', 'max:30'],
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
}
