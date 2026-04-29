<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function showSettings()
    {
        $user = Auth::user();
        return view('auth.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'fullname'        => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email,' . $user->id,
            'password'        => 'nullable|min:8',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->fullname = $request->fullname;
        $user->email    = $request->email;

        if ($user->role !== 'admin') {
            $user->phone_number = $request->phone_number;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $user->profile_picture = $path;
        }

        $user->save();

        return redirect()->route($user->role === 'admin' ? 'admin.dashboard' : 'customer.landing')
            ->with('success', 'Account settings updated successfully!');
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return $this->redirectUser(Auth::user());
        }

        return back()
            ->withErrors(['username' => 'Invalid credentials.'])
            ->onlyInput('username');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'fullname' => $request->fullname,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->username === 'admin' ? 'admin' : 'customer', // auto-assign role
        ]);

        Auth::login($user);
        return $this->redirectUser($user);
    }

    protected function redirectUser($user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('customer.landing');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}