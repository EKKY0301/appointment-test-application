@component('mail::message')
# Nueva cita programada

**Título:** {{ $appointment->title }}  
**Fecha de inicio:** {{ $appointment->start_at->format('d/m/Y H:i') }}  
**Fecha de fin:**   {{ $appointment->end_at->format('d/m/Y H:i') }}

**Paciente:** {{ $appointment->patient->name }}  
**Doctor:**   {{ $appointment->doctor->name }}

---


Saludos,  
**El equipo de {{ config('app.name') }}**

---

<sub>Este es un mensaje automático, por favor no lo respondas directamente.  
Si tienes dudas, contáctanos al soporte: soporte@{{ parse_url(config('app.url'), PHP_URL_HOST) }}</sub>
@endcomponent
