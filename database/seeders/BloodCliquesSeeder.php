<?php

namespace Database\Seeders;

use App\Models\BloodClique;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BloodCliquesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

        foreach ($types as $type) {
            BloodClique::create(['blood_clique' => $type]);
        }
    }
}
