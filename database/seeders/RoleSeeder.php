<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Creamos al usuario con el rango más alto
        User::create([
            'name' => 'Director Fey y Alegría',
            'email' => 'admin@feyalegria.edu',
            'password' => Hash::make('admin123'),
            'role' => 'super_admin',
        ]);
    }
}
