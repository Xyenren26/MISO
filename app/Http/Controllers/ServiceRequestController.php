<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\EquipmentDescription;
use App\Models\EquipmentPart;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ServiceRequestController extends Controller
{
    public function create()
    {
        return view('service-request-form');
    }

    public function store(Request $request)
    {
        // Validate the form input
        $validated = $request->validate([
            'service_type' => 'required|in:walk_in,pull_out',
            'department' => 'required|string|max:255',
            'condition' => 'required|array|min:1',  // Ensure at least one condition is selected
            'condition.*' => 'string',
            // other validations...
        ]);

        // Generate a dynamic form number (SRF-year-000001)
        $year = Carbon::now()->year;
        $latestFormRequest = ServiceRequest::whereYear('created_at', $year)->latest()->first();
        $latestNumber = $latestFormRequest ? (int)substr($latestFormRequest->form_no, -6) : 0;
        $newFormNo = 'SRF-' . $year . '-' . str_pad($latestNumber + 1, 6, '0', STR_PAD_LEFT);

        // Save the condition as a comma-separated string
        $serviceRequest = ServiceRequest::create([
            'form_no' => $newFormNo,
            'service_type' => $request->service_type, // Store the service type
            'department' => $request->department,
            'condition' => implode(',', $request->condition ?? []), // Store conditions as a comma-separated string
        ]);        

        // Save the Equipment Description and Parts
        $this->saveEquipmentDescriptions($newFormNo, $request);

        // Return a response, or redirect to another page with a success message
        return redirect()->route('device_management')->with('success', 'Service request submitted successfully!');
    }

    private function saveEquipmentDescriptions($formNo, $request)
    {
        // Equipment Descriptions data
        $equipmentDescriptions = [
            [
                'equipment_type' => 'System Unit',
                'brand' => $request->system_brand,
                'motherboard' => $request->system_motherboard ? 1 : 0,
                'ram' => $request->system_ram ? 1 : 0,
                'hdd' => $request->system_hdd ? 1 : 0,
                'accessories' => $request->system_accessories ? 1 : 0,
                'remarks' => $request->system_remarks,
            ],
            [
                'equipment_type' => 'Monitor',
                'brand' => $request->monitor_brand,
                'remarks' => $request->monitor_remarks,
            ],
            [
                'equipment_type' => 'Laptop',
                'brand' => $request->laptop_brand,
                'motherboard' => $request->laptop_motherboard ? 1 : 0,
                'ram' => $request->laptop_ram ? 1 : 0,
                'hdd' => $request->laptop_hdd ? 1 : 0,
                'accessories' => $request->laptop_accessories ? 1 : 0,
                'remarks' => $request->laptop_remarks,
            ],
            [
                'equipment_type' => 'Printer',
                'brand' => $request->printer_brand,
                'remarks' => $request->printer_remarks,
            ],
            [
                'equipment_type' => 'UPS',
                'brand' => $request->ups_brand,
                'remarks' => $request->ups_remarks,
            ]
        ];

        // Iterate over equipment descriptions and save parts as needed
        foreach ($equipmentDescriptions as $equipment) {
            $equipmentDescription = EquipmentDescription::create([
                'form_no' => $formNo,
                'equipment_type' => $equipment['equipment_type'],
                'brand' => $equipment['brand'],
                'remarks' => $equipment['remarks'],
            ]);

            // Define the parts for each equipment type
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
}
