<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\ServiceRequest;
use App\Models\EquipmentDescription;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\TechnicalReport; 
use App\Models\Rating;
use App\Models\Approval;
use App\Models\User;
use App\Models\Endorsement; 


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

    public function downloadPdfReport($id)
    {
        // Fetch the technical report data
        $technicalReport = TechnicalReport::findOrFail($id);

        // Fetch the rating based on the control_no
        $rating = Rating::where('control_no', $technicalReport->control_no)->first();
        $approve = Approval::where('ticket_id', $technicalReport->control_no)->first();

        // Pass the data to the Blade template
        $pdf = Pdf::loadView('pdf.technical_report', [
            'technicalReport' => $technicalReport,
            'rating' => $rating ? $rating->rating : 'No Rating', // Use the rating or a default value
            'approve' => $approve,
        ]);

        // Download the PDF
        return $pdf->download('technical_report.pdf');
    }
   
    public function downloadPdfEndorsement($id)
    {
        // Fetch the endorsement data
        $endorsement = Endorsement::findOrFail($id);

        $approve = Approval::where('ticket_id', $endorsement->ticket_id)->first();

        // Format dates for HTML input fields
        $endorsement->endorsed_to_date = $endorsement->endorsed_to_date ? date('Y-m-d', strtotime($endorsement->endorsed_to_date)) : '';
        $endorsement->endorsed_by_date = $endorsement->endorsed_by_date ? date('Y-m-d', strtotime($endorsement->endorsed_by_date)) : '';

        // Handle missing approval data
        $approveData = [
            'name' => $approve ? $approve->name : 'Not Available',
            'approve_date' => $approve ? $approve->approve_date : 'Not Available',
        ];

        // Decode network and network_details
        $network = $this->safeJsonDecode($endorsement->network);
        $network_details = $this->safeJsonDecode($endorsement->network_details);

        // Pass the data to the Blade template
        $pdf = Pdf::loadView('pdf.endorsement', [
            'endorsement' => $endorsement,
            'approve' => $approveData,
            'network' => $network, // Pass the decoded network array
            'network_details' => $network_details, // Pass the decoded network_details array
        ]);

        // Download the PDF
        return $pdf->download('endorsement.pdf');
    }

    /**
     * Helper function to safely decode JSON values
     */
    private function safeJsonDecode($value)
    {
        $decoded = json_decode($value, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : $value; // Return original value if not JSON
    }

    public function downloadPdfService_Request($formNo)
    {
        // Fetch the service request data
        $serviceRequest = ServiceRequest::where('form_no', $formNo)->firstOrFail();

        // Fetch the equipment descriptions for this service request
        $equipmentDescriptions = EquipmentDescription::where('form_no', $formNo)->get();

        // Fetch the rating based on the control_no
        $rating = Rating::where('control_no', $serviceRequest->ticket_id)->first();
        $approve = Approval::where('ticket_id', $serviceRequest->ticket_id)->first();
        $name = User::where('employee_id', $serviceRequest->technical_support_id )->first();

        // Generate the QR code as a base64 string
        $qrCode = QrCode::size(200)->generate(route('generate.pdf', $formNo));
        $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrCode);

        // Pass the data to the Blade template
        $pdf = Pdf::loadView('pdf.service_request', [
            'serviceRequest' => $serviceRequest,
            'equipmentDescriptions' => $equipmentDescriptions,
            'qrCodeBase64' => $qrCodeBase64,
            'rating' => $rating ? $rating->rating : 'No Rating', // Use the rating or a default value
            'approve' => $approve,
            'name' => $name,
        ]);

        // Download the PDF
        return $pdf->download('service_request.pdf');
    }
}    
