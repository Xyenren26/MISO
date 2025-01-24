<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Ticket_Controller extends Controller
{
    public function showTicket()
{
    // Get the current logged-in user
    $user = auth()->user();

    // Ensure the user is a technical support user
    if ($user->account_type !== 'technical_support') {
        abort(403, 'Unauthorized access');
    }

    // Fetch tickets assigned to the logged-in technical support user
    $status = request('filter');
    $tickets = Ticket::where('technical_support_id', $user->employee_id)
        ->when($status, function ($query, $status) {
            return $query->where('status', $status);
        })
        ->with('history') // Include histories in the query
        ->orderBy('created_at', 'desc')
        ->paginate(20);

    // Fetch all technical support users excluding the current one
    $technicalSupports = User::where('account_type', 'technical_support')
        ->get();

    // Get the current year for control number formatting
    $currentYear = now()->year;
    $lastTicket = Ticket::whereYear('created_at', $currentYear)
        ->orderBy('control_no', 'desc')
        ->first();

    // Calculate the next control number
    $nextControlNo = $lastTicket ? (intval(substr($lastTicket->control_no, -4)) + 1) : 1;
    $formattedControlNo = 'TS-' . $currentYear . '-' . str_pad($nextControlNo, 4, '0', STR_PAD_LEFT);

    // Pass data to the view
    return view('ticket', compact('tickets', 'technicalSupports', 'formattedControlNo'));
}


    public function store(Request $request)
    {
        // For debugging purposes, we can uncomment this to inspect the incoming data
        // dd($request->all()); // You can remove this after debugging

        if ($request->isMethod('get')) {
            // Fetch technical support users excluding the current logged-in user
            $user = auth()->user();
            $technicalSupports = User::where('account_type', 'technical_support')
                ->where('employee_id', '!=', $user->employee_id)  // Exclude current technical support
                ->get();

            $currentYear = now()->year;

            $lastTicket = Ticket::whereYear('created_at', $currentYear)
                ->orderBy('control_no', 'desc')
                ->first();

            $nextControlNo = $lastTicket ? (intval(substr($lastTicket->control_no, -4)) + 1) : 1;

            $formattedControlNo = 'TS-' . $currentYear . '-' . str_pad($nextControlNo, 4, '0', STR_PAD_LEFT);

            return view('components.ticket-form', compact('technicalSupports', 'formattedControlNo'));
        }

        if ($request->isMethod('post')) {
            // Validate the request data
            $request->validate([
                'first-name' => 'required|string|max:255',
                'last-name' => 'required|string|max:255',
                'department' => 'required|string',
                'category' => 'required|string',
                'employeeId' => 'required|string|max:50',
                'technicalSupport' => 'required|string',
                'issues' => 'nullable|array',  // Make sure 'issues' is an array
                'issues.*' => 'nullable|string', // Ensure each issue is a string
                'otherConcern' => 'nullable|string', // Handle 'Other' concern if it's provided
            ]);

            // Fetch the technical support user's full name
            $technicalSupport = User::where('employee_id', $request->input('technicalSupport'))->first();
            $technicalSupportName = $technicalSupport ? $technicalSupport->first_name . ' ' . $technicalSupport->last_name : null;

            if (!$technicalSupportName) {
                return redirect()->back()->withErrors(['technicalSupport' => 'Invalid technical support user selected.']);
            }

            // Prepare the concerns: Handle 'issues' array and 'otherConcern' field
            $concerns = $request->input('issues', []);
            
            // If 'otherConcern' is provided, append it to the concerns array
            if ($request->input('concern') === 'other' && $request->input('otherConcern')) {
                $concerns[] = $request->input('otherConcern');
            }

            // Concatenate all concerns into a single string (comma-separated)
            $concernString = implode(', ', $concerns);

            // Create and save the ticket
            $ticket = new Ticket();
            $ticket->control_no = $request->input('controlNumber');
            $ticket->employee_id = $request->input('employeeId');
            $ticket->name = $request->input('first-name') . ' ' . $request->input('last-name');
            $ticket->department = $request->input('department');
            $ticket->priority = $request->input('category');
            $ticket->concern = $concernString;  // Store the concerns as a single string
            $ticket->technical_support_id = $request->input('technicalSupport');
            $ticket->technical_support_name = $technicalSupportName;
            $ticket->status = 'in-progress';

            // No need to manually set 'created_at', Laravel handles it automatically

            $ticket->save();

            return redirect()->route('ticket')->with('success', 'Ticket created successfully!');
        }
    }

    public function passTicket(Request $request)
    {
    $ticketControlNo = $request->input('ticket_control_no');
    $newTechnicalSupport = $request->input('new_technical_support'); // Ensure this value is correctly retrieved

    $ticket = Ticket::where('control_no', $ticketControlNo)->first();

    if ($ticket) {
        $ticket->history()->create([
            'previous_technical_support' => $ticket->technical_support_id,
            'new_technical_support' => $newTechnicalSupport, // Check if this value is being passed correctly
            'ticket_id' => $ticket->id, // Ensure you’re passing the correct ticket ID
        ]);

        $ticket->technical_support_id = $newTechnicalSupport;
        $ticket->save();
    }

    return redirect()->route('ticket')->with('success', 'Pass Ticket created successfully!');
    }

    public function filterTickets(Request $request)
{
    $status = $request->get('status');
    $priority = $request->get('priority');

    // Get the currently logged-in user
    $currentTechnicalSupportId = Auth::user()->employee_id;
    $user = Auth::user();  // Define the user for filtering active technical supports

    // Start building the query for tickets
    $query = Ticket::query();

    // Filter tickets assigned to the logged-in technical support user
    $query->where('technical_support_id', $currentTechnicalSupportId);

    // Handle the "recent" filter to show all latest records
    if ($status === 'recent') {
        $query->orderBy('created_at', 'desc'); // Sort by creation date (latest first)
    } else {
        // Filter by specific status if not "recent"
        if ($status) {
            $query->where('status', $status);
        }
    }

    // Filter by priority if provided
    if ($priority) {
        $query->where('priority', $priority);
    }

    // Paginate the results
    $tickets = $query->paginate(10);

    // Fetch active technical supports (those who have a recent activity and a session)
    $threshold = now()->subMinutes(30);  // Set the threshold to 30 minutes ago (can be adjusted)
    $technicalSupports = User::where('last_activity', '>=', $threshold)
        ->whereNotNull('session_id') // Ensure they have a session ID
        ->where('employee_id', '!=', $user->employee_id)  // Exclude the current technical support
        ->get();

    // Render the ticket list view
    return view('components.ticket-list', compact('tickets', 'technicalSupports'))->render();
}

public function show($id)
{
    $ticket = Ticket::findOrFail($id);

    // Fetch technical support name based on technical_support_id
    $technicalSupport = User::where('employee_id', $ticket->technical_support_id)
        ->selectRaw("CONCAT(first_name, ' ', last_name) AS technical_support_name")
        ->first();

    $ticket->technical_support_name = $technicalSupport->technical_support_name ?? 'N/A';

    // Fetch the first history record for this ticket (if it exists)
    $ticketHistory = $ticket->history()->orderBy('changed_at', 'asc')->first();

    // Fetch full names for previous and new technical support
    if ($ticketHistory) {
        $previousTechnicalSupport = User::where('employee_id', $ticketHistory->previous_technical_support)
            ->selectRaw("CONCAT(first_name, ' ', last_name) AS name")
            ->first();

        $newTechnicalSupport = User::where('employee_id', $ticketHistory->new_technical_support)
            ->selectRaw("CONCAT(first_name, ' ', last_name) AS name")
            ->first();

        // Add the names to the ticket history
        $ticketHistory->previous_technical_support_name = $previousTechnicalSupport ? $previousTechnicalSupport->name : 'N/A';
        $ticketHistory->new_technical_support_name = $newTechnicalSupport ? $newTechnicalSupport->name : 'N/A';
    }

    // Pass ticket data along with the first technical support history record (if available)
    return response()->json([
        'ticket' => $ticket,
        'ticketHistory' => $ticketHistory
    ]);
}    

}

