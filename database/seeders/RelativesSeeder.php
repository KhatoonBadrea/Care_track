<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use App\Models\Relative;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RelativesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $relativeUsers = User::where('role', 'relative')->get();

       foreach ($relativeUsers as $user) {
            $relative = Relative::create([
                'user_id' => $user->id,
                'name' => 'Fatima Ahmad',
                'relation' => 'mother',
                'phone' => '0999999999',
                'email' => 'fatima' . $user->id . '@example.com',
            ]);
        }
    }
}