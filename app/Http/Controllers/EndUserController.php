<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
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
        
    
    public function showEmployeeTicket(){
        // Get the current logged-in user
        $user = auth()->user();

        // Fetch tickets assigned to the logged-in technical support user
        $status = request('filter');
        $tickets = Ticket::where('employee_id', $user->employee_id)
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
        return view ('employee.ticket', compact('tickets','technicalSupports', 'formattedControlNo'));
    }

    public function filterEmployeeTickets(Request $request)
    {
        $status = $request->get('status');
        $priority = $request->get('priority');
        
        // Get the currently logged-in end user
        $currentUserId = Auth::user()->employee_id;

        // Start building the query for tickets assigned to this user
        $query = Ticket::where('employee_id', $currentUserId); // Change this to match your column for tracking ticket owners

        // Handle the "recent" filter to show all latest records
        if ($status === 'recent') {
            $query->orderBy('created_at', 'desc');
        } else {
            if ($status) {
                $statusArray = explode(',', $status);
                $query->whereIn('status', $statusArray);
            }
        }

        // Filter by priority if provided
        if ($priority) {
            $query->where('priority', $priority);
        }

        // Paginate the results
        $tickets = $query->paginate(10);

        // Render the end-user-specific ticket list
        return view('components.employee.ticket-list', compact('tickets'))->render();
    }

}
