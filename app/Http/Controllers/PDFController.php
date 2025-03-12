<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\Deployment;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends Controller
{
    public function generatePDF($form_no)
    {
        $serviceRequest = ServiceRequest::with(['equipmentDescriptions.equipmentParts', 'technicalSupport'])
            ->where('form_no', $form_no)
            ->firstOrFail();
    
        if (!$serviceRequest) {
            abort(404, 'Service request not found.');
        }
    
        // Prepare technical support name
        $technicalSupportName = $serviceRequest->technicalSupport 
            ? $serviceRequest->technicalSupport->first_name . ' ' . $serviceRequest->technicalSupport->last_name
            : 'Unassigned';
    
        // Transform equipment descriptions
        $equipmentDescriptions = $serviceRequest->equipmentDescriptions->map(function ($equipment) {
            // Get only parts where 'is_present' is true
            $parts = $equipment->equipmentParts->where('is_present', true)->pluck('part_name')->toArray();
        
            return [
                'brand' => $equipment->brand,
                'equipment_type' => $equipment->equipment_type,
                'remarks' => $equipment->remarks,
                'equipment_parts' => $parts, // This should now contain only present parts
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
