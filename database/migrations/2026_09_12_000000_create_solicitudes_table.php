<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_aprendiz');
            $table->string('documento', 50);
            $table->string('email')->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('programa')->nullable();
            $table->string('ficha', 50)->nullable();
            $table->enum('tipo_requerimiento', ['Solicitud', 'Queja', 'Reclamo', 'Asesoría', 'Otro']);
            $table->text('descripcion');
            $table->enum('estado', ['Pendiente', 'En Proceso', 'Resuelto'])->default('Pendiente');
            $table->timestamps();

            $table->index('estado');
            $table->index(['nombre_aprendiz', 'documento']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};