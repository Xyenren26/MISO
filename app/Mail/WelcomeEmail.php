<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Auth\Notifications\VerifyEmail;

class WelcomeEmail extends Mailable
{
    use SerializesModels;

    public $user;
    public $verificationUrl;

    public function __construct($user)
    {
        $this->user = $user;

        // Pass employee_id as the ID and use sha1() for email hashing
        $this->verificationUrl = route('RegistrationEmailValidate', [
            'id' => $this->user->employee_id,  // Use employee_id as the ID
            'hash' => sha1($this->user->email),  // Hash the email for verification
        ]);
    }

    public function build()
    {
        return $this->subject('Welcome to Our System!')
                    ->view('emails.welcome')
                    ->with([
                        'name' => $this->user->name,
                        'verification_url' => $this->verificationUrl,
                    ]);
    }
}
