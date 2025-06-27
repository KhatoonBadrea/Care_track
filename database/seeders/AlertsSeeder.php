<?php

namespace Database\Seeders;

use App\Models\Alert;
use App\Models\Patient;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AlertsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patient = Patient::first();

        Alert::create([
            'patient_id' => $patient->id,
            'type' => 'blood_pressure',
            'message' => 'High systolic pressure detected',
            'alerted_at' => now(),
        ]);
    }
}
