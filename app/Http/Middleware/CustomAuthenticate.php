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

        // Check if the user is not logged in
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        // Check if the user has completed their profile
        if ($user->is_first_login === 1) {
            return redirect()->route('profile.complete.form')->with('error', 'You must complete your profile before proceeding.');
        }

        // Restrict access for unauthorized users
        if (!in_array($user->account_type, ['technical_support', 'administrator', 'technical_support_head'])) {
            return redirect()->route('employee.home')->with('error', 'You do not have permission to access this page.');
        }

        // Redirect specific roles to their designated pages
        if ($user->account_type === 'technical_support_head') {
            return redirect()->route('ticket'); // Redirect technical_support_head to Ticket Management
        } elseif ($user->account_type === 'administrator') {
            return redirect()->route('report'); // Redirect administrator to Reports and Analytics
        } 

        // Allow access to authorized users
        return $next($request);
    }
}
