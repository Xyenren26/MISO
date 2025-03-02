<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketRemarkUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->subject('Technical Service Request Status Updated')
                    ->view('emails.ticket_update_remarks')
                    ->with([
                        'control_no' => $this->ticket->control_no,
                        'status' => $this->ticket->status,
                    ]);
    }
}
