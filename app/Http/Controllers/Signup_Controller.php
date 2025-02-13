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
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);
        

        // Generate a random 6-digit employee ID
        $employeeId = rand(100000, 999999);

        // Ensure the employee ID is unique
        while (User::where('employee_id', $employeeId)->exists()) {
            $employeeId = rand(100000, 999999);
        }

        // Create user in the database
        $user = User::create([
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'employee_id' => $employeeId,
        ]);

        // Fetch the user again from the database using their email
        $user = User::where('email', $request->input('email'))->first();

        // Send the welcome email
        Mail::to($user->email)->send(new WelcomeEmail($user));

        // Redirect to login with success message
        return redirect()->route('login')->with('success', 'Account created successfully. Please log in.');
    }
}
