<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ServiceRequestUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $serviceRequest;

    public function __construct($serviceRequest)
    {
        $this->serviceRequest = $serviceRequest;
    }

    public function build()
    {
        return $this->subject('Service Request Status Updated')
                    ->view('emails.service_request_updated')
                    ->with([
                        'form_no' => $this->serviceRequest->form_no,
                        'status' => $this->serviceRequest->status,
                    ]);
    }
}
