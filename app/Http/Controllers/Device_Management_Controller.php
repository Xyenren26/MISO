<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Collection;
use App\Models\User;
use App\Models\ServiceRequest;
use App\Models\Deployment;
use App\Models\EquipmentDescription;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Device_Management_Controller extends Controller
{
    public function showDevice_Management(Request $request)
    {
        
        $filter = $request->query('filter', 'all');
        $search = $request->query('search', '');
        $conditionFilter = $request->query('condition', '');
        $statusFilter = $request->query('status', ''); // For new-deployment status filter
        $technicalSupportFilter = $request->query('technical_support_id', '');

        // Default values
        $serviceRequests = collect(); // Empty collection
        $deployments = collect(); // Empty collection

        // Check the filter type
        if ($filter === 'new-deployment') {
            // Fetch from the Deployments table
            $query = Deployment::query();

            // Apply status filter for New Deployment
            if ($statusFilter && in_array($statusFilter, ['new', 'used'])) {
                $query->where('status', $statusFilter);
            }

            // Apply search filter for Deployments
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('control_number', 'like', "%$search%")
                    ->orWhere('purpose', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->orWhere('components', 'like', "%$search%")
                    ->orWhere('software', 'like', "%$search%")
                    ->orWhere('received_by', 'like', "%$search%");
                });
            }

            $deployments = $query->paginate(10);
        } else {
            // Otherwise, fetch from ServiceRequests
            $query = ServiceRequest::with('equipmentDescriptions', 'technicalSupport');

            if ($filter === 'in-repairs') {
                $query->where('status', 'in-repairs');
            } elseif ($filter === 'repaired') {
                $query->where('status', 'repaired');
            }

            if ($conditionFilter && in_array($conditionFilter, ['working', 'not-working', 'needs-repair'])) {
                $query->where('condition', $conditionFilter);
            }

            if (!empty($technicalSupportFilter)) {
                $query->where('technical_support_id', $technicalSupportFilter);
            }

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('form_no', 'like', "%$search%")
                    ->orWhere('department', 'like', "%$search%")
                    ->orWhereHas('equipmentDescriptions', function ($subQuery) use ($search) {
                        $subQuery->where('remarks', 'like', "%$search%");
                    });
                });
            }

            $serviceRequests = $query->paginate(10);
        }

        // Generate next form number (only for service requests)
        $nextFormNo = null;
        if ($filter !== 'new-deployment') {
            $latestFormNo = ServiceRequest::latest('form_no')->first();
            $nextNumber = $latestFormNo ? str_pad((int)substr($latestFormNo->form_no, 9) + 1, 6, '0', STR_PAD_LEFT) : '000001';
            $nextFormNo = 'SRF-' . date('Y') . '-' . $nextNumber;
        }

        // Fetch all technical support personnel
        $technicalSupports = User::where('account_type', 'technical_support')->get();

        return view('Device_Management', compact('serviceRequests', 'deployments', 'filter', 'nextFormNo', 'conditionFilter', 'technicalSupports', 'technicalSupportFilter', 'statusFilter'));
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
            'service_type' => $serviceRequest->service_type ?? 'N/A', // Default to 'N/A' if service_type is not available
            'name' => $serviceRequest->name,
            'employee_id' => $serviceRequest->employee_id,
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



    public function showDeployment($id)
    {
        $deployment = Deployment::with('equipmentItems')->findOrFail($id);
    
        if (is_string($deployment->components)) {
            $deployment->components = json_decode($deployment->components, true);
        }
        if (is_string($deployment->software)) {
            $deployment->software = json_decode($deployment->software, true);
        }
    
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
            'noted_by' => $deployment->noted_by,
            'received_date' => $deployment->received_date,
            'issued_date' => $deployment->issued_date,
            'noted_date' => $deployment->noted_date,
        ];
    
        return response()->json($responseData);
    }
}    