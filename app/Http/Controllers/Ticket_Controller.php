<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketHistory;
use App\Models\Approval;
use App\Models\Ticket;
use App\Models\Rating;
use App\Models\User;
use App\Models\ServiceRequest;
use App\Models\Endorsement;
use App\Models\Deployment;
use App\Models\TechnicalReport;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SystemNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\NewNotification;
use Chatify\Facades\ChatifyMessenger as Chatify; 
use App\Models\ChMessage;
use App\Mail\TicketRemarkUpdate;
use Illuminate\Support\Facades\Mail;


class Ticket_Controller extends Controller
{
    public function showTicket()
    {
        $user = auth()->user();

        if (!in_array($user->account_type, ['technical_support', 'administrator'])) {
            abort(403, 'Unauthorized access');
        }

        // Fetch all tickets based on account type
        $status = request('filter');
        $filterNotApproved = request('filter_not_approved'); // Check if filtering for non-approved tickets

        $tickets = Ticket::when($user->account_type === 'administrator', function ($query) {
            return $query;
        }, function ($query) use ($user) {
            return $query->where('technical_support_id', $user->employee_id);
        })
        ->when($status, function ($query, $status) {
            return $query->where('status', $status);
        })
        ->with(['history', 'serviceRequest', 'deployment', 'ratings']) // Include ServiceRequest relationship
        ->orderBy('created_at', 'desc')
        ->paginate(5)
        ->onEachSide(1);
   

        // Fetch all active technical supports
        $technicalSupports = User::where('account_type', 'technical_support')
            ->whereNotNull('session_id')
            ->where('is_assigned', false) // Get only unassigned techs
            ->get();
        
        // If all are assigned, reset all techs to be available again
        if ($technicalSupports->isEmpty()) {
            User::where('account_type', 'technical_support')->update(['is_assigned' => false]);
            $technicalSupports = User::where('account_type', 'technical_support')
                ->whereNotNull('session_id')
                ->get();
        }
    
        $currentYear = now()->year;
        $lastTicket = Ticket::whereYear('created_at', $currentYear)
            ->orderBy('control_no', 'desc')
            ->first();

        // Generate next control number
        $nextControlNo = $lastTicket ? (intval(substr($lastTicket->control_no, -4)) + 1) : 1;
        $formattedControlNo = 'TS-' . $currentYear . '-' . str_pad($nextControlNo, 4, '0', STR_PAD_LEFT);

        // Generate next form number
        $latestFormNo = ServiceRequest::latest('form_no')->first();
        $nextNumber = $latestFormNo ? str_pad((int)substr($latestFormNo->form_no, 9) + 1, 6, '0', STR_PAD_LEFT) : '000001';
        $nextFormNo = 'SRF-' . date('Y') . '-' . $nextNumber;

        // Fetch online technical supports (last activity within 30 minutes)
        $threshold = now()->subMinutes(30);
        $technicalAssistSupports = User::where('last_activity', '>=', $threshold)
            ->where('account_type', 'technical_support')
            ->whereNotNull('session_id')
            ->where('employee_id', '!=', $user->employee_id)
            ->get();

            $inProgressCount = Ticket::where('status', 'in-progress')
            ->where('technical_support_id', Auth::user()->employee_id) // Only count tickets assigned to the logged-in user
            ->count();

            $endorsedCount = Ticket::where('status', 'endorsed')
                ->where('technical_support_id', Auth::user()->employee_id)
                ->whereDoesntHave('approval', function ($query) {
                    $query->whereColumn('approvals.ticket_id', 'tickets.control_no');
                })
                ->count();

            $technicalReportCount = Ticket::where('status', 'technical-report')
                ->where('technical_support_id', Auth::user()->employee_id)
                ->whereDoesntHave('approval', function ($query) {
                    $query->whereColumn('approvals.ticket_id', 'tickets.control_no');
                })
                ->count();

            $pullOutCount = Ticket::where('status', 'pull-out')
                ->where('technical_support_id', Auth::user()->employee_id)
                ->whereDoesntHave('approval', function ($query) {
                    $query->whereColumn('approvals.ticket_id', 'tickets.control_no');
                })
                ->count();

                $deploymentCount = Ticket::where('status', 'deployment')
                ->where('technical_support_id', Auth::user()->employee_id)
                ->whereDoesntHave('approval', function ($query) {
                    $query->whereColumn('approvals.ticket_id', 'tickets.control_no');
                })
                ->count();

        // Add isApproved and existsInModels check for each ticket
        foreach ($tickets as $ticket) {
            $ticket->isApproved = Approval::where('ticket_id', $ticket->control_no)->exists();

            $ticket->existsInModels = Deployment::where('control_number', $ticket->control_no)
                                    ->whereNotNull('issued_by')
                                    ->exists() ||
                          Endorsement::where('ticket_id', $ticket->control_no)
                                    ->whereNotNull('endorsed_to')
                                    ->exists() ||
                          ServiceRequest::where('ticket_id', $ticket->control_no)
                                    ->whereNotNull('employee_id')
                                    ->exists() ||
                          TechnicalReport::where('control_no', $ticket->control_no)
                                    ->whereNotNull('reported_by')
                                    ->exists();
            $ticket->formfillup = $ticket->status !== 'completed' && (
                                        Deployment::where('control_number', $ticket->control_no)->exists() ||
                                        Endorsement::where('ticket_id', $ticket->control_no)->exists() ||
                                        ServiceRequest::where('ticket_id', $ticket->control_no)->exists() ||
                                        TechnicalReport::where('control_no', $ticket->control_no)->exists());    
            $ticket->isRated = Rating::where('control_no', $ticket->control_no)->exists();
            $ticket->isRepaired = ServiceRequest::where('ticket_id', $ticket->control_no)
                                    ->where('status', 'repaired')
                                    ->exists();

        }

        // Filter tickets that are not approved if requested
        if ($user->account_type === 'administrator'&& $filterNotApproved) {
            $tickets = $tickets->filter(fn($ticket) => !$ticket->isApproved);
        }

        return view('ticket', compact(
            'tickets', 'technicalAssistSupports', 'technicalSupports', 
            'formattedControlNo', 'nextFormNo', 'inProgressCount', 'endorsedCount' ,
            'technicalReportCount','pullOutCount', 'deploymentCount'
        ));
    }

