<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Ticket;
use App\Models\TechnicalReport;
use App\Models\Endorsement;

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
        $technicalReportId = null;
        $endorsementControlNo = null;

        if ($this->ticket->status === 'technical-report') {
            $technicalReport = TechnicalReport::where('control_no', $this->ticket->control_no)->first();
            $technicalReportId = $technicalReport?->TR_id; // Or TR_id if that’s your column name
        }

        if ($this->ticket->status === 'endorsed') {
            $endorsement = Endorsement::where('ticket_id', $this->ticket->control_no)->first();
            $endorsementControlNo = $endorsement?->control_no;
        }

        return $this->subject('Your Ticket Has Been Approved')
            ->view('emails.ticket_approved')
            ->with([
                'ticketId' => $this->ticket->control_no,
                'status' => $this->ticket->status,
                'approvedBy' => auth()->user()?->first_name . ' ' . auth()->user()?->last_name ?? 'System',
                'approveDate' => now()->format('F d, Y H:i A'),
                'technicalReportId' => $technicalReportId,
                'endorsementControlNo' => $endorsementControlNo,
            ]);
    }

}
