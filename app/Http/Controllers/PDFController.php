<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\EquipmentDescription;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends Controller
{
    public function generatePDF($form_no)
    {
        $serviceRequest = ServiceRequest::with(['equipmentDescriptions', 'technicalSupport'])
            ->where('form_no', $form_no)
            ->firstOrFail();

        if (!$serviceRequest) {
            abort(404, 'Service request not found.');
        }

        // Prepare technical support name
        $technicalSupportName = $serviceRequest->technicalSupport 
            ? $serviceRequest->technicalSupport->first_name . ' ' . $serviceRequest->technicalSupport->last_name
            : 'Unassigned';

        // Count occurrences of each serial_no in the database
        $serialNoCounts = EquipmentDescription::select('serial_no')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('serial_no')
            ->having('count', '>=', 1)
            ->pluck('count', 'serial_no');

        // Transform equipment descriptions
        $equipmentDescriptions = $serviceRequest->equipmentDescriptions->map(function ($equipment) use ($serialNoCounts) {
            $serialNo = $equipment->serial_no;
            $warningMessage = null;

            // Check if the serial_no appears more than 5 times
            if ($serialNo && isset($serialNoCounts[$serialNo])) {
                $warningMessage = "This device with serial number $serialNo has been requested for ticket support more than 5 times. This may indicate a recurring issue that requires further investigation.";
            }

            return [
                'brand' => $equipment->brand,
                'device' => $equipment->device,
                'description' => $equipment->description,
                'serial_no' => $serialNo,
                'remarks' => $equipment->remarks,
                'warning_message' => $warningMessage, // Add warning message if applicable
            ];
        });

        // Pass data to the PDF view
        $pdf = Pdf::loadView('pdf.form', [
            'form_no' => $serviceRequest->form_no,
            'name' => $serviceRequest->name,
            'employee_id' => $serviceRequest->employee_id,
            'department' => $serviceRequest->department,
            'condition' => $serviceRequest->condition ?? 'N/A',
            'technical_support' => $technicalSupportName,
            'equipment_descriptions' => $equipmentDescriptions,
        ]);

        return $pdf->stream("ServiceRequest_$form_no.pdf");
    }
}    
