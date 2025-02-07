<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class CheckRememberToken
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            return $next($request); // If already authenticated, continue
        }

        // Check if remember_token exists in session
        $rememberToken = session('remember_token');

        if ($rememberToken) {
            // Find user with matching remember token
            $user = User::where('remember_token', $rememberToken)->first();

            if ($user && session()->getId() === $user->session_id) {
                // If token is valid and session ID matches, log in the user
                Auth::login($user);
                return $next($request);
            }
        }

        return $next($request);
    }
}
