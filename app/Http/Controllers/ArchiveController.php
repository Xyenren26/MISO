<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketArchive;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ArchiveController extends Controller
{
    // Display archived tickets
    public function index(Request $request)
    {
        $query = TicketArchive::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('control_no', 'like', "%$search%")
                  ->orWhere('name', 'like', "%$search%")
                  ->orWhere('department', 'like', "%$search%");
        }

        $archivedTickets = $query->paginate(10)->onEachSide(1);

        return view('archive', compact('archivedTickets'));
    }
    
    public function export(Request $request)
    {
        $ticketIds = explode(',', $request->query('tickets'));
        $tickets = TicketArchive::whereIn('control_no', $ticketIds)->get();
    
        $csvFileName = 'archived_tickets_' . now()->format('Ymd_His') . '.csv';
    
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
        ];
    
        $callback = function () use ($tickets) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Control No', 'Employee ID', 'Name', 'Department', 'Priority', 
                'Concern', 'Remarks', 'Technical Support ID', 'Technical Support Name', 
                'Status', 'Time In', 'Time Out', 'Archived At'
            ]);
    
            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->control_no, $ticket->employee_id, $ticket->name, 
                    $ticket->department, $ticket->priority, $ticket->concern, 
                    $ticket->remarks, $ticket->technical_support_id, 
                    $ticket->technical_support_name, $ticket->status, 
                    $ticket->time_in, $ticket->time_out, $ticket->archived_at
                ]);
            }
    
            fclose($file);
        };
    
        // Delete the exported records **after** sending the response
        $response = response()->stream($callback, 200, $headers);
    
        TicketArchive::whereIn('control_no', $ticketIds)->delete();
    
        return $response;
    }    
}
