<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class Department_Controller extends Controller
{
    public function getDepartments()
    {
        // Fetch all departments and group them by group_name
        $departments = Department::all()->groupBy('group_name');

        // Return the grouped departments as JSON
        return response()->json($departments);
    }
     public function index(Request $request)
    {
        // Get the search query from the request
        $search = $request->input('search');

        // Query departments with search filter
        $departments = Department::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
                         ->orWhere('group_name', 'like', '%' . $search . '%');
        })->paginate(10); // Paginate results (10 per page)

        // Pass the departments and search query to the view
        return view('department', compact('departments', 'search'));
    }

    /**
     * Store a newly created department in the database.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'group_name' => 'nullable|string|max:255',
        ]);

        // Create a new department
        Department::create($request->only('name', 'group_name'));

        // Redirect to the departments list with a success message
        return redirect()->route('department.index')->with('success', 'Department created successfully.');
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'group_name' => 'nullable|string|max:255',
        ]);

        // Find the department and update it
        $department = Department::findOrFail($id);
        $department->update($request->only('name', 'group_name'));

        // Redirect to the departments list with a success message
        return redirect()->route('department.index')->with('success', 'Department updated successfully.');
    }

    public function destroy($id)
    {
        // Find the department and delete it
        $department = Department::findOrFail($id);
        $department->delete();

        // Redirect to the departments list with a success message
        return redirect()->route('department.index')->with('success', 'Department deleted successfully.');
    }
}
