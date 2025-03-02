<?php

namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Ticket;

class TicketApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function build()
    {
        return $this->subject('Your Ticket Has Been Approved')
                    ->view('emails.ticket_approved')
                    ->with([
                        'ticketId' => $this->ticket->control_no,
                        'status' => $this->ticket->status, // Include the ticket's status
                        'approvedBy' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                        'approveDate' => now()->format('F d, Y H:i A'),
                    ]);
    }
}
