<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->string('tipo_requerimiento', 255)->change();
            $table->string('estado', 20)->change();
        });
    }

    public function down(): void
    {
        // no invertible de forma segura (las listas ahora viven en el código).
    }
};