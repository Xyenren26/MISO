<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\EquipmentDescription;
use App\Models\EquipmentPart;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\SystemNotification;
use Carbon\Carbon;

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

        $technicalSupport = User::where('employee_id', $request->technical_support_id)->first();
        if ($technicalSupport) {
            $technicalSupport->notify(new SystemNotification(
                'Service Request',
                'You have been assigned a new Service Request.',
                route('device_management', ['form_no' => $serviceRequest->form_no])
            ));
        }

        return redirect()->route('device_management')->with('success', 'Service request submitted successfully!');
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

        $serviceRequest->update(['status' => 'repaired']);

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
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
}
