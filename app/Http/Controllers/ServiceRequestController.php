<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\Approval;
use App\Models\EquipmentDescription;
use App\Models\EquipmentPart;
use App\Models\User;
use App\Models\Rating;
use Illuminate\Http\Request;
use App\Notifications\SystemNotification;
use App\Mail\ServiceRequestUpdated;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Events\NewNotification;

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
                'ticket_id' => 'nullable|exists:tickets,control_no', // Only validate if provided
                'service_type' => 'required|in:walk_in,pull_out',
                'name' => 'required|string|max:255',
                'employee_id' => 'required|integer|min:1',
                'department' => 'required|string|max:255',
                'condition' => 'required|array|min:1',
                'condition.*' => 'string',
                'technical_support_id' => 'required|exists:users,employee_id',
                'system_brand' => 'nullable|string|max:255',
                'monitor_brand' => 'nullable|string|max:255',
                'laptop_brand' => 'nullable|string|max:255',
                'printer_brand' => 'nullable|string|max:255',
                'ups_brand' => 'nullable|string|max:255',
                'monitor_remarks' => 'nullable|string|max:255',
                'laptop_remarks' => 'nullable|string|max:255',
                'printer_remarks' => 'nullable|string|max:255',
                'ups_remarks' => 'nullable|string|max:255',
            ]);

            \Log::info('Validation Passed:', $validated);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation Failed:', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        // Generate a dynamic form number (SRF-year-000001)
        $year = now()->year;
        $latestFormRequest = ServiceRequest::whereYear('created_at', $year)->latest('id')->first();
        $latestNumber = $latestFormRequest ? (int)substr($latestFormRequest->form_no, -6) : 0;
        $newFormNo = 'SRF-' . $year . '-' . str_pad($latestNumber + 1, 6, '0', STR_PAD_LEFT);

        // Save the service request
        $serviceRequest = ServiceRequest::create([
            'ticket_id' => $request->ticket_id ?? null, // Ensure ticket_id is nullable
            'form_no' => $newFormNo,
            'service_type' => $request->service_type,
            'name' => $request->name,
            'employee_id' => $request->employee_id,
            'department' => $request->department,
            'condition' => $request->condition[0] ?? null, // Save only the first value
            'technical_support_id' => $request->technical_support_id,
        ]);

        \Log::info('Service Request Saved', ['id' => $serviceRequest->id ?? 'Failed']);

        // Save Equipment Description and Parts
        $this->saveEquipmentDescriptions($newFormNo, $request);

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
        $admin = User::where('account_type', 'administrator')->first();

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
        // Equipment Descriptions data
        $equipmentDescriptions = [
            [
                'equipment_type' => 'System Unit',
                'brand' => $request->system_brand ?? '', // Fallback to empty string if null
                'motherboard' => $request->system_motherboard ? 1 : 0,
                'ram' => $request->system_ram ? 1 : 0,
                'hdd' => $request->system_hdd ? 1 : 0,
                'accessories' => $request->system_accessories ? 1 : 0,
                'remarks' => $request->system_remarks ?? '', // Fallback to empty string if null
            ],
            [
                'equipment_type' => 'Monitor',
                'brand' => $request->monitor_brand ?? '',  // Fallback to empty string if null
                'remarks' => $request->monitor_remarks ?? '', // Fallback to empty string if null
            ],
            [
                'equipment_type' => 'Laptop',
                'brand' => $request->laptop_brand ?? '',
                'motherboard' => $request->laptop_motherboard ? 1 : 0,
                'ram' => $request->laptop_ram ? 1 : 0,
                'hdd' => $request->laptop_hdd ? 1 : 0,
                'accessories' => $request->laptop_accessories ? 1 : 0,
                'remarks' => $request->laptop_remarks ?? '',
            ],
            [
                'equipment_type' => 'Printer',
                'brand' => $request->printer_brand ?? '',
                'remarks' => $request->printer_remarks ?? '',
            ],
            [
                'equipment_type' => 'UPS',
                'brand' => $request->ups_brand ?? '',
                'remarks' => $request->ups_remarks ?? '',
            ]
        ];

        // Iterate over equipment descriptions and save parts
        foreach ($equipmentDescriptions as $equipment) {
            $equipmentDescription = EquipmentDescription::create([
                'form_no' => $formNo,
                'equipment_type' => $equipment['equipment_type'],
                'brand' => $equipment['brand'],
                'remarks' => $equipment['remarks'],
            ]);

            // Define parts for each equipment type
            $parts = [];
            if (isset($equipment['motherboard'])) $parts[] = ['name' => 'Motherboard', 'is_present' => $equipment['motherboard']];
            if (isset($equipment['ram'])) $parts[] = ['name' => 'RAM', 'is_present' => $equipment['ram']];
            if (isset($equipment['hdd'])) $parts[] = ['name' => 'HDD', 'is_present' => $equipment['hdd']];
            if (isset($equipment['accessories'])) $parts[] = ['name' => 'Accessories', 'is_present' => $equipment['accessories']];

            // Save the equipment parts
            foreach ($parts as $part) {
                EquipmentPart::create([
                    'equipment_description_id' => $equipmentDescription->id,
                    'part_name' => $part['name'],
                    'is_present' => $part['is_present'],
                ]);
            }
        }
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

        return response()->json(['success' => true, 'message' => 'Status updated and email sent successfully']);
    }

    public function checkServiceRequest($ticketId)
    {
        $serviceRequest = ServiceRequest::where('ticket_id', $ticketId)->with('equipmentDescriptions.equipmentParts')->first();

        if ($serviceRequest) {
            return response()->json([
                'exists' => true,
                'formNo' => $serviceRequest->form_no ?? null,
            ]);
        }

        return response()->json(['exists' => false]);
    }

    public function getServiceRequest($form_no)
    {
        // Fetch the service request along with its related data
        $serviceRequest = ServiceRequest::with(['equipmentDescriptions.equipmentParts', 'technicalSupport'])
            ->where('form_no', $form_no)
            ->firstOrFail(); // Automatically returns 404 if not found

        // Prepare the technical support information
        $technicalSupportName = $serviceRequest->technicalSupport 
            ? $serviceRequest->technicalSupport->first_name . ' ' . $serviceRequest->technicalSupport->last_name
            : 'Unassigned';

        // Get the ticket_id from ServiceRequest
        $ticketId = $serviceRequest->ticket_id;

        // Fetch approval details
        $approval = Approval::where('ticket_id', $ticketId)->first();
        // Fetch rating based on control_no
        $rating = Rating::where('control_no', $serviceRequest->ticket_id)->first();

        // Return the response as JSON
        return response()->json([
            'rating' => $rating ? $rating->rating : null, 
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
                    'brand' => $equipment->brand,
                    'equipment_type' => $equipment->equipment_type,
                    'remarks' => $equipment->remarks,
                    'equipment_parts' => $equipment->equipmentParts->where('is_present', true)->pluck('part_name')->toArray(),
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
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'employee_id' => 'required|integer|min:1',
                'department' => 'required|string|max:255',
                'condition' => 'required|array|min:1',
                'condition.*' => 'string',
                'technical_support_id' => 'required|exists:users,employee_id',
                'system_brand' => 'nullable|string|max:255',
                'monitor_brand' => 'nullable|string|max:255',
                'laptop_brand' => 'nullable|string|max:255',
                'printer_brand' => 'nullable|string|max:255',
                'ups_brand' => 'nullable|string|max:255',
                'monitor_remarks' => 'nullable|string|max:255',
                'laptop_remarks' => 'nullable|string|max:255',
                'printer_remarks' => 'nullable|string|max:255',
                'ups_remarks' => 'nullable|string|max:255',
            ]);

            $formNo = $request->input('form_no');
            $serviceRequest = ServiceRequest::where('form_no', $formNo)->first();

            if ($serviceRequest) {
                $serviceRequest->update($validated);
                return response()->json(['message' => 'Service request updated successfully!']);
            } else {
                return response()->json(['error' => 'Service request not found.'], 404);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the service request.'], 500);
        }
    }
}
