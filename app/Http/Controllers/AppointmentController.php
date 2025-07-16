<?php

namespace App\Http\Controllers;

use App\Events\AppointmentDeleted;
use App\Events\ReloadAll;
use App\Mail\CancelledMail;
use App\Mail\ScheduledMail;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $appointments = Appointment::with(['patient','doctor'])->get()
            ->map(function($appt) {
                $appt->start_at = $appt->start_at_for_api;
                $appt->end_at   = $appt->end_at_for_api;
                return $appt;
            });

        return $appointments->makeHidden(['patient_id','doctor_id']);
    }

    public function store(Request $request)
    {
        // Validación inicial
        $data = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id'  => 'required|exists:users,id',
            'title'      => 'required|string|max:255',
            'start_at'   => 'required|date|after_or_equal:now',
            'end_at'     => 'required|date|after:start_at',
            'status'     => ['sometimes', Rule::in(['pending','responded','finished','deleted'])],
        ]);

        // Convertir payload ISO8601 (UTC/Z) a UTC MySQL
        $start = Carbon::parse($data['start_at'])
            ->setTimezone('UTC')
            ->toDateTimeString();
        $end = Carbon::parse($data['end_at'])
            ->setTimezone('UTC')
            ->toDateTimeString();

        // Validar solapamiento para el mismo doctor
        $conflict = Appointment::where('doctor_id', $data['doctor_id'])
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('start_at', [$start, $end])
                  ->orWhereBetween('end_at',   [$start, $end])
                  ->orWhere(function($q2) use ($start, $end) {
                      $q2->where('start_at', '<', $start)
                         ->where('end_at',   '>', $end);
                  });
            })->exists();

        if ($conflict) {
            return response()->json([
                'error' => 'El intervalo de tiempo se superpone con otra cita existente.'
            ], 422);
        }

        // Crear la cita en UTC
        $appointment = Appointment::create([
            'patient_id' => $data['patient_id'],
            'doctor_id'  => $data['doctor_id'],
            'title'      => $data['title'],
            'start_at'   => $start,
            'end_at'     => $end,
            'status'     => $data['status'] ?? 'pending',
        ]);

        // Enviar notificaciones por mail (fechas formateadas en modelo)
        // try {
        //     Mail::to($appointment->patient->email)
        //         ->send(new ScheduledMail($appointment));
        //     Mail::to($appointment->doctor->email)
        //         ->send(new ScheduledMail($appointment));
        // } catch (\Throwable $e) {
        //     return response()->json([
        //         'error'   => 'No se pudo enviar el mail de cita programada',
        //         'details' => $e->getMessage(),
        //     ], 500);
        // }

        event(new ReloadAll("AppointmentCreado"));

        return response()->json($appointment, 201);
    }

    public function destroy(Appointment $appointment)
    {
        $id = $appointment->id;

        // Notificar cancelación
        // try {
        //     Mail::to($appointment->patient->email)
        //         ->send(new CancelledMail($appointment));
        //     Mail::to($appointment->doctor->email)
        //         ->send(new CancelledMail($appointment));
        // } catch (\Throwable $e) {
        //     return response()->json([
        //         'error'   => 'No se pudo enviar el mail de cancelación',
        //         'details' => $e->getMessage(),
        //     ], 500);
        // }

        event(new AppointmentDeleted($id));
        $appointment->delete();

        return response()->json(null, 204);
    }
}
