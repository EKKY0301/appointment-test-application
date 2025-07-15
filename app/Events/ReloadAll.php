<?php
// app/Events/AppointmentDeleted.php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class ReloadAll implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public string $reason;

    public function __construct(string $reason)
    {
        $this->reason = $reason;
    }

    // Canal público “appointments”
    public function broadcastOn(): Channel
    {
        return new Channel('relaod');
    }

    // Payload que llegará al cliente
    public function broadcastWith(): array
    {
        return ['reason' => $this->reason];
    }
}
