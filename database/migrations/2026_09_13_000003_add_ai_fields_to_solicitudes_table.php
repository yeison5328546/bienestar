<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->string('prioridad', 10)->default('Baja')->after('estado');
            $table->string('etiqueta', 80)->nullable()->after('prioridad');
            $table->text('recomendaciones')->nullable()->after('etiqueta');
            $table->text('motivacion')->nullable()->after('recomendaciones');
            $table->text('gestion_admin')->nullable()->after('motivacion');
            $table->boolean('alerta')->default(false)->after('gestion_admin');
            $table->boolean('analizado')->default(false)->after('alerta');
        });
    }

    public function down(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropColumn(['prioridad', 'etiqueta', 'recomendaciones', 'motivacion', 'gestion_admin', 'alerta', 'analizado']);
        });
    }
};