    public function filterTickets(Request $request)
    {
        $status = $request->get('status');
        $priority = $request->get('priority');
        $search = $request->get('search'); // Get search input
        $filterNotApproved = $request->get('filter_not_approved');
        $startDate = $request->get('fromDate');
        $endDate = $request->get('toDate');

        // Get the currently logged-in user
        $user = Auth::user();

        // Start building the query for tickets
        $query = Ticket::query();

        // Filter by technical support ID if the user is not an admin
        if ($user->account_type === 'technical_support') {
            $query->where('technical_support_id', $user->employee_id);
        }

        // Add search filter for control_no and name
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('control_no', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($startDate) && !empty($endDate)) {
            $query->whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate);
        }

        // Handle "recent" filter to show latest records
        if ($status === 'recent') {
            $query->orderBy('created_at', 'desc');
        } else {
            // Handle multiple statuses (comma-separated)
            if (!empty($status)) {
                $statusArray = explode(',', $status);
                $query->whereIn('status', $statusArray);
            }
        }

        // Filter by priority if provided
        if (!empty($priority)) {
            $query->where('priority', $priority);
        }

        // Fetch tickets with pagination
        $tickets = $query->with('history', 'serviceRequest', 'ratings')->paginate(5)->onEachSide(1);

        // Process each ticket for additional attributes
        foreach ($tickets as $ticket) {
            $ticket->isAssistDone = $ticket->history->where('ticket_id', $ticket->control_no)->count() > 0;
            $ticket->isRemarksDone = in_array($ticket->status, ['completed', 'endorsed', 'technical-report', 'pull-out','deployment']);
            $ticket->isApproved = Approval::where('ticket_id', $ticket->control_no)->exists();

            $ticket->existsInModels = Deployment::where('control_number', $ticket->control_no)
                                    ->whereNotNull('issued_by')
                                    ->exists() ||
                          Endorsement::where('ticket_id', $ticket->control_no)
                                    ->whereNotNull('endorsed_to')
                                    ->exists() ||
                          ServiceRequest::where('ticket_id', $ticket->control_no)
                                    ->whereNotNull('employee_id')
                                    ->exists() ||
                          TechnicalReport::where('control_no', $ticket->control_no)
                                    ->whereNotNull('reported_by')
                                    ->exists();
            $ticket->formfillup = $ticket->status !== 'completed' && (
                                        Deployment::where('control_number', $ticket->control_no)->exists() ||
                                        Endorsement::where('ticket_id', $ticket->control_no)->exists() ||
                                        ServiceRequest::where('ticket_id', $ticket->control_no)->exists() ||
                                        TechnicalReport::where('control_no', $ticket->control_no)->exists());
            $ticket->isRated = Rating::where('control_no', $ticket->control_no)->exists();
            $ticket->isRepaired = ServiceRequest::where('ticket_id', $ticket->control_no)
                                    ->where('status', 'repaired')
                                    ->exists();
        }

