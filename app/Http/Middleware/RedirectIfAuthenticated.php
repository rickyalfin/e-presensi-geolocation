<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard('karyawan')->check()) {
                return redirect(RouteServiceProvider::HOME); // kalo kita sudah login tapi belum logout, yang diakses guard karyawan maka akan redirect ke halaman dashboard karyawan
            }
            if (Auth::guard('user')->check()) {
                return redirect(RouteServiceProvider::HOMEADMIN); // kalo kita sudah login tapi belum logout, yang diakses guard user / admin maka akan ke redirect ke home admin / halaman dashboard admin
            }
        }

        return $next($request);
    }
}
