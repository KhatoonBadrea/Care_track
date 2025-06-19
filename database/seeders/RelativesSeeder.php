<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Relative;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RelativesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patient = Patient::first();

        Relative::create([
            'patient_id' => $patient->id,
            'name' => 'Fatima Ahmad',
            'relation' => 'mother',
            'phone' => '0999999999',
            'email' => 'fatima@example.com',
        ]);
    }
}
