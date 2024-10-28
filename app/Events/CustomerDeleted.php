<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerDeleted implements ShouldBroadcast
{
    public $customerId;

    public function __construct($customerId)
    {
        $this->customerId = $customerId;  // Pasamos solo la ID de la customer eliminada
    }

    public function broadcastOn()
    {
        return new Channel('customers');  // Canal de broadcasting
    }

    public function broadcastWith()
    {
        return [
            'customerId' => $this->customerId,  // Lo que se envía a los usuarios
        ];
    }
}
