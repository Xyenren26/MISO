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

        // Check if the user is NOT a technical support or administrator
        if (!in_array($user->account_type, ['technical_support', 'administrator'])) {
            return redirect()->route('employee.home')->with('error', 'The website you were trying to access is invalid.');
        }

        // Allow access if the user is authorized
        return $next($request);
    }
}
