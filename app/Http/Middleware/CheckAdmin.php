<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and is an admin
        if (Auth::check() && Auth::user()->account_type === 'administrator') {
            return $next($request); // Allow access
        }

        // Redirect back with an error message if not admin
        return redirect()->back()->with('error', 'Access denied. Admins only.');
    }
}
