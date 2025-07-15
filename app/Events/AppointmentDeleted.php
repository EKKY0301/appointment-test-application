<?php
// app/Events/AppointmentDeleted.php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class AppointmentDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public int $appointmentId;

    public function __construct(int $appointmentId)
    {
        $this->appointmentId = $appointmentId;
    }

    // Canal público “appointments”
    public function broadcastOn(): Channel
    {
        return new Channel('appointments');
    }

    // Payload que llegará al cliente
    public function broadcastWith(): array
    {
        return ['id' => $this->appointmentId];
    }
}
