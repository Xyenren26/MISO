<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationMail;
use App\Models\User;
use Illuminate\Http\Request;


class VerificationController extends Controller
{
    public function sendVerificationEmail(Request $request)
    {
        $user = auth()->user();

        if ($user->email_verified_at) {
            return back()->with('message', 'Your email is already verified.');
        }

        Mail::to($user->email)->send(new VerificationMail($user));

        return back()->with('message', 'Verification email sent!');
    }

    public function verifyEmail($id, $hash)
    {
        // Find the user using employee_id instead of the default ID
        $user = User::where('employee_id', $id)->firstOrFail();

        // Check if the hash matches the user's email
        if (sha1($user->email) !== $hash) {
            return redirect('/login')->with('error', 'Invalid verification link!');
        }

        // Proceed with the email verification process
        $user->email_verified_at = now();
        $user->save();

        return redirect('/login')->with('message', 'Your email has been successfully verified!');
    }

    public function RegistrationEmailValidate($id, $hash)
    {
        // Find the user using employee_id instead of the default ID
        $user = User::where('employee_id', $id)->firstOrFail();

        // Check if the hash matches the user's email
        if (sha1($user->email) !== $hash) {
            return redirect('/login')->with('error', 'Invalid verification link!');
        }

        // Proceed with the email verification process
        $user->email_verified_at = now();
        $user->save();

        // After successful verification, redirect to login page or home
        return redirect('/login')->with('message', 'Your email has been successfully verified! Please log in.');
    }

}
