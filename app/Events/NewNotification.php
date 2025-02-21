<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $notification;
    public $channelName;


    public function __construct($notification, $channelName = 'notifications') {
        $this->notification = $notification;
        $this->channelName = $channelName;
    }

    public function broadcastOn()
    {
        return new Channel($this->channelName); // Broadcast to the specified channel
    }
    

    public function broadcastAs() {
        return 'new-notification';
    }
}
