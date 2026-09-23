<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AprendizDemoSeeder extends Seeder
{
    /**
     * Crea una cuenta de Aprendiz de demostración para pruebas.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'aprendiz@sena.edu.co'],
            [
                'name' => 'Aprendiz Demo',
                'email' => 'aprendiz@sena.edu.co',
                'password' => 'aprendiz12345',
                'documento' => '1000000001',
                'programa' => 'Análisis y Desarrollo de Software',
                'ficha' => '2754123',
                'role' => User::ROL_APRENDIZ,
            ]
        );
    }
}