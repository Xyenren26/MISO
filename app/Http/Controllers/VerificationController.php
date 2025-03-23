<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use App\Mail\VerificationMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;


class VerificationController extends Controller
{
    public function sendVerificationEmail(Request $request)
    {
        $user = auth()->user();
    
        // Check if the user's email is already verified
        if ($user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Your email is already verified.'
            ], 400); // 400 Bad Request status code
        }
    
        try {
            // Send the verification email
            Mail::to($user->email)->send(new VerificationMail($user));
    
            return response()->json([
                'success' => true,
                'message' => 'Verification email sent! Check your inbox.'
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Failed to send verification email: ' . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification email. Please try again later.'
            ], 500); // 500 Internal Server Error status code
        }
    }
    
    public function verifyEmail(Request $request, $id, $hash)
    {
        $expires = $request->input('expires');

        if (!$expires || Carbon::now()->timestamp > $expires) {
            return redirect()->route('login')->with('error', 'Verification link has expired.');
        }

        // Find user by employee_id
        $user = User::where('employee_id', $id)->firstOrFail();

        // Validate hash
        if (sha1($user->email) !== $hash) {
            return redirect('/login')->with('error', 'Invalid verification link!');
        }

        // Mark email as verified
        $user->email_verified_at = now();
        $user->save();

        return redirect('/login')->with('message', 'Your email has been successfully verified!');
    }

    public function RegistrationEmailValidate(Request $request, $id)
    {
        // Ensure the URL signature is valid first (prevents tampering)
        if (!$request->hasValidSignature()) {
            return Redirect::to('/login')->with('error', 'Invalid or expired verification link!');
        }
    
        // Find the user using employee_id
        $user = User::where('employee_id', $id)->firstOrFail();
    
        // Check if the email is already verified
        if ($user->email_verified_at) {
            return Redirect::to('/login')->with('message', 'Your email is already verified. Please log in.');
        }
    
        // Verify the email
        $user->email_verified_at = now();
        $user->save();
    
        return Redirect::to('/login')->with('message', 'Your email has been successfully verified! Please log in.');
    }

}
