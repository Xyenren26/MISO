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

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        $verificationUrl = route('verification.verify', [
            'id' => $this->user->getKey(),  // User ID
            'hash' => sha1($this->user->getEmailForVerification())  // Hashed email for verification
        ]);

        return $this->subject('Welcome to Our System!')
                    ->view('emails.welcome')
                    ->with([
                        'name' => $this->user->name,
                        'verification_url' => $verificationUrl,
                    ]);
    }
}
