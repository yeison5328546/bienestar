<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Crea la única cuenta de Administrador (Natalia).
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bienestar.com'],
            [
                'name' => 'Natalia',
                'email' => 'admin@bienestar.com',
                'password' => 'admin12345',
                'role' => User::ROL_ADMIN,
            ]
        );
    }
}