<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ], [
            'username.required' => 'Username wajib diisi',
            'username.string' => 'Username harus berupa teks',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        $remember = $request->boolean('remember');

        // Find user by username
        $user = User::whereRaw("lower(username) = lower(?)", [$credentials['username']])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user, $remember);
            $request->session()->regenerate();

            // Cek role dan redirect sesuai role
            if ($user->hasRole('Super Admin')) {
                return redirect()->route('super-admin.dashboard')->with('success', 'Login berhasil!');
            } elseif ($user->hasRole('Manager')) {
                return redirect()->route('manager.dashboard')->with('success', 'Login berhasil!');
            } else {
                Auth::logout();
                return redirect()->route('login')->withErrors(['username' => 'Akun anda tidak memiliki akses.']);
            }
        }

        throw ValidationException::withMessages([
            'username' => 'Username atau password tidak sesuai.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('Formlogin')->with('success', 'Logout berhasil!');
    }
}
