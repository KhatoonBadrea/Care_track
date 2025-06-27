<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\TreatmentPlan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TreatmentPlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patient = Patient::first();

        TreatmentPlan::create([
            'patient_id' => $patient->id,
            'diagnosis' => 'Hypertension Stage 1',
            'medications' => 'Amlodipine 5mg daily',
            'instructions' => 'Reduce salt intake, daily walking 30 min',
            'start_date' => now(),
            'end_date' => now()->addMonths(1),
        ]);
    }
}
