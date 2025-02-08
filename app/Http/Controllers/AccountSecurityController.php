<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountSecurityController extends Controller
{
    /**
     * Show Account Security Page.
     */
    public function index()
    {
        return view('profile.accountsecurity');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // Verify the current password
        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return response()->json(['success' => false, 'message' => 'Current password is incorrect.'], 422);
        }

        // If current password is correct, update it
        auth()->user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json(['success' => true, 'message' => 'Password updated successfully!']);
    }

    /**
     * Handle email change request.
     */
    public function changeEmail(Request $request)
    {
        $user = Auth::user();

        // Check if the new email is the same as the current one
        if ($request->email === $user->email) {
            return response()->json([
                'success' => false,
                'message' => 'Your email is the same as the current one.'
            ], 422);
        }

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->employee_id . ',employee_id',
        ]);

        // Update email and set email_verified_at to null
        $user->update([
            'email' => $request->email,
            'email_verified_at' => null
        ]);

        return response()->json(['success' => true, 'message' => 'Email updated successfully!']);
    }

}