        // Filter tickets that are not approved if requested (Only for Admin)
        if ($user->account_type === 'administrator' && $filterNotApproved) {
            $tickets = $tickets->filter(fn($ticket) => !$ticket->isApproved);
        }

        // Fetch all technical supports
        $technicalSupports = User::where('account_type', 'technical_support')->get();

        // Fetch active technical supports (last activity within 30 minutes)
        $threshold = now()->subMinutes(30);
        $technicalAssistSupports = User::where('last_activity', '>=', $threshold)
                ->where('account_type', 'technical_support')
                ->whereNotNull('session_id')
                ->where('employee_id', '!=', $user->employee_id)
                ->get();

        // Generate next form number
        $latestFormNo = ServiceRequest::latest('form_no')->first();
        $nextNumber = $latestFormNo ? str_pad((int)substr($latestFormNo->form_no, 9) + 1, 6, '0', STR_PAD_LEFT) : '000001';
        $nextFormNo = 'SRF-' . date('Y') . '-' . $nextNumber;

        // Render the ticket list view
        return view('components.ticket-list', compact('tickets', 'technicalSupports', 'technicalAssistSupports', 'nextFormNo'))->render();
    }

    // Render the ticket view
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
        $user = User::where('employee_id', $ticket->employee_id)->first();

        // Pass ticket data along with the first technical support history record (if available)
        return response()->json([
            'ticket' => $ticket,
            'ticketHistory' => $ticketHistory,
            'user' => $user ? ['phone_number' => $user->phone_number] : null, // Return only phone number or null
        ]);
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
                'concern' => 'required|string', // Now required since we add it dynamically
            ]);

             // Fetch the technical support user's full name
             $technicalSupport = User::where('employee_id', $request->input('technicalSupport'))->first();
             $technicalSupportName = $technicalSupport ? $technicalSupport->first_name . ' ' . $technicalSupport->last_name : null;
 
             if (!$technicalSupportName) {
                 return redirect()->back()->withErrors(['technicalSupport' => 'Invalid technical support user selected.']);
             }
        
            // Save ticket
            $ticket = new Ticket();
            $ticket->control_no = $request->input('controlNumber');
            $ticket->employee_id = $request->input('employeeId');
            $ticket->name = $request->input('first-name') . ' ' . $request->input('last-name');
            $ticket->department = $request->input('department');
            $ticket->priority = $request->input('category');
            $ticket->concern = $request->input('concern');  // Now correctly received
            $ticket->technical_support_id = $request->input('technicalSupport');
            $ticket->technical_support_name = $technicalSupportName;
            $ticket->status = 'in-progress';

            $ticket->save();

            // Mark the technical support as assigned
            if ($request->has('technicalSupport')) {
                User::where('employee_id', $request->input('technicalSupport'))->update(['is_assigned' => true]);
            }

            // Retrieve the employee based on employee_id in the tickets table
            $ticketOwner = User::where('employee_id', $ticket->employee_id)->first();
            
            if ($ticketOwner) {
                $notification = new SystemNotification(
                    'TicketUpdated',
                    'A new update has been made to your ticket.',
                    route('employee.tickets', $ticket->control_no)
                );
            
                $ticketOwner->notify($notification);
            }

            if ($technicalSupport) {
                $notification = new SystemNotification(
                    'TicketAssigned',
                    'You have been assigned a new ticket.',
                    route('ticket', $ticket->control_no)
                );
            
                $technicalSupport->notify($notification);
            }
            $notification = $request->input('controlNumber');
            event(new NewNotification($notification));
            
            
            // Redirect depending on user account type
            if (Auth::user()->account_type === 'end_user') {
                // If the user is an end_user, redirect to employee's ticket page
                return redirect()->route('employee.tickets')->with('success', 'Ticket created successfully!');
            }

            // If not an end_user, redirect to the general ticket page
            return redirect()->route('ticket')->with('success', 'Ticket created successfully!');
        }
    }

    public function passTicket(Request $request)
    {
        $ticketControlNo = $request->input('ticket_control_no');
        $newTechnicalSupport = $request->input('new_technical_support');

        $ticket = Ticket::where('control_no', $ticketControlNo)->first();

        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found.'], 404);
        }

        // Check if the ticket has already been passed before
        $alreadyPassed = $ticket->history()->exists();

        if ($alreadyPassed) {
            return response()->json(['error' => 'This ticket has already been passed once and cannot be reassigned.'], 400);
        }

        // Proceed with creating history and updating the ticket
        $ticket->history()->create([
            'previous_technical_support' => $ticket->technical_support_id,
            'new_technical_support' => $newTechnicalSupport,
            'ticket_id' => $ticket->id,
        ]);

        $ticket->technical_support_id = $newTechnicalSupport;
        $ticket->save();

        return response()->json(['success' => 'Pass Ticket created successfully!'], 200);
    }
    public function updateRemarks(Request $request)
    {
        // Validate the request inputs
        $request->validate([
            'control_no' => 'required|string|exists:tickets,control_no',
            'remarks' => 'required|string|max:255',
            'status' => 'required|in:completed,endorsed,technical-report,pull-out,deployment',
        ]);

        try {
            // Find the ticket by control number
            $ticket = Ticket::where('control_no', $request->control_no)->firstOrFail();
            $ticket->remarks = $request->remarks;
            $ticket->status = $request->status;  // Update the status
            $ticket->save();

            // Retrieve the end user ID (assuming ticket has a `user_id` field)
            $EndUserID = $ticket->employee_id;

            $statusFormatted = ucwords(str_replace('-', ' ', $ticket->status));

            $message = "Hello, {$ticket->name} your ticket with Ticket No:{$ticket->control_no} has been 
                        updated to Status: {$statusFormatted}, with Remarks: {$ticket->remarks}
                        by {$ticket->technical_support_name} on {$ticket->updated_at}
                        
                        If you have any question related to your Ticket Service Request Please fill free to Inquire.
                        
                        Thank you by TechTrack Team.";


            // Create a new message to notify the end user
            ChMessage::create([
                'from_id' => auth()->id(), // Sender (could be admin or support staff)
                'to_id' => $EndUserID, // Recipient (end user)
                'body' => $message, // The predefined message content
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Fetch user email based on employee_id
            $user = User::where('employee_id', $ticket->employee_id)->first();

            // Send email notification if user exists and has an email
            if ($user && $user->email) {
                Mail::to($user->email)->send(new TicketRemarkUpdate($ticket));
            }

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

            // Find the endorsement
            $endorsement = Endorsement::where('ticket_id', $ticketId)->first();

            if (!$endorsement) {
                \Log::error('No endorsement found for Ticket ID:', ['ticket_id' => $ticketId]);
                return response()->json(['message' => 'Endorsement not found.'], 404);
            }

            // Get the ticket details
            $ticket = Ticket::where('control_no', $ticketId)->first();

            if (!$ticket) {
                \Log::error('No ticket found for Ticket ID:', ['ticket_id' => $ticketId]);
                return response()->json(['message' => 'Ticket not found.'], 404);
            }

            // Get the assigned technical support details from User model
            $technicalSupport = User::where('employee_id', $ticket->technical_support_id)->first();

            return response()->json([
                'endorsement' => $endorsement,
                'technical_support' => $technicalSupport ? [
                    'first_name' => $technicalSupport->first_name,
                    'last_name' => $technicalSupport->last_name,
                    'remarks' => $ticket->remarks,
                    'employee_id' => $technicalSupport->employee_id
                ] : null
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error fetching endorsement details:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to fetch endorsement details.'], 500);
        }
    }

    public function endorseStore(Request $request)
    {

        $validated = $request->validate([
            'control_no' => 'required|string|max:255',
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
        
        // Debug validation data
        \Log::info('Validated data:', $validated);
        

        // Convert arrays to JSON
        $validated['network'] = json_encode($request->input('network', []));
        $validated['network_details'] = json_encode($request->input('network_details', []));
        $validated['user_account'] = json_encode($request->input('user_account', []));
        $validated['user_account_details'] = json_encode($request->input('user_account_details', []));

        // Check if endorsement already exists
        $endorsement = Endorsement::where('control_no', $validated['control_no'])->first();

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
        // Retrieve the administrator
        $admin = User::where('account_type', 'administrator')->first();

        if ($admin) {
            $notification = new SystemNotification(
                'TicketUpdated',
                'A new update has been made to a ticket that requires your approval.',
                route('ticket')
            );

            $admin->notify($notification);
        }
        $notification = $request->input('control_no');
        event(new NewNotification($notification));

        return redirect()->back()->with('success', $message);
    }

    public function getTicketDetails($ticketId)
    {
        $endorsement = Endorsement::where('ticket_id', $ticketId)->first();

        if (!$endorsement) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }

        // Fetch approval details
        $approval = Approval::where('ticket_id', $ticketId)->first();

        // Fetch rating based on control_no
        $rating = Rating::where('control_no', $endorsement->ticket_id)->first();

        return response()->json([
            'rating' => $rating ? $rating->rating : null, 
            'control_no' => $endorsement->control_no ?? "Not Available",
            'department' => $endorsement->department ?? "Not Available",
            'endorsed_to' => $endorsement->endorsed_to ?? "Not Available",
            'endorsed_to_date' => $endorsement->endorsed_to_date ?? "Not Available",
            'endorsed_to_time' => $endorsement->endorsed_to_time ?? "Not Available",
            'endorsed_to_remarks' => $endorsement->endorsed_to_remarks ?? "Not Available",
            'endorsed_by' => $endorsement->endorsed_by ?? "Not Available",
            'endorsed_by_date' => $endorsement->endorsed_by_date ?? "Not Available",
            'endorsed_by_time' => $endorsement->endorsed_by_time ?? "Not Available",
            'endorsed_by_remarks' => $endorsement->endorsed_by_remarks ?? "Not Available",
            'network' => $this->safeJsonDecode($endorsement->network),
            'network_details' => $this->safeJsonDecode($endorsement->network_details),
            'user_account' => $this->safeJsonDecode($endorsement->user_account),
            'user_account_details' => $this->safeJsonDecode($endorsement->user_account_details),
           
            // Approval details
            'approval' => [
                'name' => optional($approval)->name ?? "Not Available",
                'approve_date' => optional($approval)->approve_date ?? "Not Available"
            ],

        ]);
    }


    /**
     * Helper function to safely decode JSON values
     */
    private function safeJsonDecode($value)
    {
        $decoded = json_decode($value, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : $value; // Return original value if not JSON
    }



    public function checkTechnicalReport($control_no)
    {
        $report = TechnicalReport::where('control_no', $control_no)->first();

        // Fetch approval details
        $approval = Approval::where('ticket_id', $control_no)->first();

        if ($report) {
              // Fetch rating based on control_no
                $rating = Rating::where('control_no', $report->control_no)->first();

            return response()->json([
                'rating' => $rating ? $rating->rating : null, 
                'exists' => true,
                'report' => $report,
                // Approval details
                'approval' => [
                    'name' => optional($approval)->name ?? "Not Available",
                    'approve_date' => optional($approval)->approve_date ?? "Not Available"
                ]
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
        ]);

         // Retrieve the administrator
         $admin = User::where('account_type', 'administrator')->first();

         if ($admin) {
             $notification = new SystemNotification(
                 'TicketUpdated',
                 'A new update has been made to a ticket that requires your approval.',
                 route('ticket')
             );
 
             $admin->notify($notification);
         }
         $notification = $request->input('control_no');
         event(new NewNotification($notification));

        return redirect()->back()->with('success', 'Technical Report saved successfully.');
    }
    public function getDeploymentNames($control_no)
    {
        // Find the ticket by control_no
        $ticket = Ticket::where('control_no', $control_no)->first();

        if (!$ticket) {
            return response()->json(['success' => false, 'message' => 'Ticket not found.']);
        }

        // Get receive_by name from the ticket (assuming it's stored as a full name)
        $receiveByName = $ticket->name ?? 'Not Assigned';

        // Get assign_by (technical support) based on technical_support_id
        $assignBy = User::where('employee_id', $ticket->technical_support_id)->first();

        return response()->json([
            'success' => true,
            'receive_by_name' => $receiveByName,
            'assign_by_name' => $assignBy ? ($assignBy->first_name . ' ' . $assignBy->last_name) : 'Not Assigned'
        ]);
    }
    public function sendMessageToEndUser($ticketId)
    {
        \Log::info('Sending message for ticket ID: ' . $ticketId); // Log the ticket ID
    
        // Retrieve the ticket using the control number (ticketId)
        $ticket = Ticket::where('control_no', $ticketId)->first();
    
        if (!$ticket) {
            return response()->json(['success' => false, 'message' => 'Ticket not found.']);
        }
    
        // Check if the ticket is not marked as "Remarks Done"
        if (!$ticket->isRemarksDone) {
            // Get the technical support ID
            $endUserID = $ticket->employee_id;

            // Return a JSON response with the redirect URL
            return response()->json([
                'success' => true,
                'redirectUrl' => route('chatify', ['id' => $endUserID])
            ]);
        }
    
        return response()->json(['success' => false, 'message' => 'Ticket is already marked as Remarks Done.']);
    }
}
