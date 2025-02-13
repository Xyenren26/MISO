<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;


class WelcomeEmail extends Mailable
{
    use SerializesModels;

    public $user;
    public $verificationUrl;

    public function __construct($user)
    {
    $this->user = $user;

    // Generate a signed URL with an expiration time (24 hours)
    $this->verificationUrl = URL::temporarySignedRoute(
        'RegistrationEmailValidate', now()->addHours(24), [
            'id' => $this->user->employee_id,   // Use employee_id as the ID
            'hash' => sha1($this->user->email),  // Hash the email for verification
        ]);
    }

    public function build()
    {
        return $this->subject('Welcome to Our System!')
                    ->view('emails.welcome')
                    ->with([
                        'name' => $this->user->username,
                        'verification_url' => $this->verificationUrl,
                    ]);
    }
}
