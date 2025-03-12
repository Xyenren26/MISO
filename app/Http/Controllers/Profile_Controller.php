<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'department' => 'required|string|max:255',
            'phone-number' => 'required|string|max:255',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Set account type based on department
        $accountType = $request->department === 'Technical Support Division (MISO)' 
            ? 'technical_support' 
            : 'end_user';

        // Update the user profile
        $user->update([
            'id' => $user->employee_id, // Keep employee_id
            'name' => $request->input('first-name') . ' ' . $request->input('last-name'), // Combine first and last name
            'first_name' => $request->input('first-name'),
            'last_name' => $request->input('last-name'),
            'department' => $request->input('department'),
            'phone_number' => $request->input('phone-number'),
            'account_type' => $accountType,
            'is_first_login' => false, // Mark profile as complete
        ]);

        // Logout the user and redirect to login
        Auth::logout();

        return redirect()->route('login')->with('success', 'Successful Finish profile, please log in again.');
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
    public function update(Request $request)
    {
        // Force Laravel to return JSON for debugging
        if (!$request->expectsJson()) {
            return response()->json(['error' => 'Invalid request type'], 400);
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:15',
        ]);

        try {
            $user = auth()->user();
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'name' => $request->input('first-name') . ' ' . $request->input('last-name'),
                'department' => $request->department,
                'phone_number' => $request->phone_number,
            ]);

            return response()->json(['success' => true, 'message' => 'Profile updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $user = Auth::user();
    
        // Check if the user already has an avatar and it's NOT named 'avatar'
        if ($user->avatar && $user->avatar !== 'avatar') {
            Storage::delete('public/users-avatar/' . $user->avatar);
        }
    
        // Save new avatar
        $fileName = time() . '.' . $request->avatar->extension();
        $request->avatar->storeAs('public/users-avatar/', $fileName);
        $user->avatar = $fileName;
        $user->save();
    
        return response()->json([
            'success' => true,
            'avatar' => asset('storage/users-avatar/' . $fileName)
        ]);
    }    
}
