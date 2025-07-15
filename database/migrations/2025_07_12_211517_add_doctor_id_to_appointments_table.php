<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('appointments', function (Blueprint $table) {
        $table->foreignId('doctor_id')
              ->after('patient_id')
              ->constrained('patients')
              ->cascadeOnDelete();
    });
}

public function down(): void
{
    Schema::table('appointments', function (Blueprint $table) {
        $table->dropForeign(['doctor_id']);
        $table->dropColumn('doctor_id');
    });
}

};
