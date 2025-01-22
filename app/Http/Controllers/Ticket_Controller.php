<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket; // Ensure the Ticket model exists
use App\Models\User;
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
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    
        // Fetch all technical support users
        $technicalSupports = User::where('account_type', 'technical_support')->get();
    
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
        // Fetch technical support users
        $technicalSupports = User::where('account_type', 'technical_support')->get();

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

public function filterTickets($status, Request $request)
{
    // Fetch the 'priority' parameter from the request (default to null for "all priorities")
    $priority = $request->query('priority', null);

    // Start building the query
    $query = Ticket::query();

    // Filter by status
    if ($status === 'recent') {
        $query->orderBy('created_at', 'desc');
    } else {
        $query->where('status', $status);
    }

    // Filter by priority if it's specified
    if ($priority) {
        $query->where('priority', $priority);
    }

    // Paginate the results
    $tickets = $query->paginate(10);

    // Render the ticket list view with the filtered tickets
    $html = view('components.ticket-list', compact('tickets'))->render();

    // Return the filtered ticket list and pagination HTML
    return response()->json([
        'html' => $html,
        'pagination' => (string) $tickets->links('pagination::bootstrap-4') // Return pagination as HTML
    ]);
}
public function show($control_no)
{
    $ticket = Ticket::where('control_no', $control_no)->first();
    
    // Return ticket details as a JSON response
    return response()->json($ticket);
}

}
