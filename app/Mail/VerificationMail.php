<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class VerificationMail extends Mailable
{
    use SerializesModels;

    public $user;
    public $verificationUrl;
    public $expiresAt;

    public function __construct($user)
    {
        $this->user = $user;
        
        $this->expiresAt = now()->addMinutes(60); // Expiration time

        $this->verificationUrl = route('verification.custom.verify', [
            'id' => $this->user->employee_id,  
            'hash' => sha1($this->user->email),
            'expires' => $this->expiresAt->timestamp // Add expiration timestamp
        ]);
    }

    public function build()
    {
        return $this->subject('Verify Your Email')
                    ->view('emails.verification-email')
                    ->with([
                        'user' => $this->user,
                        'verificationUrl' => $this->verificationUrl,
                        'expiresAt' => $this->expiresAt
                    ]);
    }
}
