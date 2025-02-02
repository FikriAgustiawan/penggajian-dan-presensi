<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // Cek apakah user sudah login
        if (Auth::guard('web')->check() || Auth::guard('karyawan')->check()) {
            // Jika admin, arahkan ke dashboard admin
            if (Auth::guard('web')->check()) {
                $user = Auth::guard('web')->user();
                if($user && $user->hasRole('administrator')) {
                    return redirect('/panel/dashboardadmin');
                }
            }
            // Jika karyawan atau user biasa, arahkan ke dashboard
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required',
                'password' => 'required'
            ]);

            // Coba login sebagai admin/user
            if(Auth::guard('web')->attempt(['email' => $request->username, 'password' => $request->password])) {
                $request->session()->regenerate();
                $user = Auth::guard('web')->user();
                
                // Cek role administrator
                if($user && $user->hasRole('administrator')) {
                    return redirect('/panel/dashboardadmin');
                }
                return redirect('/dashboard');
            }

            // Coba login sebagai karyawan
            if(Auth::guard('karyawan')->attempt(['nik' => $request->username, 'password' => $request->password])) {
                $request->session()->regenerate();
                $karyawan = Auth::guard('karyawan')->user();
                
                // Assign role karyawan jika belum ada
                if(!$karyawan->hasAnyRole()) {
                    $karyawan->assignRole('karyawan');
                }
                
                return redirect('/dashboard');
            }

            return redirect('/')->with(['warning' => 'Username atau Password Salah']);
        } catch (\Exception $e) {
            return redirect('/')->with(['warning' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Logout dari semua guard
            if(Auth::guard('web')->check()) {
                Auth::guard('web')->logout();
            }
            
            if(Auth::guard('karyawan')->check()) {
                Auth::guard('karyawan')->logout();
            }

            // Invalidate session dan regenerate token
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/');
        } catch (\Exception $e) {
            return redirect('/')->with(['warning' => 'Terjadi kesalahan saat logout: ' . $e->getMessage()]);
        }
    }
}