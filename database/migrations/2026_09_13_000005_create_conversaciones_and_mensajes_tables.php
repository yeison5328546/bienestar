<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->cascadeOnDelete();
            $table->string('iniciada_por', 10)->default('aprendiz');
            $table->string('estado', 12)->default('solicitada');
            $table->unsignedBigInteger('cerrada_por')->nullable();
            $table->timestamp('iniciada_at')->nullable();
            $table->timestamp('cerrada_at')->nullable();
            $table->timestamps();

            $table->foreign('cerrada_por')->references('id')->on('users')->nullOnDelete();
            $table->index(['solicitud_id', 'estado']);
        });

        Schema::create('mensajes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversacion_id')->constrained('conversaciones')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('contenido');
            $table->timestamps();

            $table->index(['conversacion_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensajes');
        Schema::dropIfExists('conversaciones');
    }
};