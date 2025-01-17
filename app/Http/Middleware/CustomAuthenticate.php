<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Custom authentication logic (example)
        if (!$request->session()->has('user_id')) {
            return redirect('/login'); // Redirect if not authenticated
        }

        return $next($request); // Continue if authenticated
    }
}
