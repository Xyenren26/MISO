<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\EquipmentDescription;

class Device_Management_Controller extends Controller
{
    public function showDevice_Management(Request $request)
{
    $filter = $request->query('filter', 'all');
    $search = $request->query('search', '');
    $conditionFilter = $request->query('condition', ''); // New condition filter

    $query = ServiceRequest::with('equipmentDescriptions');

    // Apply filtering based on status
    if ($filter === 'in-repairs') {
        $query->where('status', 'in-repairs');
    } elseif ($filter === 'repaired') {
        $query->where('status', 'repaired');
    } elseif ($filter === 'new-deployment') {
        $query->where('service_type', 'pull_out');
    }

    // Apply condition filter (if available) for Enum column
    if ($conditionFilter && in_array($conditionFilter, ['working', 'not-working', 'needs-repair'])) {
        $query->where('condition', $conditionFilter); // Compare with enum values
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

    return view('Device_Management', compact('serviceRequests', 'filter', 'nextFormNo', 'conditionFilter'));
}


    public function addRemarks(Request $request, $formNo)
    {
        $request->validate(['remark' => 'required|string|max:255']);

        $serviceRequest = ServiceRequest::where('form_no', $formNo)->firstOrFail();
        $serviceRequest->equipmentDescriptions()->update(['remarks' => $request->remark]);

        return response()->json(['success' => true]);
    }

    public function getServiceRequest($form_no)
    {
        $serviceRequest = ServiceRequest::with(['equipmentDescriptions.equipmentParts'])
            ->where('form_no', $form_no)
            ->firstOrFail();

        return response()->json([
            'form_no' => $serviceRequest->form_no,
            'department' => $serviceRequest->department,
            'condition' => $serviceRequest->condition ?? 'N/A',
            'equipment_descriptions' => $serviceRequest->equipmentDescriptions->map(function ($equipment) {
                return [
                    'brand' => $equipment->brand,
                    'equipment_type' => $equipment->equipment_type,
                    'remarks' => $equipment->remarks,
                    'equipment_parts' => $equipment->equipmentParts->map(function ($part) {
                        return ['part_name' => $part->part_name];
                    }),
                ];
            }),
        ]);
    }

}
