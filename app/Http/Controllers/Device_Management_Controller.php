<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ServiceRequest;
use App\Models\EquipmentDescription;

class Device_Management_Controller extends Controller
{
    public function showDevice_Management(Request $request)
    {
        $filter = $request->query('filter', 'all');
        $search = $request->query('search', '');
        $conditionFilter = $request->query('condition', '');
        $technicalSupportFilter = $request->query('technical_support_id', ''); // Filter by technical support

        $query = ServiceRequest::with('equipmentDescriptions', 'technicalSupport');

        // Apply filtering based on status
        if ($filter === 'in-repairs') {
            $query->where('status', 'in-repairs');
        } elseif ($filter === 'repaired') {
            $query->where('status', 'repaired');
        } elseif ($filter === 'new-deployment') {
            $query->where('service_type', 'pull_out');
        }

        // Apply condition filter (if available)
        if ($conditionFilter && in_array($conditionFilter, ['working', 'not-working', 'needs-repair'])) {
            $query->where('condition', $conditionFilter);
        }

        // Apply technical support filter
        if (!empty($technicalSupportFilter)) {
            $query->where('technical_support_id', $technicalSupportFilter);
        }

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('form_no', 'like', "%$search%")
                  ->orWhere('department', 'like', "%$search%")
                  ->orWhereHas('equipmentDescriptions', function ($subQuery) use ($search) {
                      $subQuery->where('remarks', 'like', "%$search%");
                  });
            });
        }

        // Generate next form number
        $latestFormNo = ServiceRequest::latest('form_no')->first();
        $nextNumber = $latestFormNo ? str_pad((int)substr($latestFormNo->form_no, 9) + 1, 6, '0', STR_PAD_LEFT) : '000001';
        $nextFormNo = 'SRF-' . date('Y') . '-' . $nextNumber;

        $serviceRequests = $query->paginate(10);
        
        // Fetch all technical support personnel
        $technicalSupports = User::where('account_type', 'technical_support')->get();

        return view('Device_Management', compact('serviceRequests', 'filter', 'nextFormNo', 'conditionFilter', 'technicalSupports', 'technicalSupportFilter'));
    }
    public function getServiceRequest($form_no)
{
    // Fetch the service request along with its related data
    $serviceRequest = ServiceRequest::with(['equipmentDescriptions.equipmentParts', 'technicalSupport'])
        ->where('form_no', $form_no)
        ->firstOrFail();

    // Check if $serviceRequest is actually found
    if (!$serviceRequest) {
        // If no service request is found, handle the error (optional)
        return response()->json(['error' => 'Service request not found.'], 404);
    }

    // Prepare the technical support information, ensuring it's handled correctly
    $technicalSupportName = $serviceRequest->technicalSupport 
        ? $serviceRequest->technicalSupport->first_name . ' ' . $serviceRequest->technicalSupport->last_name
        : 'Unassigned';

    // Return the response as JSON with all the necessary data
    return response()->json([
        'form_no' => $serviceRequest->form_no,
        'department' => $serviceRequest->department,
        'condition' => $serviceRequest->condition ?? 'N/A',
        'technical_support' => $technicalSupportName,
        'equipment_descriptions' => $serviceRequest->equipmentDescriptions->map(function ($equipment) {
            // Get the parts that are present (where `is_present` is true), even if the parts array is empty
            $parts = $equipment->equipmentParts->where('is_present', true)->pluck('part_name')->toArray();
            return [
                'brand' => $equipment->brand,
                'equipment_type' => $equipment->equipment_type,
                'remarks' => $equipment->remarks,
                'equipment_parts' => $parts, // Return as array of parts that are present, even if empty
            ];
        }),
    ]);
}
}