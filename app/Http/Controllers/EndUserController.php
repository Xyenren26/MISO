<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EndUserController extends Controller
{
    public function index(){
        // Fetch all technical support users excluding the current one
        $technicalSupports = User::where('account_type', 'technical_support')
        ->whereNotNull('session_id')  // Check if 'time_in' is not null
        ->get();

        // Get the current year for control number formatting
        $currentYear = now()->year;
        $lastTicket = Ticket::whereYear('created_at', $currentYear)
            ->orderBy('control_no', 'desc')
            ->first();

        // Calculate the next control number
        $nextControlNo = $lastTicket ? (intval(substr($lastTicket->control_no, -4)) + 1) : 1;
        $formattedControlNo = 'TS-' . $currentYear . '-' . str_pad($nextControlNo, 4, '0', STR_PAD_LEFT);
        return view ('employee.home', compact('technicalSupports', 'formattedControlNo'));
    }
        
    
    public function showEmployeeTicket()
{
    $user = auth()->user();

    // Fetch all tickets (without pagination)
    $status = request('filter');
    $tickets = Ticket::where('employee_id', $user->employee_id)
        ->when($status, function ($query, $status) {
            return $query->where('status', $status);
        })
        ->with('history') // Include histories
        ->orderBy('created_at', 'desc')
        ->paginate(6); // Changed from paginate() to get()

    // Fetch other data
    $technicalSupports = User::where('account_type', 'technical_support')
        ->whereNotNull('session_id')
        ->get();

    $currentYear = now()->year;
    $lastTicket = Ticket::whereYear('created_at', $currentYear)
        ->orderBy('control_no', 'desc')
        ->first();

    $nextControlNo = $lastTicket ? (intval(substr($lastTicket->control_no, -4)) + 1) : 1;
    $formattedControlNo = 'TS-' . $currentYear . '-' . str_pad($nextControlNo, 4, '0', STR_PAD_LEFT);

    // Generate next form number
    $latestFormNo = ServiceRequest::latest('form_no')->first();
    $nextNumber = $latestFormNo ? str_pad((int)substr($latestFormNo->form_no, 9) + 1, 6, '0', STR_PAD_LEFT) : '000001';
    $nextFormNo = 'SRF-' . date('Y') . '-' . $nextNumber;

    $threshold = now()->subMinutes(30);
    $technicalAssistSupports = User::where('last_activity', '>=', $threshold)
        ->where('account_type', 'technical_support') // Correct comparison here
        ->whereNotNull('session_id')
        ->where('employee_id', '!=', $user->employee_id)
        ->get();

    return view('employee.ticket', compact('tickets', 'technicalAssistSupports', 'technicalSupports', 'formattedControlNo', 'nextFormNo'));
}

public function filterEmployeeTickets(Request $request)
{
    $status = $request->get('status');
    $priority = $request->get('priority');
    $search = $request->get('search'); // Get search input

    // Get the currently logged-in user
    $currentEmployeeId = Auth::user()->employee_id;
    $user = Auth::user(); 

    // Start building the query for tickets
    $query = Ticket::query();

    // Filter tickets assigned to the logged-in employee
    $query->where('employee_id', $currentEmployeeId);

    // Add search filter for control_no and name
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('control_no', 'LIKE', "%{$search}%")
            ->orWhere('name', 'LIKE', "%{$search}%");
        });
    }

    // Handle the "recent" filter to show all latest records
    if ($status === 'recent') {
        $query->orderBy('created_at', 'desc');
    } else {
        // If the status is not "recent", check if there are multiple statuses (comma-separated)
        if (!empty($status)) {
            $statusArray = explode(',', $status);
            $query->whereIn('status', $statusArray);
        }
    }

    // Filter by priority if provided
    if (!empty($priority)) {
        $query->where('priority', $priority);
    }

    // Paginate the results
    $tickets = $query->paginate(6);

    // Process each ticket to determine assist and remarks status
    foreach ($tickets as $ticket) {
        $ticket->isAssistDone = $ticket->history->where('ticket_id', $ticket->control_no)->count() > 0;
        $ticket->isRemarksDone = in_array($ticket->status, ['completed', 'endorsed', 'technical-report', 'pull-out']);
    }

    // Fetch technical supports
    $technicalSupports = User::where('account_type', 'technical_support')->get();

    // Fetch active technical supports (last activity within 30 minutes)
    $threshold = now()->subMinutes(30);
    $technicalAssistSupports = User::where('last_activity', '>=', $threshold)
        ->where('account_type', 'technical_support') // Correct comparison here
        ->whereNotNull('session_id')
        ->where('employee_id', '!=', $user->employee_id)
        ->get();

    // Generate next form number
    $latestFormNo = ServiceRequest::latest('form_no')->first();
    $nextNumber = $latestFormNo ? str_pad((int)substr($latestFormNo->form_no, 9) + 1, 6, '0', STR_PAD_LEFT) : '000001';
    $nextFormNo = 'SRF-' . date('Y') . '-' . $nextNumber;

    return view('components.employee.ticket-list', compact('tickets', 'technicalSupports', 'technicalAssistSupports', 'nextFormNo'))->render();
}


}
