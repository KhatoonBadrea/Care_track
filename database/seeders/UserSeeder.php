<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'name' => 'ali',
            'email' => 'ali@example.com',
            'role' => 'patient',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'name' => 'anas',
            'email' => 'anas@example.com',
            'role' => 'patient',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'name' => 'maha',
            'email' => 'maha@example.com',
            'role' => 'patient',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'name' => 'alaa',
            'email' => 'alaa@example.com',
            'role' => 'relative',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'name' => 'ola',
            'email' => 'ola@example.com',
            'role' => 'relative',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'name' => 'alia',
            'email' => 'alia@example.com',
            'role' => 'relative',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'name' => 'alma',
            'email' => 'alma@example.com',
            'role' => 'doctor',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'name' => 'omar',
            'email' => 'omar@example.com',
            'role' => 'doctor',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'name' => 'rama',
            'email' => 'rama@example.com',
            'role' => 'doctor',
            'password' => Hash::make('password'),
        ]);
    }
}
