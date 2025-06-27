<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use App\Models\Relative;
use App\Models\BloodClique;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PatientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patientUser = User::where('role', 'patient')->first();
        $bloodClique = BloodClique::inRandomOrder()->first();
        $relative = Relative::first(); 

        Patient::create([
            'user_id' => $patientUser->id,
            'blood_clique_id' => $bloodClique->id,
            'gender' => 'Male',
            'birth_date' => '1995-05-01',
            'notes' => 'Has mild hypertension',
            'relative_id' => $relative ? $relative->id : null,
        ]);
    }
}
