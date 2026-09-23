<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Añade el rol y los datos de perfil del aprendiz a la tabla users.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('aprendiz')->after('email');
            $table->string('documento', 50)->nullable()->after('role');
            $table->string('programa')->nullable()->after('documento');
            $table->string('ficha', 50)->nullable()->after('programa');

            $table->index('role');
        });

        // La única cuenta de administrador conserva su rol tras la migración.
        DB::table('users')
            ->where('email', 'admin@bienestar.com')
            ->update(['role' => 'admin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropColumn(['role', 'documento', 'programa', 'ficha']);
        });
    }
};