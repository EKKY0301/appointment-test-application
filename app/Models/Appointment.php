<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory;

    // Permitir cast de start_at y end_at a Carbon automáticamente
    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
    ];

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'title',
        'start_at',
        'end_at',
        'status',
    ];

    /**
     * Relación con paciente
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Relación con doctor
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Devuelve la fecha de inicio formateada para API
     */
    public function getStartAtForApiAttribute(): string
    {
        return $this->start_at->timezone('America/New_York')->toIso8601String();
    }

    /**
     * Devuelve la fecha de fin formateada para API
     */
    public function getEndAtForApiAttribute(): string
    {
        return $this->end_at->timezone('America/New_York')->toIso8601String();
    }
}
