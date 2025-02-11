<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketHistory;
use App\Models\Endorsement;
use App\Models\Ticket;
use App\Models\User;
use App\Models\ServiceRequest;
use App\Models\TechnicalReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Ticket_Controller extends Controller
{
    public function showTicket()
{
    // Get the current logged-in user
    $user = auth()->user();

   // Ensure the user is a technical support user or an administrator
    if (!in_array($user->account_type, ['technical_support', 'administrator'])) {
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
    
    // Generate next form number
    $latestFormNo = ServiceRequest::latest('form_no')->first();
    $nextNumber = $latestFormNo ? str_pad((int)substr($latestFormNo->form_no, 9) + 1, 6, '0', STR_PAD_LEFT) : '000001';
    $nextFormNo = 'SRF-' . date('Y') . '-' . $nextNumber;

    $technicalAssistSupports = collect(); // Prevent undefined variable
        
    // Fetch active technical supports (those who have a recent activity and a session)
    $threshold = now()->subMinutes(30);  // Set the threshold to 30 minutes ago (can be adjusted)
    $technicalAssistSupports = User::where('last_activity', '>=', $threshold)
        ->whereNotNull('session_id') // Ensure they have a session ID
        ->where('employee_id', '!=', $user->employee_id)  // Exclude the current technical support
        ->get();

    // Pass data to the view
    return view('ticket', compact('tickets', 'technicalAssistSupports','technicalSupports', 'formattedControlNo', 'nextFormNo'));
}


    public function store(Request $request)
    {
        // For debugging purposes, we can uncomment this to inspect the incoming data
        // dd($request->all()); // You can remove this after debugging
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
            // If the status is not "recent", check if there are multiple statuses (comma-separated)
            if ($status) {
                $statusArray = explode(',', $status); // Split the status string by commas
                $query->whereIn('status', $statusArray); // Use whereIn to filter by multiple statuses
            }
        }
        
        // Filter by priority if provided
        if ($priority) {
            $query->where('priority', $priority);
        }
        
        // Paginate the results
        $tickets = $query->paginate(10);
        
        // For each ticket, determine if assist is done and if remarks are done
        foreach ($tickets as $ticket) {
            // Check if assist has been done (if the ticket exists in ticket_histories)
            $ticket->isAssistDone = $ticket->history->where('ticket_id', $ticket->control_no)->count() > 0;

            // Check if the status is one of the completed, endorsed, or technical_report
            $ticket->isRemarksDone = in_array($ticket->status, ['completed', 'endorsed', 'technical-report', 'pull-out']);
        }
        $technicalSupports = User::where('account_type', 'technical_support')->get();

        $technicalAssistSupports = collect(); // Prevent undefined variable
        
        // Fetch active technical supports (those who have a recent activity and a session)
        $threshold = now()->subMinutes(30);  // Set the threshold to 30 minutes ago (can be adjusted)
        $technicalAssistSupports = User::where('last_activity', '>=', $threshold)
            ->whereNotNull('session_id') // Ensure they have a session ID
            ->where('employee_id', '!=', $user->employee_id)  // Exclude the current technical support
            ->get();

     

        // Generate next form number
        $latestFormNo = ServiceRequest::latest('form_no')->first();
        $nextNumber = $latestFormNo ? str_pad((int)substr($latestFormNo->form_no, 9) + 1, 6, '0', STR_PAD_LEFT) : '000001';
        $nextFormNo = 'SRF-' . date('Y') . '-' . $nextNumber;
            
        // Render the ticket list view
        return view('components.ticket-list', compact('tickets', 'technicalSupports','technicalAssistSupports','nextFormNo'))->render();
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

    public function updateRemarks(Request $request)
    {
        // Validate the request inputs
        $request->validate([
            'control_no' => 'required|string|exists:tickets,control_no',
            'remarks' => 'required|string|max:255',
            'status' => 'required|in:completed,endorsed,technical-report,pull-out', // Ensure 'technical-report' is included here
        ]);

        try {
            // Find the ticket by control number
            $ticket = Ticket::where('control_no', $request->control_no)->firstOrFail();
            $ticket->remarks = $request->remarks;
            $ticket->status = $request->status;  // Update the status
            $ticket->save();

             // Handle specific logic if the ticket status is "endorsed"
             if ($ticket->status === 'endorsed') {
                $this->createEndorsement($ticket);  // Your custom logic for endorsements
            }

            // Return a success response
            return response()->json(['message' => 'Ticket updated successfully.'], 200);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Failed to update ticket and endorsement:', ['error' => $e->getMessage()]);
            
            // Return an error message as JSON
            return response()->json(['message' => 'Failed to update ticket.', 'error' => $e->getMessage()], 500);
        }
    } 

    public function createEndorsement($ticket)
    {
        // Get the current year
        $currentYear = now()->year;

        // Find the last endorsement for the current year and get the last incremented number
        $lastEndorsement = Endorsement::whereYear('endorsed_by_date', $currentYear)  // Make sure you use the correct column name for date
            ->orderByDesc('control_no')  // Order by control_no to get the most recent entry
            ->first();

        // Extract the last number from the control_no (e.g., from ITS-2025-000010, extract 000010)
        $lastControlNo = $lastEndorsement ? $lastEndorsement->control_no : null;
        $lastNumber = $lastControlNo ? (int)substr($lastControlNo, -6) : 0;

        // Increment the number by 1
        $newNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);

        // Generate a new control_no
        $newControlNo = 'ITS-' . $currentYear . '-' . $newNumber;

        // Save the endorsement
        $endorsement = new Endorsement();
        $endorsement->ticket_id = $ticket->control_no;  // Referencing ticket's control_no
        $endorsement->department = $ticket->department;  // Assuming department is a field in the ticket
        $endorsement->endorsed_by = $ticket->technical_support_name;  // Assuming this field is present in the ticket
        $endorsement->endorsed_by_remarks = $ticket->remarks;  // Reusing the remarks from the ticket
        $endorsement->control_no = $newControlNo;  // Newly generated control_no for the endorsement
        $endorsement->save(); // Save the endorsement

        // Return response with the new control_no to display in a modal
        return response()->json([
            'message' => 'Endorsement created successfully!',
            'control_no' => $endorsement
        ]);
    } 
    
    public function getEndorsementDetails($ticketId)
    {
        try {
            \Log::info('Fetching endorsement for Ticket ID:', ['ticket_id' => $ticketId]);

            // Check if the endorsement exists
            $endorsement = Endorsement::where('ticket_id', $ticketId)->first();

            if (!$endorsement) {
                \Log::error('No endorsement found for Ticket ID:', ['ticket_id' => $ticketId]);
                return response()->json(['message' => 'Endorsement not found.'], 404);
            }

            return response()->json(['endorsement' => $endorsement], 200);
        } catch (\Exception $e) {
            \Log::error('Error fetching endorsement details:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to fetch endorsement details.'], 500);
        }
    }


    public function endorseStore(Request $request)
    {


        // Validate request
        $validated = $request->validate([
            'ticket_id' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'network' => 'nullable|array',
            'network_details' => 'nullable|array',
            'user_account' => 'nullable|array',
            'user_account_details' => 'nullable|array',
            'endorsed_to' => 'nullable|string|max:255',
            'endorsed_to_date' => 'nullable|date',
            'endorsed_to_time' => 'nullable|date_format:H:i',
            'endorsed_to_remarks' => 'nullable|string',
            'endorsed_by' => 'nullable|string|max:255',
            'endorsed_by_date' => 'nullable|date',
            'endorsed_by_time' => 'nullable|date_format:H:i',
            'endorsed_by_remarks' => 'nullable|string',
        ]);


        // Convert arrays to JSON
        $validated['network'] = json_encode($request->input('network', []));
        $validated['network_details'] = json_encode($request->input('network_details', []));
        $validated['user_account'] = json_encode($request->input('user_account', []));
        $validated['user_account_details'] = json_encode($request->input('user_account_details', []));

        // Check if endorsement already exists
        $endorsement = Endorsement::where('ticket_id', $validated['ticket_id'])->first();

        if ($endorsement) {

            $endorsement->update($validated);
            $message = 'Endorsement updated successfully!';
        } else {

            // Generate control number
            $year = date('Y');
            $lastRecord = Endorsement::whereYear('created_at', $year)
                ->orderBy('control_no', 'desc')
                ->first();

            $lastNumber = $lastRecord ? (int)substr($lastRecord->control_no, -6) : 0;
            $nextNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
            $validated['control_no'] = "ITS-$year-$nextNumber";

            $endorsement = Endorsement::create($validated);
            $message = 'Endorsement saved successfully!';
        }

        return redirect()->back()->with('success', $message);
    }

    public function getTicketDetails($ticketId)
    {
        $endorsement = Endorsement::where('ticket_id', $ticketId)->first();

        if (!$endorsement) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }

        return response()->json([
            'control_no' => $endorsement->control_no,
            'department' => $endorsement->department,
            'endorsed_to' => $endorsement->endorsed_to,
            'endorsed_to_date' => $endorsement->endorsed_to_date,
            'endorsed_to_time' => $endorsement->endorsed_to_time,
            'endorsed_to_remarks' => $endorsement->endorsed_to_remarks,
            'endorsed_by' => $endorsement->endorsed_by,
            'endorsed_by_date' => $endorsement->endorsed_by_date,
            'endorsed_by_time' => $endorsement->endorsed_by_time,
            'endorsed_by_remarks' => $endorsement->endorsed_by_remarks,
            'network' => $this->safeJsonDecode($endorsement->network),
            'network_details' => $this->safeJsonDecode($endorsement->network_details),
            'user_account' => $this->safeJsonDecode($endorsement->user_account),
            'user_account_details' => $this->safeJsonDecode($endorsement->user_account_details),
        ]);
    }

    /**
     * Helper function to safely decode JSON values
     */
    private function safeJsonDecode($value)
    {
        $decoded = json_decode($value, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : [];
    }

    public function checkTechnicalReport($control_no)
    {
        $report = TechnicalReport::where('control_no', $control_no)->first();

        if ($report) {
            return response()->json([
                'exists' => true,
                'report' => $report
            ]);
        } else {
            return response()->json([
                'exists' => false
            ]);
        }
    }

    public function getTechnicalReportDetails($control_no)
    {
        $ticket = Ticket::where('control_no', $control_no)->first();

        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }

        return response()->json([
            'name' => $ticket->name,
            'employee_id' => $ticket->employee_id,
            'department' => $ticket->department,
            'enduser' => $ticket->name, // Assuming 'name' is the end user field
            'success' => true // To check if it's found
        ]);
    }
    
    public function storeTechnicalReport(Request $request)
    {
        $request->validate([
            'control_no' => 'required|string|exists:tickets,control_no', // ✅ Validate control_no
            'date_time' => 'required|date',
            'department' => 'required|string|max:255',
            'enduser' => 'required|string|max:255',
            'specification' => 'required|string',
            'problem' => 'required|string',
            'workdone' => 'required|string',
            'findings' => 'required|string',
            'recommendation' => 'required|string',
            'reported_by' => 'required|string|max:255',
            'reported_date' => 'nullable|date',
            'inspected_by' => 'required|string|max:255',
            'inspected_date' => 'nullable|date',
            'noted_by' => 'required|string|max:255',
            'noted_date' => 'nullable|date',
        ]);

        TechnicalReport::create([
            'control_no' => $request->control_no, // ✅ Ensure control_no is included
            'date_time' => $request->date_time,
            'department' => $request->department,
            'enduser' => $request->enduser,
            'specification' => $request->specification,
            'problem' => $request->problem,
            'workdone' => $request->workdone,
            'findings' => $request->findings,
            'recommendation' => $request->recommendation,
            'reported_by' => $request->reported_by,
            'reported_date' => $request->reported_date,
            'inspected_by' => $request->inspected_by,
            'inspected_date' => $request->inspected_date,
            'noted_by' => $request->noted_by,
            'noted_date' => $request->noted_date,
        ]);

        return redirect()->back()->with('success', 'Technical Report saved successfully.');
    }

}
