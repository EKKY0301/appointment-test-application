<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1) Renombrar la tabla patients → users
        Schema::rename('patients', 'users');

        // 2) Ajustar FKs en appointments
        Schema::table('appointments', function (Blueprint $table) {
            // 2.1) Eliminar constraints antiguas
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['doctor_id']);

            // 2.2) Volver a crear ambas FKs apuntando a users.id
            $table->foreign('patient_id')
                  ->references('id')->on('users')
                  ->cascadeOnDelete();

            $table->foreign('doctor_id')
                  ->references('id')->on('users')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        // 1) Eliminar las FKs nuevas
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['patient_id']);
        });

        // 2) Renombrar users → patients
        Schema::rename('users', 'patients');

        // 3) Restaurar los FKs originales de appointments
        Schema::table('appointments', function (Blueprint $table) {
            // paciente → patients.id
            $table->foreign('patient_id')
                  ->references('id')->on('patients')
                  ->cascadeOnDelete();

            // doctor → doctors.id
            $table->foreign('doctor_id')
                  ->references('id')->on('doctors')
                  ->cascadeOnDelete();
        });
    }
};
