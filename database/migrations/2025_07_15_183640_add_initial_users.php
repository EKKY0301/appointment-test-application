<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

return new class extends Migration
{
    public function up(): void
    {
        $now = Carbon::now();

        DB::table('users')->insert([
            // Pacientes
            [
                'name'       => 'Alice Smith',
                'email'      => 'alice.smith@example.com',
                'role'       => 'patient',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Bob Johnson',
                'email'      => 'bob.johnson@example.com',
                'role'       => 'patient',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Carol Williams',
                'email'      => 'carol.williams@example.com',
                'role'       => 'patient',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Doctores
            [
                'name'       => 'Dr. John Doe',
                'email'      => 'john.doe@example.com',
                'role'       => 'doctor',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Dr. Jane Roe',
                'email'      => 'jane.roe@example.com',
                'role'       => 'doctor',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Dr. Max Mustermann',
                'email'      => 'max.mustermann@example.com',
                'role'       => 'doctor',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('users')
            ->whereIn('email', [
                'alice.smith@example.com',
                'bob.johnson@example.com',
                'carol.williams@example.com',
                'john.doe@example.com',
                'jane.roe@example.com',
                'max.mustermann@example.com',
            ])->delete();
    }
};
