<?php

namespace App\Http\Controllers;

use App\Models\Deployment;
use App\Models\EquipmentItem;
use Illuminate\Http\Request;

class DeploymentController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validate the input
            $validated = $request->validate([
                'control_number' => 'required|unique:deployments',
                'purpose' => 'required',
                'status' => 'required|in:new,used',
                'components' => 'nullable|array',
                'software' => 'nullable|array',
                'brand_name' => 'nullable|string',
                'specification' => 'nullable|string',
                'received_by' => 'required|string',
                'received_date' => 'nullable|date', 
                'issued_by' => 'required|string',
                'issued_date' => 'nullable|date',
                'noted_by' => 'required|string',
                'noted_date' => 'nullable|date',
                'equipment_items' => 'required|array',  // For equipment items
            ]);
    
            // Create the deployment record
            $deployment = Deployment::create([
                'control_number' => $request->control_number,
                'purpose' => $request->purpose,
                'status' => $request->status,
                'components' => $request->components,
                'software' => $request->software,
                'brand_name' => $request->brand_name,
                'specification' => $request->specification,
                'received_by' => $request->received_by,
                'received_date' => $request->received_date,
                'issued_by' => $request->issued_by,
                'issued_date' => $request->issued_date,
                'noted_by' => $request->noted_by,
                'noted_date' => $request->noted_date,
            ]);
    
            // Create equipment items
            foreach ($request->equipment_items as $item) {
                EquipmentItem::create([
                    'deployment_id' => $deployment->id,
                    'description' => $item['description'],
                    'serial_number' => $item['serial_number'],
                    'quantity' => $item['quantity'],
                ]);
            }
    
            // Return JSON response instead of redirect
            return response()->json([
                'message' => 'Deployment created successfully',
                'deployment_id' => $deployment->id
            ], 201);  // HTTP 201 Created
        } catch (\Exception $e) {
            // Return error response if something goes wrong
            return response()->json([
                'error' => 'Something went wrong while creating the deployment',
                'message' => $e->getMessage()
            ], 500);  // HTTP 500 Internal Server Error
        }
    }
}
