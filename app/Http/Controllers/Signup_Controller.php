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
            'employee-id' => 'required|string|max:255|unique:users,employee_id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Create user in the database
        $user = User::create([
            'employee_id' => $request->input('employee-id'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'status' => 'inactive', // Set status to inactive
        ]);

        // Fetch the user again from the database using their email
        $user = User::where('email', $request->input('email'))->first();

        // Send the welcome email
        Mail::to($user->email)->send(new WelcomeEmail($user));

        // Redirect to login with success message
        return redirect()->route('login')->with('success', 'Successful created, your account is temporary inactive please contact the administrator');
    }
}
