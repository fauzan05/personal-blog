<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
       if(!Cookie::get('admin')) {
            if(Auth::check()) {
                Auth::logout();
            }
            return route('login');
            // return redirect('/login-admin')->with('status', 'Sesi anda telah habis! Silahkan login kembali');
        }
        // return $request->expectsJson() ? null : route('login');
    }
}
