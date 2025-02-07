<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationMail extends Mailable
{
    use SerializesModels;

    public $user;
    public $verificationUrl;

    public function __construct($user)
    {
        $this->user = $user;
        
        // Pass employee_id as the ID and use sha1() for email hashing
        $this->verificationUrl = route('verification.custom.verify', [
            'id' => $this->user->employee_id,  // Use employee_id as the ID
            'hash' => sha1($this->user->email),  // Hash the email for verification
        ]);
    }

    public function build()
    {
        return $this->subject('Verify Your Email')
                    ->view('emails.verification-email')
                    ->with([
                        'name' => $this->user->name,
                        'verification_url' => $this->verificationUrl,
                    ]);
    }
}
