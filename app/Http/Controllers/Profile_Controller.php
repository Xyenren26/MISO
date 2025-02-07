<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Profile_Controller extends Controller
{
    /**
     * Show the profile completion form.
     *
     * @return \Illuminate\View\View
     */
    public function showCompleteProfileForm()
    {
        $user = Auth::user();

        // If the user has already completed their profile, redirect them to their home page
        if (!$user->is_first_login) {
            return redirect()->route('home')->with('info', 'Your profile is already complete.');
        }

        return view('profile.complete');
    }

    /**
     * Handle profile completion submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function completeProfile(Request $request)
    {
        // Validate the input fields
        $request->validate([
            'first-name' => 'required|string|max:255',
            'last-name' => 'required|string|max:255',
            'employee-id' => 'required|numeric|digits:7|unique:users,employee_id',
            'department' => 'required|string|max:255',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Set account type based on department
        $accountType = $request->department === 'Management Information Systems Office (MISO)' 
            ? 'technical_support' 
            : 'end_user';

        // Update the user profile
        $user->update([
            'first_name' => $request->input('first-name'),
            'last_name' => $request->input('last-name'),
            'department' => $request->input('department'),
            'employee_id' => $request->input('employee-id'),
            'account_type' => $accountType,
            'is_first_login' => false, // Mark profile as complete
        ]);

        // Fallback redirect if no condition is met
    return redirect()->route('login')->with('error', 'Successful Finish profile, please log in again.');
    }

    public function index()
    {
        $user = Auth::user();
        $role = $this->getRoleFromAccountType($user->account_type);

        return view('profile.profile', compact('user', 'role'));
    }

    private function getRoleFromAccountType($accountType)
    {
        switch ($accountType) {
            case 'administrator':
                return 'Administrator';
            case 'technical_support':
                return 'Technical Support';
            case 'end_user':
                return 'End User';
            default:
                return 'User';
        }
    }

}
