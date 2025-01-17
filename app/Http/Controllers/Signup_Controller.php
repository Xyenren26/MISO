<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;  // Make sure to import this

class Signup_Controller extends Controller
{
    public function storeSignup(Request $request)
    {
        // Validate the input data
        $request->validate([
            'first-name' => 'required|string|max:255',
            'last-name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'department' => 'required|string|max:255',
            'employee-id' => 'required|unique:users,employee_id|digits:7', // Validate employee ID as 7 digits
        ]);

        // Set account type based on department
        $accountType = $request->department === 'Management Information Systems Office (MISO)' 
            ? 'technical_support' 
            : 'end_user'; // Default account type is 'end_user'

        // Create a new user in the database
        $user = User::create([
            'first_name' => $request->input('first-name'),
            'last_name' => $request->input('last-name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'department' => $request->input('department'),
            'employee_id' => $request->input('employee-id'),
            'account_type' => $accountType,  // Store the account type
        ]);

        // Send welcome email
        Mail::to($user->email)->send(new WelcomeEmail($user));

        // Redirect to the login page with a success message
        return redirect()->route('login')->with('success', 'Account created successfully. Please log in.');
    }
}
