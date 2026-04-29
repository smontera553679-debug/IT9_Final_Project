<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    // Show the form (the HTML I gave you earlier)
    public function showResetForm()
    {
        return view('auth.reset-password');
    }

    // Handle the update logic
    public function updatePassword(Request $request)
    {
        // 1. Validate the input
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed', 
        ]);

        // 2. Find the user by email
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // 3. Update and Hash the new password
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return redirect()->route('login')->with('status', 'Password updated successfully!');
        }

        return back()->withErrors(['email' => 'User not found.']);
    }
}