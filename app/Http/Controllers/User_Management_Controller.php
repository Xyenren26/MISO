<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Import the model
use Illuminate\Support\Facades\Hash; // To hash the password

class User_Management_Controller extends Controller
{
    // Show user management page with all users
    public function showUser_Management(){
        $users = User::all(); // Fetch all users
        return view('user_management', ['users' => $users]);
    }

    // Edit user details
    public function updateUser(Request $request, $employee_id) {
        $user = User::find($employee_id); // Find the user by Employee ID
        if (!$user) {
            return redirect()->route('user.management')->with('error', 'User not found');
        }

        // Validate input
        $validatedData = $request->validate([
            'employee_id' => 'required|unique:users,employee_id,' . $employee_id,
            'password' => 'nullable|min:6|confirmed', // Password can be null but must match the confirmation
        ]);

        // Update the user data
        $user->employee_id = $request->employee_id;
        if ($request->password) {
            $user->password = Hash::make($request->password); // Hash the password
        }
        $user->save();

        return redirect()->route('user.management')->with('success', 'User updated successfully');
    }

    // Delete a user
    public function deleteUser($employee_id) {
        $user = User::find($employee_id); // Find the user by Employee ID
        if (!$user) {
            return redirect()->route('user.management')->with('error', 'User not found');
        }
        $user->delete(); // Delete the user
        return redirect()->route('user.management')->with('success', 'User deleted successfully');
    }

    public function update(Request $request, $employee_id)
    {
        // Your logic for updating the user based on the $employee_id
        $user = User::findOrFail($employee_id);
    
        // Handle the update of either employee_id or password
        if ($request->has('editOption') && $request->input('editOption') === 'employee_id') {
            $user->employee_id = $request->input('newEmployeeId');
        } elseif ($request->has('editOption') && $request->input('editOption') === 'password') {
            $user->password = bcrypt($request->input('newPassword'));
        }
    
        $user->save();
    
        return redirect()->route('user.management')->with('success', 'User updated successfully.');
    }
    
    public function disable($employee_id)
{
    $user = User::findOrFail($employee_id);
    $user->status = 'inactive'; // or 'disabled', depending on your naming preference
    $user->save();

    return redirect()->route('user.management')->with('message', 'User has been disabled.');
}

public function toggleStatus($employee_id)
{
    $user = User::find($employee_id);
    $newStatus = $user->status === 'active' ? 'inactive' : 'active'; // Toggle status
    $user->status = $newStatus;
    $user->save();
    
    return redirect()->back()->with('message', 'User status updated successfully.');
}

}
