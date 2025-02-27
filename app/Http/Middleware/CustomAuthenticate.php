<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user is logged in
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        if ($user->is_first_login === 1) {
            return redirect()->route('profile.complete.form')->with('error', 'You must complete your profile before proceeding.');
        }        

        // Check if the user is a technical_support or administrator
        if (!in_array($user->account_type, ['technical_support', 'administrator'])) {
            return redirect()->route('employee.home')->with('error', 'You do not have permission to access this page.');
        }

        // Allow access if the user is authorized and has completed the first login
        return $next($request);
    }
}