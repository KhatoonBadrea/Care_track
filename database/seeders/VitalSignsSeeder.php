<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Patient;
use App\Models\VitalSign;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VitalSignsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patient = Patient::first();

        VitalSign::create([
            'patient_id' => $patient->id,
            'temperature' => 37.2,
            'heart_rate' => 78,
            'blood_pressure_systolic' => 120,
            'blood_pressure_diastolic' => 80,
            'respiratory_rate' => 18,
            'measured_at' => Carbon::now(),
        ]);
    }
}
