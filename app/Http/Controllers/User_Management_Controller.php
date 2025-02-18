<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Import the model
use Illuminate\Support\Facades\Hash; // To hash the password

class User_Management_Controller extends Controller
{
    public function showUser_Management(Request $request) {
        $users = User::query()
            ->when($request->has('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('employee_id', 'LIKE', "%$search%")
                      ->orWhere('first_name', 'LIKE', "%$search%")
                      ->orWhere('last_name', 'LIKE', "%$search%")
                      ->orWhere('department', 'LIKE', "%$search%");
                });
            })
            ->when($request->has('filter') && !empty($request->filter), function ($query) use ($request) {
                $query->where('account_type', $request->filter);
            })
            ->orderBy('first_name')
            ->paginate(10); // Paginate with 10 items per page
    
        if ($request->ajax()) {
            // Return only the table body content for AJAX requests
            return response()->json([
                'html' => view('user_management', compact('users'))->render(),
                'pagination' => $users->links()->toHtml() // Include pagination links
            ]);
        }
    
        return view('user_management', compact('users')); // ✅ No overwriting
    }

    public function update(Request $request, $employee_id) {
        $user = User::where('employee_id', $employee_id)->firstOrFail();
    
        // Conditionally validate fields based on editOption
        $validatedData = $request->validate([
            'newEmployeeId' => 'nullable|numeric|digits:7|unique:users,employee_id,' . $user->employee_id, // Make employee ID nullable and unique, skip if not provided
            'newPassword' => 'nullable|min:8', // Make password nullable
        ]);
        
        // Only update the employee_id if it's provided
        if ($request->has('newEmployeeId') && $request->newEmployeeId !== null) {
            $user->employee_id = $request->newEmployeeId;
        }
        
        // Only update the password if it's provided
        if ($request->has('newPassword') && $request->newPassword !== null) {
            $user->password = Hash::make($request->newPassword);
        }
        
        $user->save();
        
        return redirect()->route('user_management')->with('message', 'User updated successfully.');
    }
        
    

    public function changeRole(Request $request, $employee_id) {
        $user = User::where('employee_id', $employee_id)->firstOrFail();

        $request->validate([
            'newRole' => 'required|in:end_user,technical_support,administrator',
        ]);

        $user->account_type = $request->newRole;
        $user->save();

        return redirect()->route('user_management')->with('message', 'User role updated successfully.');
    }

    public function toggleStatus($employee_id) {
        $user = User::where('employee_id', $employee_id)->firstOrFail();

        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        return redirect()->route('user_management')->with('message', 'User status updated successfully.');
    }
}