<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Determine which channels to send the notification on.
     */
    public function via($notifiable)
    {
        return ['mail']; // This makes sure it is sent via email
    }

    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $expiration = now()->addMinutes(config('auth.passwords.users.expire'))->timestamp; // Expiry time

        return (new MailMessage)
            ->subject('Reset Your Password')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', url(route('password.reset', [
                'token' => $this->token, 
                'email' => $notifiable->email,
                'expires' => $expiration
            ], false)))
            ->line('This link will expire in ' . config('auth.passwords.users.expire') . ' minutes.')
            ->line('If you did not request a password reset, no further action is required.');
    }

}
