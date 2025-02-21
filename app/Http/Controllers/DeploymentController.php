<?php

namespace App\Http\Controllers;

use App\Models\Deployment;
use App\Models\EquipmentItem;
use App\Models\User;
use App\Models\EquipmentDescription;
use Illuminate\Http\Request;
use App\Models\Approval;
use App\Notifications\SystemNotification;
use App\Events\NewNotification;

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
                'equipment_items' => 'required|array',
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

            // Retrieve the administrator
            $admins = User::where('account_type', 'administrator')->get();

            if ($admins->isNotEmpty()) {
                foreach ($admins as $admin) {
                    $admin->notify(new SystemNotification(
                        'TicketUpdated',
                        'A new update has been made to a ticket that requires your approval.',
                        route('ticket', $Deployment->control_number)
                    ));
                }
            }
            
            // Send real-time notification
            $notification = $request->input('control_number');
            event(new NewNotification($notification));
            

            // Redirect to a success page or return a response
            return redirect()->route('ticket')->with('success', 'Deployment created successfully.');
        } catch (\Exception $e) {
            // Handle errors
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function showDeployment($control_no)
    {
        $deployment = Deployment::with('equipmentItems')
        ->where('control_number', $control_no)
        ->first(); // Use first() instead of findOrFail()
    
        if (is_string($deployment->components)) {
            $deployment->components = json_decode($deployment->components, true);
        }
        if (is_string($deployment->software)) {
            $deployment->software = json_decode($deployment->software, true);
        }
        // Fetch approval details
        $approval = Approval::where('ticket_id', $control_no)->first();
    
        $responseData = [
            'purpose' => $deployment->purpose,
            'control_number' => $deployment->control_number,
            'status' => $deployment->status,
            'components' => $deployment->components, 
            'software' => $deployment->software, 
            'equipment_items' => $deployment->equipmentItems->map(function ($item) {
                return [
                    'description' => $item->description,
                    'serial_number' => $item->serial_number,
                    'quantity' => $item->quantity,
                ];
            }),
            'brand_name' => $deployment->brand_name,
            'specification' => $deployment->specification,
            'received_by' => $deployment->received_by,
            'issued_by' => $deployment->issued_by,
            'received_date' => $deployment->received_date,
            'issued_date' => $deployment->issued_date,
            'approval' => [
                'name' => optional($approval)->name ?? "Not Available",
                'approve_date' => optional($approval)->approve_date ?? "Not Available"
            ]
        ];
    
        return response()->json($responseData);
    }

    public function checkDeployment($control_no)
    {
        $exists = \App\Models\Deployment::where('control_number', $control_no)->exists();

        return response()->json(['exists' => $exists]);
    }

}
