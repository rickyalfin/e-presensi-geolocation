<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            if (request()->is('panel/*')) { // kalo misalnya yang diakses halaman administrator dimana halaman administrator ada /panel dan * mengartikan setelah panel itu halaman bebas apa saja yang penting depan ada /panel jiak belum login maka akan diarahkan ke halaman login admin
                return route('loginadmin');
            } else {
                return route('login'); // selain itu jika tidak ada /panel dan belum login maka akan diarahkan ke login karyawan
            }
        }
    }
}
