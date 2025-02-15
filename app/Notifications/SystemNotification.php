<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SystemNotification extends Notification 
{
    use Queueable;

    public $type;
    public $message;
    public $link;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $type, string $message, ?string $link = null)
    {
        $this->type = $type;
        $this->message = $message;
        $this->link = $link;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database']; // Store in the database
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'type' => $this->type,
            'message' => $this->message,
            'link' => $this->link ?? '#', // Ensure link exists
        ];
    }    
}
