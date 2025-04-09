<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Concern;
use App\Models\User;

class ConcernController extends Controller
{
    public function index()
    {
        $mainConcerns = Concern::where('type', 'main')
            ->with(['children', 'assignedUser'])
            ->get();
            
        $technicalStaff = User::where('account_type', 'technical_support')
            ->orderBy('name')
            ->get();

        return view('concerns.index', compact('mainConcerns', 'technicalStaff'));
    }

    public function edit(Concern $concern)
    {
        if(request()->ajax()) {
            return response()->json([
                'id' => $concern->id,
                'name' => $concern->name,
                'type' => $concern->type,
                'parent_id' => $concern->parent_id,
                'default_priority' => $concern->default_priority,
                'assigned_user_id' => $concern->assigned_user_id,
                'assign_to_all_tech' => $concern->assign_to_all_tech
            ]);
        }

        $mainConcerns = Concern::where('type', 'main')->get();
        $technicalStaff = User::where('account_type', 'technical_support')->get();
        
        return view('concerns.edit', compact('concern', 'mainConcerns', 'technicalStaff'));
    }

    public function store(Request $request)
    {
        \Log::info('Store method called with request data:', $request->all());

        try {
            \Log::info('Starting validation...');
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|in:main,sub',
                'parent_id' => 'required_if:type,sub|nullable|exists:concerns,id',
                'default_priority' => 'required|in:urgent,high,medium,low',
                'assigned_user_id' => 'nullable' // Fixed typo from 'assigned_user_id'
            ]);

            \Log::info('Validation passed with data:', $validated);

            $data = $request->all();
            
            if ($request->assigned_user_id === 'all') {
                $data['assign_to_all_tech'] = true;
                $data['assigned_user_id'] = null;
                \Log::info('Assigning to all technicians');
            } else {
                $data['assign_to_all_tech'] = false;
                \Log::info('Assigning to specific user: ' . $request->assigned_user_id);
            }

            \Log::info('Final data before creation:', $data);

            $concern = Concern::create($data);
            \Log::info('Concern created successfully with ID: ' . $concern->id);
            
            return redirect()->route('concerns.index')
                ->with('success', 'Concern created successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed:', $e->errors());
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error in store method:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'Failed to create concern: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Concern $concern)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:main,sub',
            'parent_id' => 'required_if:type,sub|nullable|exists:concerns,id',
            'default_priority' => 'required|in:urgent,high,medium,low',
            'assigned_user_id' => 'nullable'
        ]);

        $data = $request->all();
        
        if ($request->assigned_user_id === 'all') {
            $data['assign_to_all_tech'] = true;
            $data['assigned_user_id'] = null;
        } else {
            $data['assign_to_all_tech'] = false;
        }

        $concern->update($data);

        return redirect()->route('concerns.index')
            ->with('success', 'Concern updated successfully.');
    }

    public function destroy(Concern $concern)
    {
        $concern->delete();

        return redirect()->route('concerns.index')
            ->with('success', 'Concern deleted successfully.');
    }
}