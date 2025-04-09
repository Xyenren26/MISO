<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Concern;
use App\Models\User;
use App\Models\Approval;
use App\Models\Announcement;
use App\Models\ServiceRequest;
use App\Models\Endorsement;
use App\Models\Event;
use App\Models\TechnicalReport;
use App\Models\Ticket;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Chatify\Facades\ChatifyMessenger as Chatify; 
use App\Models\ChMessage;
use Illuminate\Support\Facades\Log;

class EndUserController extends Controller
{
    public function index()
    {
        // Fetch all technical support users excluding the current one
        $technicalSupports = User::where('account_type', 'technical_support')
            ->whereNotNull('session_id')  // Check if 'time_in' is not null
            ->get();

        // Count tickets based on status
        $inProcessingCount = Ticket::whereIn('status', ['completed', 'endorsed', 'technical-report', 'pull-out'])
                    ->where('employee_id', Auth::user()->employee_id)
                    ->whereDoesntHave('ratings')
                    ->count();
        $inProgressRequests = Ticket::where('status', 'in-progress')->count();
        $resolvedTickets = Ticket::whereIn('status', ['completed', 'endorsed', 'technical-report', 'pull-out'])
                            ->where('employee_id', Auth::user()->employee_id)
                            ->whereHas('ratings')
                            ->count();

        // Get the current year for control number formatting
        $currentYear = now()->year;
        $lastTicket = Ticket::whereYear('created_at', $currentYear)
            ->orderBy('control_no', 'desc')
            ->first();

        // Calculate the next control number
        $nextControlNo = $lastTicket ? (intval(substr($lastTicket->control_no, -4)) + 1) : 1;
        $formattedControlNo = 'TS-' . $currentYear . '-' . str_pad($nextControlNo, 4, '0', STR_PAD_LEFT);

        // Fetch announcements
        $announcements = Announcement::latest()->get();
        // Call the private cleanup function to delete expired events
        $this->cleanupExpiredEvents();

        // Fetch events for the authenticated user
        $events = Event::where('employee_id', auth()->user()->employee_id)->get();

        // Fetch recent tickets
        $recentTickets = \App\Models\Ticket::where('employee_id', auth()->user()->employee_id)
                    ->latest('time_in')
                    ->take(3)
                    ->get();

        $mainConcerns = Concern::where('type', 'main')->with('children')->get();
        // Pass variables to the view
        return view('employee.home', compact(
            'technicalSupports', 
            'formattedControlNo', 
            'inProcessingCount', 
            'inProgressRequests', 
            'resolvedTickets', 
            'announcements',
            'events', // Pass events to the view
            'recentTickets',
            'mainConcerns'
        ));
    }   

    // Private cleanup function
    private function cleanupExpiredEvents()
    {
        $now = now(); // Get the current date and time

        // Find all events that have ended
        $expiredEvents = Event::where('end', '<', $now)->get();

        // Delete each expired event
        foreach ($expiredEvents as $event) {
            $event->delete();
        }
    }
    
    public function showEmployeeTicket()
    {
        $user = auth()->user();

        if ($user->account_type !== 'end_user') {
            abort(403, 'Unauthorized access');
        }        
        
        // Fetch all tickets based on account type
        $status = request('filter');

        // Fetch all tickets (without pagination)
        $status = request('filter');
        $tickets = Ticket::where('employee_id', $user->employee_id)
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->with('history') // Include histories
            ->orderBy('created_at', 'desc')
            ->paginate(5)
            ->onEachSide(1); // Changed from paginate() to get()
   

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
            ->where('employee_id', Auth::user()->employee_id) // Only count tickets assigned to the logged-in user
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
            $inProcessingCount = Ticket::whereIn('status', ['completed', 'endorsed', 'technical-report', 'pull-out'])
                ->where('employee_id', Auth::user()->employee_id)
                ->whereDoesntHave('ratings')
                ->count();


        // Add isApproved and existsInModels check for each ticket
        foreach ($tickets as $ticket) {
            $ticket->isApproved = Approval::where('ticket_id', $ticket->control_no)->exists();

            $ticket->existsInModels =
                          Endorsement::where('ticket_id', $ticket->control_no)
                                    ->whereNotNull('endorsed_by')
                                    ->exists() ||
                          ServiceRequest::where('ticket_id', $ticket->control_no)
                                    ->whereNotNull('service_type')
                                    ->exists() ||
                          TechnicalReport::where('control_no', $ticket->control_no)
                                    ->whereNotNull('inspected_by')
                                    ->exists();
            $ticket->formfillup = $ticket->status !== 'completed' && (
                Endorsement::where('ticket_id', $ticket->control_no)->exists() ||
                ServiceRequest::where('ticket_id', $ticket->control_no)->exists() ||
                TechnicalReport::where('control_no', $ticket->control_no)->exists()
            );
            $ticket->isRated = Rating::where('control_no', $ticket->control_no)->exists();
            $ticket->isRepaired = ServiceRequest::where('ticket_id', $ticket->control_no)
                                    ->where('status', 'repaired')
                                    ->exists();
                                    
        }

        // Filter tickets that are not approved if requested
        if ($user->account_type === 'administrator') {
            $tickets = $tickets->filter(fn($ticket) => !$ticket->isApproved);
        }
        $mainConcerns = Concern::where('type', 'main')->with('children')->get();
        return view('employee.ticket', compact(
            'tickets', 'technicalAssistSupports', 'technicalSupports', 
            'formattedControlNo', 'nextFormNo', 'inProgressCount', 'endorsedCount' ,
            'technicalReportCount','pullOutCount','inProcessingCount', 'mainConcerns'
        ));
    }

