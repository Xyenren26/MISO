<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Mail\VerificationMail;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password'); // Create this view later
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        if (!$user->email_verified_at) { 
            // Call your custom verification function
            $this->sendVerificationEmailManually($user);

            return back()->with('unverified', 'Your email is not verified. A new verification email has been sent.');
        }

        // Send password reset link
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'A password reset link has been sent to your email.')
            : back()->withErrors(['email' => 'Failed to send reset link. Please try again.']);
    }

    /**
     * Manually send a verification email
     */
    private function sendVerificationEmailManually($user)
    {
        \Mail::to($user->email)->send(new VerificationMail($user));
    }
    
}

