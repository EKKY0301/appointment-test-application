<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;
use Carbon\Carbon;

class ScheduledMail extends Mailable
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

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nueva Cita Programada',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.appointment.scheduled',
            with: [
                'appointment' => $this->appointment,  // pasamos el modelo entero
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
