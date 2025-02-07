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

    // Function to generate the PDF for new device deployment
    public function generateDeploymentPDF($control_number)
    {
        $deployment = Deployment::where('control_number', $control_number)->firstOrFail();

        // Get related equipment items for the deployment
        $equipmentItems = $deployment->equipmentItems;

        // Prepare the components and software as comma-separated strings
        $components = is_array($deployment->components) ? implode(', ', $deployment->components) : 'No Components';
        $software = is_array($deployment->software) ? implode(', ', $deployment->software) : 'No Software';

        // Pass data to the PDF view
        $pdf = Pdf::loadView('pdf.deployment', [
            'control_number' => $deployment->control_number,
            'purpose' => $deployment->purpose,
            'status' => $deployment->status,
            'components' => $components,
            'software' => $software,
            'brand_name' =>  $deployment->brand_name,
            'specification' =>  $deployment->specification,
            'received_by' => $deployment->received_by,
            'received_date' => $deployment->received_date,
            'issued_by' => $deployment->issued_by,
            'issued_date' => $deployment->issued_date,
            'noted_by' => $deployment->noted_by,
            'noted_date' => $deployment->noted_date,
            'equipmentItems' => $equipmentItems, // Pass the equipment items data
        ]);

        // Return the PDF as a stream or force download
        return $pdf->stream("Deployment_$control_number.pdf");
    }

    
}    
