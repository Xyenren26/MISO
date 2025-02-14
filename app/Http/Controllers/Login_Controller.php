<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

class Login_Controller extends Controller
{
    public function showLogin()
    {
        // Disable caching for the login page
        return response()->view('Login') // Refers to resources/views/login.blade.php
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function showSignup()
    {
        return view('Signup');
    }

    public function authenticate(Request $request)
    {
        // Validate the inputs for username and password
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Please enter your username.',
            'password.required' => 'Please enter your password.',
        ]);

        // Find user by username
        $user = User::where('username', $request->username)->first();

        if ($user) {
            // Check if the user account is inactive or disabled
            if ($user->status !== 'active') {
                return redirect()->back()->withErrors([
                    'login' => 'Your account is inactive or disabled. Please contact support.',
                ])->withInput();
            }

            // Check if the user is already logged in from another session
            if ($user->session_id) {
                return redirect()->back()->withErrors([
                    'login' => 'You are already logged in from another session. Please log out first.',
                ])->withInput();
            }

            $remember = $request->has('remember');

            // Set session lifetime based on "Remember Me"
            if ($remember) {
                Config::set('session.lifetime', env('REMEMBER_ME_LIFETIME', 43200));
                Config::set('session.expire_on_close', false); // Session persists even when the browser is closed
            } else {
                Config::set('session.lifetime', env('SESSION_LIFETIME', 5));
                Config::set('session.expire_on_close', true); // Session expires when the browser is closed
            }

            // Attempt to log the user in
            if (Auth::attempt(['username' => $request->username, 'password' => $request->password], $remember)) {
                // Check if this is the user's first login
                if ($user->is_first_login) {
                    return redirect()->route('profile.complete.form'); // Replace with actual route name for profile completion
                }

                // Save the user's session info
                session(['user_id' => $user->id]); // Save user ID in session, not employee_id
                session(['last_activity' => now()]); // Track last activity time

                // Update user session ID
                $user->last_activity = now();
                $user->session_id = session()->getId();
                $user->save();

                // Redirect based on account type
                if ($user->account_type === 'end_user') {
                    return redirect('/employee/home'); // Redirect to employee's home page
                } elseif (in_array($user->account_type, ['technical_support', 'administrator'])) {
                    return redirect()->route('home'); // Redirect to technical roles or admin home
                }
            }
        }

        // If login fails, redirect back with an error
        return redirect()->back()->withErrors([
            'login' => 'Invalid username or password.',
        ])->withInput();
    }

    public function logout()
    {
        $user = Auth::user();

        if ($user) {
            $user->session_id = null;
            $user->remember_token = null; // Clear the remember token
            $user->last_activity = null;
            $user->save();

            // Remove the session from the database based on user_id (EmployeeID)
            \DB::table('sessions')
                ->where('user_id', $user->employee_id) // Assuming user_id in the sessions table maps to EmployeeID
                ->delete();
        }

        // Log the user out
        Auth::logout();

        // Clear all session data
        session()->flush();

        // Optionally regenerate the session to avoid session fixation attacks
        session()->regenerate();

        // Remove the remember_me cookie
        return redirect()->route('login')->withCookie(\Cookie::forget('remember_web'));
    }

    
}
