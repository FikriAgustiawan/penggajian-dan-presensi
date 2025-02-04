<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // public function showLoginForm()
    // {
    //     if (Auth::guard('web')->check()) {
    //         $isAdmin = DB::table('model_has_roles')
    //             ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
    //             ->where('model_has_roles.model_id', Auth::guard('web')->id())
    //             ->where('roles.name', 'administrator')
    //             ->exists();
                
    //         if ($isAdmin) {
    //             return redirect('/panel/dashboardadmin');
    //         }
    //         return redirect('/dashboard');
    //     }
        
    //     if (Auth::guard('karyawan')->check()) {
    //         return redirect('/dashboard');
    //     }

    //     return view('auth.login');
    // }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required',
                'password' => 'required'
            ]);

            // Coba login sebagai admin
            if(Auth::guard('web')->attempt(['email' => $request->username, 'password' => $request->password])) {
                $request->session()->regenerate();
                
                $isAdmin = DB::table('model_has_roles')
                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->where('model_has_roles.model_id', Auth::guard('web')->id())
                    ->where('roles.name', 'administrator')
                    ->exists();
                    
                if ($isAdmin) {
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
        } catch (\Exception $e) {
            return redirect('/')->with(['warning' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        try {
            if(Auth::guard('web')->check()) {
                Auth::guard('web')->logout();
            }
            
            if(Auth::guard('karyawan')->check()) {
                Auth::guard('karyawan')->logout();
            }

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/');
        } catch (\Exception $e) {
            return redirect('/')->with(['warning' => 'Terjadi kesalahan saat logout: ' . $e->getMessage()]);
        }
    }
}