<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Collection;
use App\Models\Approval;
use App\Models\User;
use App\Models\ServiceRequest;
use App\Models\Deployment;
use App\Models\EquipmentDescription;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\URL;

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
            $query = Deployment::query()->orderBy('created_at', 'desc');

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

            $deployments = $query->paginate(3);
        } else {
            // Otherwise, fetch from ServiceRequests
            $query = ServiceRequest::with('equipmentDescriptions', 'technicalSupport')
            ->orderBy('created_at', 'desc');

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

            $serviceRequests = $query->paginate(3);
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

        $inRepairsCount = ServiceRequest::where('status', 'in-repairs')->count();

        return view('Device_Management', compact('serviceRequests', 'deployments', 'filter', 'nextFormNo', 'conditionFilter', 'technicalSupports', 'technicalSupportFilter', 'statusFilter', 'inRepairsCount'));
    }

    public function getFilteredRecords(Request $request)
    {
        $filter = $request->get('filter');
        $search = $request->get('search');
    
        if ($filter === 'new-deployment') {
            $query = Deployment::query();
    
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('control_number', 'LIKE', "%{$search}%")
                      ->orWhere('received_by', 'LIKE', "%{$search}%");
                });
            }
    
            $records = $query->get();
    
            foreach ($records as $record) {
                $qrCodeSvg = QrCode::size(100)->generate(route('generate.deployment.pdf', ['control_number' => $record->control_number]));
                $record->qr_code = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);
            }
        } else {
            $query = ServiceRequest::query();
    
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('form_no', 'LIKE', "%{$search}%")
                      ->orWhere('name', 'LIKE', "%{$search}%");
                });
            }
    
            $records = $query->get();
    
            foreach ($records as $record) {
                if ($record->status === 'repaired') {
                    $qrCodeSvg = QrCode::size(100)->generate(route('generate.pdf', ['form_no' => $record->form_no]));
                    $record->qr_code = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);
                } else {
                    $record->qr_code = null;
                }
            }
        }
    
        return response()->json($records);
    }
    
        
}    