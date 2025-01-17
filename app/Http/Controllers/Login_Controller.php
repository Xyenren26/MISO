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
        // Check if the user is already authenticated via custom authentication
        if (session()->has('user_id')) {
            $user = User::find(session('user_id'));
            if ($user) {
                // Redirect based on account type
                $accountType = $user->account_type;
                if ($accountType === 'end_user') {
                    return redirect('/employee/home'); // Redirect employee to the client folder
                } elseif (in_array($accountType, ['technical_support', 'administrator'])) {
                    return redirect()->route('home'); // Redirect technical roles to main home
                }
            }
        }

        return view('Login'); // Refers to resources/views/login.blade.php
    }

    public function showSignup()
    {
        return view('Signup');
    }

    public function authenticate(Request $request)
{
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ], [
        'username.required' => 'Please enter your username.',
        'password.required' => 'Please enter your password.',
    ]);

    $user = User::where('username', $request->username)->first();

    if ($user) {
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

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password], $remember)) {
            session(['user_id' => $user->employee_id]);
            session(['last_activity' => now()]);


            $user->last_activity = now();
            $user->session_id = session()->getId();
            $user->save();

            // Redirect based on account type
            if ($user->account_type === 'end_user') {
                return redirect('/employee/home'); // Redirect employee to the client folder
            } elseif (in_array($user->account_type, ['technical_support', 'administrator'])) {
                return redirect()->route('home'); // Redirect technical roles to main home
            }
        }
    }

    return redirect()->back()->withErrors([
        'login' => 'Invalid username or password.',
    ])->withInput();
}

}
