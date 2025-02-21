<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Approval;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketApproved;
use App\Models\User;

class ApprovalController extends Controller
{

    public function approveTicket(Request $request)
    {
        // Validate request to ensure ticket_id exists
        $request->validate([
            'ticket_id' => 'required|exists:tickets,control_no'
        ]);

        // Fetch the ticket
        $ticket = Ticket::where('control_no', $request->ticket_id)->first();

        // Ensure ticket exists
        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found.'
            ]);
        }

        // Save approval
        $approval = Approval::create([
            'ticket_id' => $request->ticket_id,
            'name' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
            'approve_date' => now(),
            'noted_by' => auth()->user()->employee_id, // Assuming `noted_by` is the approving admin
        ]);

        // Send email notification
        $emailSent = $this->sendApprovalEmail($ticket);
        
        if (!$emailSent['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket approved, but email failed: ' . $emailSent['message']
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ticket approved successfully!',
            'approval' => $approval
        ]);
    }

    /**
     * Send an email notification when a ticket is approved.
     */
    private function sendApprovalEmail($ticket)
    {
        try {
            $user = User::where('employee_id', $ticket->employee_id)->first();
            if ($user) {
                Mail::to($user->email)->send(new TicketApproved($ticket));
                return ['success' => true];
            }
            return ['success' => false, 'message' => 'User not found.'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }



    public function getApprovalDetails(Request $request)
    {
        $ticketId = $request->ticket_id;

        $approval = DB::table('approvals')
            ->where('ticket_id', $ticketId)
            ->select('name', 'approve_date')
            ->first();

        return response()->json($approval);
    }

}
