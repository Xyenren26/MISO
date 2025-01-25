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
        'username' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
    ]);

    // Generate a random 6-digit employee ID
    $employeeId = rand(100000, 999999);  // Example: Generates a number between 100000 and 999999

    // Check if the employee ID already exists and regenerate if necessary
    while (User::where('employee_id', $employeeId)->exists()) {
        $employeeId = rand(100000, 999999);  // Regenerate if the ID already exists
    }

    // Create a new user in the database
    $user = User::create([
        'username' => $request->input('username'),
        'email' => $request->input('email'),
        'password' => Hash::make($request->input('password')),
        'employee_id' => $employeeId,  // Assign the generated employee ID
    ]);

    // Send welcome email
    Mail::to($user->email)->send(new WelcomeEmail($user));

    // Redirect to the login page with a success message
    return redirect()->route('login')->with('success', 'Account created successfully. Please log in.');
}

}
