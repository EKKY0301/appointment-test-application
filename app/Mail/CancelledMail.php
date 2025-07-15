<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;
use Carbon\Carbon;

class CancelledMail extends Mailable
{
    use Queueable, SerializesModels;

    public Appointment $appointment;

    public function __construct(Appointment $appointment)
    {
        $appointment->start_at = Carbon::parse($appointment->start_at)
                                   ->timezone('America/New_York');
        $appointment->end_at   = Carbon::parse($appointment->end_at)
                                   ->timezone('America/New_York');
        $this->appointment = $appointment;
    }

    public function build()
    {
        return $this
            ->subject('Cita Cancelada')
            ->markdown('emails.appointment.cancelled')
            ->with([
                'appointment' => $this->appointment,
            ]);
    }
}
