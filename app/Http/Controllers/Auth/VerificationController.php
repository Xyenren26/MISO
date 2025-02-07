<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use App\Models\User; 

class VerificationController extends Controller
{
    // This method will handle the email verification process
    public function verify(Request $request, $id, $hash)
    {
        // Find the user by the given ID
        $user = User::findOrFail($id); // This will return a user by employee_id

        // Debug: Check if the user is found
       // Log the incoming request to see if it's being triggered
        \Log::info("Verification Controller reached with ID: $id and Hash: $hash");
        if (!$user->hasVerifiedEmail()) {
            \Log::info('Marking email as verified for user', ['user_id' => $user->id]);
            $user->markEmailAsVerified();
            event(new Verified($user));
        } else {
            \Log::info('Email already verified for user', ['user_id' => $user->id]);
        }


        // Check if the hash matches the user's email
        if (sha1($user->getEmailForVerification()) === $hash) {
            // If the email is not verified yet, mark it as verified
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
                event(new Verified($user)); // Fire the Verified event
            }
        }

        // Redirect the user to the home page
        return redirect('/home');
    }
}
