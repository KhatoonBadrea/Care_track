<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctorUsers = User::where('role', 'doctor')->get();

        foreach ($doctorUsers as $user) {
            Doctor::create([
                'user_id' => $user->id,
                'Specialization' => 'General Medicine',
                'phone_number' => '+9639999',
            ]);
        }
    }
}
