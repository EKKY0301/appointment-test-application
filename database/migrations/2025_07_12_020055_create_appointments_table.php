<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            //nota: agregar fk, para que no haya algun null
            $table->foreignId('patient_id')
                  ->constrained('patients')
                  // poner el cascade on delete
                  ->cascadeOnDelete();
            $table->string('title');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->enum('status', ['pending', 'responded', 'finished', 'deleted'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

/*
*   Migracion para crear la tabla de Appointments, se puede agreegar la reserva
*   title: es para el titulo
*   tiempo de inicio (start_at) y tiempo de final para manejar de cuando a caundo es lo que se tiene 
*   que reservar para validar que no exista una hora en especifico.
*/