<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
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
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        $remember = $request->boolean('remember');

        $user = User::whereRaw("lower(username) = lower(?)", [$credentials['username']])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            $currentHost = $request->getHost();

            if ($user->perusahaan && !empty($user->perusahaan->domain)) {
                $userDomain = $user->perusahaan->domain;

                if ($currentHost !== $userDomain) {
                    // 1. Buat Signed URL sementara (berlaku 1 menit)
                    $url = URL::temporarySignedRoute(
                        'login.auto',
                        now()->addMinutes(1),
                        ['user' => $user->id]
                    );

                    // 2. Ganti domain asal dengan domain perusahaan user
                    $finalUrl = str_replace($currentHost, $userDomain, $url);

                    // 3. Redirect ke domain baru
                    return redirect()->away($finalUrl);
                }
            }

            // Login normal jika domain sudah sesuai
            Auth::login($user, $remember);
            $request->session()->regenerate();

            return $this->redirectByRole($user);
        }

        throw ValidationException::withMessages([
            'username' => 'Username atau password tidak sesuai.',
        ]);
    }

    public function autoLogin(Request $request, User $user)
    {
        Auth::login($user);
        $request->session()->regenerate();

        return $this->redirectByRole($user)->with('success', 'Login berhasil melalui pengalihan domain!');
    }

    private function redirectByRole($user)
    {
        if ($user->hasRole('Super Admin')) {
            return redirect()->route('super-admin.dashboard');
        } elseif ($user->hasRole('Manager')) {
            return redirect()->route('manager.dashboard');
        } elseif ($user->hasRole('Admin Gudang')) {
            return redirect()->route('admin-gudang.dashboard');
        } elseif ($user->hasRole('Guest')) {
            return redirect()->route('monitoring');
        }else{
            return redirect()->route('admin-gudang.dashboard');
        }

        Auth::logout();
        return redirect()->route('login')->withErrors(['username' => 'Akun anda tidak memiliki akses.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout berhasil!');
    }
}
