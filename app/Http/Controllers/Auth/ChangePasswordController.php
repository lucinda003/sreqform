<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ChangePasswordController extends Controller
{
    /**
     * Show the form to change password on first login
     */
    public function show(): View
    {
        return view('auth.change-password');
    }

    /**
     * Handle the password change request
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
            'password_changed_at' => now(),
        ]);

        $targetRoute = strtoupper((string) auth()->user()?->department) === 'ADMIN'
            ? 'admin.dashboard'
            : 'dashboard';

        return redirect()->route($targetRoute)->with('status', 'Password changed successfully.');
    }
}
