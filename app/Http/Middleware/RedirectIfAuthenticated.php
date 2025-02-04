<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard('web')->check()) {
                $user = Auth::guard('web')->user();
                if ($user->hasRole('administrator')) {
                    return redirect('/panel/dashboardadmin');
                }
                return redirect('/dashboard');
            }
            
            if (Auth::guard('karyawan')->check()) {
                return redirect('/dashboard');
            }
        }

        return $next($request);
    }
}