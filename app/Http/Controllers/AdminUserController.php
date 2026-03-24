<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            'password' => ['required', 'string', 'min:8'],
            'department' => ['required', 'string', 'max:30', 'in:ADMIN'],
            'department_status' => ['required', 'in:approved'],
        ]);

                $department = trim($validated['department']);
        $departmentStatus = $validated['department_status'];

        if ($department === 'ADMIN') {
            $departmentStatus = 'approved';
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'department' => $department,
            'department_status' => $departmentStatus,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')->with('status', 'Account created successfully.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'department' => ['required', 'string', 'max:30', 'in:ADMIN'],
            'department_status' => ['required', 'in:approved'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $department = trim($validated['department']);
        $departmentStatus = $validated['department_status'];

        if ($department === 'ADMIN') {
            $departmentStatus = 'approved';
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->department = $department;
        $user->department_status = $departmentStatus;

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('status', 'Account updated successfully.');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->department === 'ADMIN', 403);
    }
}
