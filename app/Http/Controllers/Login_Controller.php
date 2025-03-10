<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

use Illuminate\Support\Str;

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
            'employee_id' => 'required|string',
            'password' => 'required|string',
        ], [
            'employee_id.required' => 'Please enter your username.',
            'password.required' => 'Please enter your password.',
        ]);

        // Find user by username
        $user = User::where('employee_id', $request->employee_id)->first();

        if ($user) {
            $currentSessionId = session()->getId();

            // Check if the user account is inactive or disabled
            if ($user->status !== 'active') {
                return redirect()->back()->withErrors([
                    'login' => 'Your account is inactive or disabled. Please contact support.',
                ])->withInput();
            }

            // 🔹 Check if user is already logged in from another browser
            if ($user->session_id && $user->session_id !== $currentSessionId) {
                return redirect()->back()->withErrors([
                    'login' => 'You are already logged in from another session. Please log out first.',
                ])->withInput();
            }

            // 🔹 If user has a valid remember_token & session matches, log in automatically
            if ($user->remember_token && $user->session_id === $currentSessionId) {
                Auth::login($user);
                session(['user_id' => $user->id, 'last_activity' => now()]);
                return $this->redirectUser($user);
            }

            // 🔹 Proceed with normal authentication
            if (Auth::attempt(['employee_id' => $request->employee_id, 'password' => $request->password])) {
                // 🔹 Check if this is the user's first login
                if ($user->is_first_login) {
                    return redirect()->route('profile.complete.form'); 
                }
                // Generate & save new session_id and remember_token
                $user->remember_token = Str::random(60);
                $user->session_id = $currentSessionId; // Store the new session ID
                $user->last_activity = now();
                $user->active_status =true;
                $user->save();

                session(['user_id' => $user->id, 'last_activity' => now()]);

                return $this->redirectUser($user);
            }
        }

        // If authentication fails
        return redirect()->back()->withErrors(['login' => 'Invalid ID or password.'])->withInput();
    }

    // 🔹 Helper function to redirect users based on account type
    private function redirectUser($user)
    {
        if ($user->account_type === 'end_user') {
            return redirect('/employee/home'); // Redirect to employee's home page
        } elseif ($user->account_type === 'technical_support_head') {
            return redirect()->route('ticket'); // Redirect technical_support_head to Ticket Management
        } elseif ($user->account_type === 'administrator') {
            return redirect()->route('report'); // Redirect administrator to Reports and Analytics
        } elseif ($user->account_type === 'technical_support') {
            return redirect()->route('home'); // Redirect technical support to home
        }
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
