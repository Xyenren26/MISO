<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\Approval;
use App\Models\EquipmentDescription;
use App\Models\EquipmentPart;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Rating;
use Illuminate\Http\Request;
use App\Notifications\SystemNotification;
use App\Mail\ServiceRequestUpdated;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Events\NewNotification;
use Chatify\Facades\ChatifyMessenger as Chatify; 
use App\Models\ChMessage;

class ServiceRequestController extends Controller
{
    public function store(Request $request)
    {
        \Log::info('Received request:', $request->all()); // Log the input request
    
        try {
            $request->merge([
                'ticket_id' => ($request->ticket_id === 'formPopup' || empty($request->ticket_id)) ? null : $request->ticket_id,
            ]);
    
            // Validate the form input
            $validated = $request->validate([
                'ticket_id' => 'nullable|exists:tickets,control_no',
                'service_type' => 'required|in:walk_in,pull_out',
                'name' => 'required|string|max:255',
                'employee_id' => 'required|integer|min:1',
                'department' => 'required|string|max:255',
                'condition' => 'required|array|min:1',
                'condition.*' => 'string',
                'technical_support_id' => 'required|exists:users,employee_id',
                'equipment.*.brand' => 'required|string|max:255',
                'equipment.*.device' => 'required|string|max:255',
                'equipment.*.description' => 'required|string|max:255',
                'equipment.*.remarks' => 'nullable|string|max:255',
            ]);
    
            \Log::info('Validation Passed:', $validated);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation Failed:', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    
        // Check if a ServiceRequest already exists for the given ticket_id
        $serviceRequest = ServiceRequest::where('ticket_id', $request->ticket_id)->first();
    
        if ($serviceRequest) {
            // Update the existing record
            $serviceRequest->update([
                'service_type' => $request->service_type,
                'name' => $request->name,
                'employee_id' => $request->employee_id,
                'department' => $request->department,
                'condition' => $request->condition[0] ?? null, // Save only the first value
                'technical_support_id' => $request->technical_support_id,
                'status' => 'in-repairs',
            ]);
    
            \Log::info('Service Request Updated', ['id' => $serviceRequest->id]);
        } else {
            // Generate a dynamic form number (SRF-year-000001)
            $year = now()->year;
            $latestFormRequest = ServiceRequest::whereYear('created_at', $year)->latest('id')->first();
            $latestNumber = $latestFormRequest ? (int)substr($latestFormRequest->form_no, -6) : 0;
            $newFormNo = 'SRF-' . $year . '-' . str_pad($latestNumber + 1, 6, '0', STR_PAD_LEFT);
    
            // Create a new record
            $serviceRequest = ServiceRequest::create([
                'ticket_id' => $request->ticket_id ?? null,
                'form_no' => $newFormNo,
                'service_type' => $request->service_type,
                'name' => $request->name,
                'employee_id' => $request->employee_id,
                'department' => $request->department,
                'condition' => $request->condition[0] ?? null, // Save only the first value
                'technical_support_id' => $request->technical_support_id,
                'status' => 'in-repairs',
            ]);
    
            \Log::info('Service Request Created', ['id' => $serviceRequest->id]);
        }
    
        // Save Equipment Description and Parts
        $this->saveEquipmentDescriptions($serviceRequest->form_no, $request);
    
        // Notify the assigned technical support personnel
        $technicalSupport = User::where('employee_id', $request->technical_support_id)->first();
    
        if ($technicalSupport) {
            $technicalSupport->notify(new SystemNotification(
                'Service Request',
                'You have been assigned a new Service Request.',
                route('ticket', ['form_no' => $serviceRequest->form_no])
            ));
        }
    
        // Retrieve the administrator
        $admin = User::where('account_type', 'technical_support_head')->first();
    
        if ($admin) {
            $notification = new SystemNotification(
                'TicketUpdated',
                'A new update has been made to a ticket that requires your approval.',
                route('ticket')
            );
    
            $admin->notify($notification);
        }
    
        $notification = $request->input('ticket_id');
        event(new NewNotification($notification));
    
        return redirect()->route('ticket')->with('success', 'Service request submitted successfully!');
    }

    private function saveEquipmentDescriptions($formNo, $request)
    {
        $equipmentData = $request->input('equipment', []); // Default to an empty array
    
        foreach ($equipmentData as $device) {
            EquipmentDescription::create([
                'form_no' => $formNo,
                'brand' => $device['brand'] ?? 'Unknown',
                'device' => $device['device'] ?? 'Unknown',
                'description' => $device['description'] ?? '',
                'serial_no' => $device['serial_no'] ?? '',
                'remarks' => $device['remarks'] ?? '',
            ]);
        }
    }
    
    public function checkServiceRequest($ticketId)
    {
        // Fetch the ticket details
        $ticket = Ticket::find($ticketId);

        if (!$ticket) {
            return response()->json([
                'exists' => false,
                'message' => 'Ticket not found.',
            ], 404);
        }

        // Fetch the service request with related equipment descriptions
        $serviceRequest = ServiceRequest::where('ticket_id', $ticketId)
            ->with('equipmentDescriptions')
            ->first();

        // Check if the service request exists and has a non-null services_type
        if ($serviceRequest && !is_null($serviceRequest->service_type)) {
            return response()->json([
                'exists' => true,
                'formNo' => $serviceRequest->form_no ?? null,
                'ticket' => $ticket, // Always return the ticket data
            ]);
        }

        // If the service request does not exist or services_type is null
        return response()->json([
            'exists' => false,
            'ticket' => $ticket, // Always return the ticket data
            'serviceRequest' => $serviceRequest, // Return the service request data if it exists
        ]);
    }

    public function updateStatus(Request $request, $form_no)
    {
        $serviceRequest = ServiceRequest::where('form_no', $form_no)->first();

        if (!$serviceRequest) {
            return response()->json(['success' => false, 'message' => 'Service request not found'], 404);
        }

        // Update the status
        $serviceRequest->update(['status' => 'repaired']);

        // Get the user email based on employee_id
        $user = User::where('employee_id', $serviceRequest->employee_id)->first();

        if ($user && $user->email) {
            Mail::to($user->email)->send(new ServiceRequestUpdated($serviceRequest));
        }

        // Retrieve the end user ID (assuming ticket has a `user_id` field)
        $EndUserID = $serviceRequest->employee_id;

        $message = "Hello, {$serviceRequest->name} your ticket with Ticket No:{$serviceRequest->ticket_id} 
        has been mark as repaired and ready to be redeploy.
       
        If you have any question related to your Ticket Service Request Please fill free to Inquire.
        
        Thank you by TechTrack Team.";

        // Create a new message to notify the end user
        ChMessage::create([
        'from_id' => auth()->id(), // Sender (could be admin or support staff)
        'to_id' => $EndUserID, // Recipient (end user)
        'body' => $message, // The predefined message content
        'created_at' => now(),
        'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Status updated and email sent successfully']);
    }

    public function getServiceRequest($form_no)
    {
        // Fetch the service request with related data
        $serviceRequest = ServiceRequest::with([
            'equipmentDescriptions', 
            'technicalSupport',
            'rating' // Added eager loading for rating
        ])->where('form_no', $form_no)->firstOrFail();
    
        // Prepare technical support information
        $technicalSupportName = optional($serviceRequest->technicalSupport)->first_name . ' ' . optional($serviceRequest->technicalSupport)->last_name ?? 'Unassigned';
    
        // Get the ticket_id from ServiceRequest (if exists)
        $ticketId = optional($serviceRequest)->ticket_id;
    
        // Fetch approval details
        $approval = Approval::where('ticket_id', $ticketId)->first();
    
        // Return response as JSON
        return response()->json([
            'rating' => optional($serviceRequest->rating)->rating ?? null, // Optimized rating fetching
            'form_no' => $serviceRequest->form_no,
            'service_type' => $serviceRequest->service_type ?? 'N/A',
            'name' => $serviceRequest->name,
            'employee_id' => $serviceRequest->employee_id,
            'status' => $serviceRequest->status,
            'department' => $serviceRequest->department,
            'condition' => $serviceRequest->condition ?? 'N/A',
            'technical_support' => $technicalSupportName,
    
            // Equipment details
            'equipment_descriptions' => $serviceRequest->equipmentDescriptions->map(function ($equipment) {
                return [
                    'id' => $equipment->id ?? 'N/A',
                    'brand' => $equipment->brand ?? 'N/A',
                    'description' => $equipment->description ?? 'N/A',
                    'device' => $equipment->device ?? 'Unknown',
                    'serial_no' => $equipment->serial_no ?? 'Unknown',
                    'remarks' => $equipment->remarks ?? 'N/A',
                ];
            }),
    
            // Approval details
            'approval' => [
                'name' => optional($approval)->name ?? "Not Available",
                'approve_date' => optional($approval)->approve_date ?? "Not Available"
            ]
        ]);
    }
    
    public function update(Request $request)
    {
        \Log::info('Received equipment update request:', $request->all());
    
        try {
            // Validate the request
            $validated = $request->validate([
                'form_no' => 'required|string|exists:service_requests,form_no',
                'equipment' => 'required|array|min:1',
                'equipment.*.id' => 'required|exists:equipment_descriptions,id', // Use id for unique identification
                'equipment.*.brand' => 'required|string|max:255', // Read-only, but still validated
                'equipment.*.device' => 'required|string|max:255', // Editable
                'equipment.*.description' => 'required|string|max:255', // Editable
                'equipment.*.serial_no' => 'required|string|max:255', // Read-only, but still validated
                'equipment.*.remarks' => 'nullable|string|max:255', // Editable
            ]);
    
            // Find the service request by form_no
            $serviceRequest = ServiceRequest::where('form_no', $request->form_no)->first();
    
            if ($serviceRequest) {
                // Update each equipment description
                foreach ($request->equipment as $equipment) {
                    \Log::info('Updating equipment:', [
                        'form_no' => $request->form_no,
                        'id' => $equipment['id'], // Log the id for debugging
                    ]);
    
                    // Find the equipment description by id
                    $equipmentDescription = EquipmentDescription::find($equipment['id']);
    
                    if ($equipmentDescription) {
                        // Update the equipment description
                        $equipmentDescription->update([
                            'brand' => $equipment['brand'],
                            'device' => $equipment['device'],
                            'description' => $equipment['description'],
                            'serial_no' => $equipment['serial_no'],
                            'remarks' => $equipment['remarks'],
                        ]);
    
                        \Log::info('Equipment updated successfully:', [
                            'form_no' => $request->form_no,
                            'id' => $equipment['id'],
                        ]);
                    } else {
                        \Log::error('Failed to find equipment:', [
                            'form_no' => $request->form_no,
                            'id' => $equipment['id'],
                        ]);
                    }
                }
    
                \Log::info('Equipment descriptions updated:', ['form_no' => $request->form_no]);
                return response()->json(['success' => true, 'message' => 'Equipment descriptions updated successfully!']);
            } else {
                \Log::error('Service Request Not Found:', ['form_no' => $request->form_no]);
                return response()->json(['success' => false, 'error' => 'Service request not found.'], 404);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation Failed:', ['errors' => $e->errors()]);
            return response()->json(['success' => false, 'error' => $e->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Error Updating Equipment Descriptions:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => 'An error occurred while updating equipment descriptions.'], 500);
        }
    }
}
