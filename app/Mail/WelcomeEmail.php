<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class WelcomeEmail extends Mailable
{
    use SerializesModels;

    public $user;
    public $verificationUrl;
    public $expiresAt;

    public function __construct($user)
    {
        $this->user = $user;

        // Set expiration time (60 mns from now)
        $this->expiresAt = now()->addMinutes(60);

        // Generate a signed URL with expiration time
        $this->verificationUrl = URL::temporarySignedRoute(
            'RegistrationEmailValidate', $this->expiresAt, [
                'id' => $this->user->employee_id,  
                'hash' => sha1($this->user->email),  
            ]
        );
    }

    public function build()
    {
        return $this->subject('Welcome to Our System!')
                    ->view('emails.welcome')
                    ->with([
                        'name' => $this->user->username,
                        'verification_url' => $this->verificationUrl,
                        'expiresAt' => $this->expiresAt
                    ]);
    }
}
