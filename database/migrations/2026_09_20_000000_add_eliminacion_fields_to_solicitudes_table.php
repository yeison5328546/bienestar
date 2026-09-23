<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->string('eliminacion_estado', 20)->nullable()->after('analizado');
            $table->text('eliminacion_motivo')->nullable()->after('eliminacion_estado');
            $table->timestamp('eliminacion_solicitada_at')->nullable()->after('eliminacion_motivo');
            $table->timestamp('eliminacion_decidida_at')->nullable()->after('eliminacion_solicitada_at');
        });
    }

    public function down(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropColumn([
                'eliminacion_estado',
                'eliminacion_motivo',
                'eliminacion_solicitada_at',
                'eliminacion_decidida_at',
            ]);
        });
    }
};