    public function filterEmployeeTickets(Request $request)
    {
        $status = $request->get('status');
        $priority = $request->get('priority');
        $search = $request->get('search'); 
        $startDate = $request->get('fromDate');
        $endDate = $request->get('toDate');


        // Get the currently logged-in user
        $currentEmployeeId = Auth::user()->employee_id;
        $user = Auth::user();

        // Start building the query for tickets
        $query = Ticket::where('employee_id', $currentEmployeeId);

        // Apply search filter for control_no and name
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
        

        // Handle status filtering
        if ($status === 'recent') {
            $query->orderBy('created_at', 'desc');
        } elseif ($status === 'processing') {
            $query->whereIn('status', ['completed', 'endorsed', 'technical-report', 'pull-out'])
                ->whereDoesntHave('ratings'); // Tickets with NO rating
        } elseif ($status === 'closed') {
            $query->whereIn('status', ['completed', 'endorsed', 'technical-report', 'pull-out'])
                ->whereHas('ratings'); // Tickets WITH a rating
        } elseif (!empty($status)) {
            $query->where('status', $status);
        }

        // Apply priority filtering
        if (!empty($priority)) {
            $query->where('priority', $priority);
        }

        // Paginate the results
        $tickets = $query->paginate(5)->onEachSide(1);

        // Fetch all ticket control numbers to minimize queries
        $controlNumbers = $tickets->pluck('control_no');

        // Fetch related records for all tickets at once
        $approvals = Approval::whereIn('ticket_id', $controlNumbers)->pluck('ticket_id')->toArray();
        $endorsements = Endorsement::whereIn('ticket_id', $controlNumbers)->get();
        $serviceRequests = ServiceRequest::whereIn('ticket_id', $controlNumbers)->get();
        $technicalReports = TechnicalReport::whereIn('control_no', $controlNumbers)->get();
        $ratings = Rating::whereIn('control_no', $controlNumbers)->pluck('control_no')->toArray();

        // Process each ticket for additional attributes
        foreach ($tickets as $ticket) {
            $ticket->isAssistDone = $ticket->history->where('ticket_id', $ticket->control_no)->count() > 0;
            $ticket->isRemarksDone = in_array($ticket->status, ['completed', 'endorsed', 'technical-report', 'pull-out']);
            $ticket->isApproved = in_array($ticket->control_no, $approvals);
            
            // Check if ticket exists in related models
            $ticket->existsInModels = 
                                    $endorsements->where('ticket_id', $ticket->control_no)->whereNotNull('endorsed_to')->isNotEmpty() ||
                                    $serviceRequests->where('ticket_id', $ticket->control_no)->whereNotNull('service_type')->isNotEmpty() ||
                                    $technicalReports->where('control_no', $ticket->control_no)->whereNotNull('inspected_by')->isNotEmpty();
                                    
            $ticket->formfillup =
                                $endorsements->where('ticket_id', $ticket->control_no)->isNotEmpty() ||
                                $serviceRequests->where('ticket_id', $ticket->control_no)->isNotEmpty() ||
                                $technicalReports->where('control_no', $ticket->control_no)->isNotEmpty();
                                
            $ticket->isRated = in_array($ticket->control_no, $ratings);
            $ticket->isRepaired = ServiceRequest::where('ticket_id', $ticket->control_no)
                                    ->where('status', 'repaired')
                                    ->exists();
        }

        // Fetch technical supports
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
        $mainConcerns = Concern::where('type', 'main')->with('children')->get();
        return view('components.employee.ticket-list', compact('tickets', 'technicalSupports', 'technicalAssistSupports', 'nextFormNo', 'mainConcerns'))->render();
    }


    public function trackDeviceStatus($ticket_id)
    {
        $serviceRequest = ServiceRequest::where('ticket_id', $ticket_id)->first();

        if ($serviceRequest) {
            return response()->json([
                'status' => $serviceRequest->status,  // in-repairs or repaired
                'service_type' => $serviceRequest->service_type // pull_out or others
            ]);
        }

        // If not found, return a proper JSON error
        return response()->json(['error' => 'Service request not found.'], 404);
    }

  
    public function sendMessageToTechnicalSupport($ticketId)
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
            $technicalSupportId = $ticket->technical_support_id;
    
            // Construct the predefined message
            $message = "Hello, {$ticket->technical_support_name} this is a message regarding to my 
                        Ticket Service Request with Ticket No:{$ticketId}.";
    
            // Create a new message using the ChMessage model
            ChMessage::create([
                'from_id' => auth()->id(), // The ID of the sender
                'to_id' => $technicalSupportId, // The ID of the recipient
                'body' => $message, // The predefined message content
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Return a JSON response with the redirect URL
            return response()->json([
                'success' => true,
                'redirectUrl' => route('chatify', ['id' => $technicalSupportId])
            ]);
        }
    
        return response()->json(['success' => false, 'message' => 'Ticket is already marked as Remarks Done.']);
    }
}
