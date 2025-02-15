<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token)
    {
        $expires = $request->input('expires');
        
        if (!$expires || Carbon::now()->timestamp > $expires) {
            return redirect()->route('password.request')->with('error', 'This reset link has expired.');
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }


    public function reset(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'password' => 'required|min:8|confirmed',
        'token' => 'required'
    ]);

    $resetRecord = DB::table('password_reset_tokens')->where('email', $request->email)->first();

    if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
        return back()->withErrors(['email' => 'Invalid or expired token for this email.']);
    }

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->update(['password' => Hash::make($password)]);
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
}

}
