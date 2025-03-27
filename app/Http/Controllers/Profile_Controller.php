<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        $currentSessionId = session()->getId();
        // Generate & save new session_id and remember_token
        $user->remember_token = Str::random(60);
        $user->session_id = $currentSessionId; // Store the new session ID
        $user->last_activity = now();
        $user->active_status = true;
        $user->save();

        session(['user_id' => $user->id, 'last_activity' => now()]);
        return $this->redirectUser($user);
    }

     // 🔹 Helper function to redirect users based on account type
     private function redirectUser($user)
     {
         if ($user->account_type === 'end_user') {
            $currentSessionId = session()->getId();

            // Generate & save new session_id and remember_token
            $user->remember_token = Str::random(60);
            $user->session_id = $currentSessionId; // Store the new session ID
            $user->last_activity = now();
            $user->active_status = true;
            $user->save();

            session(['user_id' => $user->id, 'last_activity' => now()]);
             return redirect('/employee/home'); // Redirect to employee's home page
         } elseif ($user->account_type === 'technical_support_head') {
             return redirect()->route('ticket'); // Redirect technical_support_head to Ticket Management
         } elseif ($user->account_type === 'administrator') {
             return redirect()->route('report'); // Redirect administrator to Reports and Analytics
         } elseif ($user->account_type === 'technical_support') {
             return redirect('/home'); // Redirect to employee's home page
         }
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
        // Validate the request
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Get the authenticated user
        $user = Auth::user();
    
        // Debug: Log uploaded file details
        \Log::info('Uploaded file:', [
            'name' => $request->avatar->getClientOriginalName(),
            'size' => $request->avatar->getSize(),
            'mime' => $request->avatar->getMimeType(),
        ]);
    
        // Get configuration values with fallbacks
        $avatarFolder = Config::get('chatify.user_avatar.folder', 'public/users-avatar');
        $defaultAvatar = Config::get('chatify.user_avatar.default', 'avatar.png');
        $disk = Config::get('chatify.user_avatar.disk', 'public');
    
        // Debug: Log configuration values
        \Log::info('Configuration:', [
            'folder' => $avatarFolder,
            'default' => $defaultAvatar,
            'disk' => $disk,
        ]);
    
        // Check if the user already has an avatar and it's NOT the default 'avatar'
        if ($user->avatar && $user->avatar !== $defaultAvatar) {
            // Delete the old avatar
            Storage::disk($disk)->delete($avatarFolder . '/' . $user->avatar);
        }
    
        // Ensure the directory exists
        if (!Storage::disk($disk)->exists($avatarFolder)) {
            Storage::disk($disk)->makeDirectory($avatarFolder);
        }
    
        // Save the new avatar
        $fileName = Str::uuid() . '.' . $request->avatar->extension();
        try {
            $path = $request->avatar->storeAs($avatarFolder, $fileName, $disk);
            \Log::info('File saved at: ' . Storage::disk($disk)->path($path));
        } catch (\Exception $e) {
            \Log::error('Error saving file: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error saving avatar.'], 500);
        }
    
        // Update the user's avatar
        $user->avatar = $fileName;
        $user->save();
    
        // Debug: Log the updated user avatar
        \Log::info('User avatar updated:', ['avatar' => $user->avatar]);
    
        // Return JSON response with the new avatar URL
        return response()->json([
            'success' => true,
            'avatar' => Storage::disk($disk)->url($avatarFolder . '/' . $fileName),
        ]);
    }
}
