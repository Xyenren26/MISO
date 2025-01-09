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
    $status = request('filter');
    
    // If filter is applied (e.g., 'recent', 'in-progress', etc.)
    if ($status) {
        // Fetch tickets by status
        $tickets = Ticket::where('status', $status)
            ->orderBy('created_at', 'desc')  // Order by latest records
            ->paginate(5); // Change the pagination number as needed
    } else {
        // For the "Recent" tab, display the latest 20 records regardless of status
        $tickets = Ticket::orderBy('created_at', 'desc')
            ->paginate(20); // You can change the 20 if you'd like
    }

    $techSupport = User::where('role', 'technical-support')->get();
    $lastTicket = Ticket::orderBy('control_no', 'desc')->first();
    $nextControlNo = $lastTicket ? $lastTicket->control_no + 1 : 1;
    $devices = Ticket::distinct()->pluck('device');
    
    return view('ticket', compact('tickets', 'techSupport', 'nextControlNo', 'devices'));
}



    public function createTicket(Request $request)
    {
    $validated = $request->validate([
        'first-name' => 'required|string',
        'last-name' => 'required|string',
        'department' => 'required|string',
        'concern' => 'required|string',
        'category' => 'required|string',
        'employeeId' => 'required|string|unique:tickets,employee_id',
    ]);

    // Save ticket to the database
    Ticket::create([
        'control_no' => $request->input('control_no'),
        'name' => $request->input('first-name') . ' ' . $request->input('last-name'),
        'department' => $request->input('department'),
        'priority' => $request->input('category'),
        'status' => 'pending',
        'employee_id' => $request->input('employeeId'),
    ]);

    return redirect()->route('ticket')->with('success', 'Ticket created successfully!');
    }


    public function filterTickets($status)
{
    if ($status == 'recent') {
        // Fetch the latest 20 tickets regardless of status
        $tickets = Ticket::latest()->take(20)->get();
    } else {
        // For other statuses (solved, in-progress, etc.)
        $tickets = Ticket::where('status', $status)->orderBy('created_at', 'desc')->get();
    }

    // Check if no tickets are found for the current filter
    if ($tickets->isEmpty()) {
        $html = ' <div class="no-records">NO RECORDS FOUND</div>';
    } else {
        // Render the tickets list if found
        $html = view('components.ticket-list', compact('tickets'))->render();
    }
    
    return response()->json(['html' => $html]);
}



}    
