<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('web')->check() || Auth::guard('karyawan')->check()) {
            if (Auth::guard('web')->check() && Auth::guard('web')->user()->hasRole('administrator')) {
                return redirect('/panel/dashboardadmin');
            }
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Coba login sebagai admin
        if(Auth::guard('web')->attempt(['email' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            if(Auth::guard('web')->user()->hasRole('administrator')) {
                return redirect('/panel/dashboardadmin');
            }
            return redirect('/dashboard');
        }

        // Coba login sebagai karyawan
        if(Auth::guard('karyawan')->attempt(['nik' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }

        return redirect('/')->with(['warning' => 'Username atau Password Salah']);
    }

    public function logout(Request $request)
    {
        if(Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }
        
        if(Auth::guard('karyawan')->check()) {
            Auth::guard('karyawan')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}