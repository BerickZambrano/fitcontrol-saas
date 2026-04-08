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
        // Tabla equipos
        Schema::table('equipos', function (Blueprint $table) {
            $table->index('categoria');
            $table->index('tenant_id');
        });

        // Tabla torneos
        Schema::table('torneos', function (Blueprint $table) {
            $table->index('estado');
            $table->index('tenant_id');
        });

        // Tabla historial_medico
        Schema::table('historial_medico', function (Blueprint $table) {
            $table->index('apto');
            $table->index('tenant_id');
        });

        // Tabla pagos
        Schema::table('pagos', function (Blueprint $table) {
            $table->index('fecha');
            $table->index('tenant_id');
        });

        // Tabla asistencia_entrenamiento
        Schema::table('asistencia_entrenamiento', function (Blueprint $table) {
            $table->index('presente');
            $table->index('user_id');
            $table->index(['entrenamiento_id', 'user_id']);
        });

        // Tabla entrenamientos
        Schema::table('entrenamientos', function (Blueprint $table) {
            $table->index('fecha');
            $table->index('tenant_id');
        });

        // Tabla users (roles)
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->index(['model_type', 'model_id']);
        });

        // Tabla notificaciones
        Schema::table('notificaciones', function (Blueprint $table) {
            $table->index('leida');
            $table->index('tenant_id');
        });

        // Tabla rendimientos
        Schema::table('rendimientos', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('tenant_id');
        });

        // Tabla partidos
        Schema::table('partidos', function (Blueprint $table) {
            $table->index('equipo_local_id');
            $table->index('equipo_visitante_id');
            $table->index('tenant_id');
        });

        // Tabla equipo_user
        Schema::table('equipo_user', function (Blueprint $table) {
            $table->index('equipo_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            $table->dropIndex(['categoria']);
            $table->dropIndex(['tenant_id']);
        });

        Schema::table('torneos', function (Blueprint $table) {
            $table->dropIndex(['estado']);
            $table->dropIndex(['tenant_id']);
        });

        Schema::table('historial_medico', function (Blueprint $table) {
            $table->dropIndex(['apto']);
            $table->dropIndex(['tenant_id']);
        });

        Schema::table('pagos', function (Blueprint $table) {
            $table->dropIndex(['fecha']);
            $table->dropIndex(['tenant_id']);
        });

        Schema::table('asistencia_entrenamiento', function (Blueprint $table) {
            $table->dropIndex(['presente']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['entrenamiento_id', 'user_id']);
        });

        Schema::table('entrenamientos', function (Blueprint $table) {
            $table->dropIndex(['fecha']);
            $table->dropIndex(['tenant_id']);
        });

        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->dropIndex(['model_type', 'model_id']);
        });

        Schema::table('notificaciones', function (Blueprint $table) {
            $table->dropIndex(['leida']);
            $table->dropIndex(['tenant_id']);
        });

        Schema::table('rendimientos', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['tenant_id']);
        });

        Schema::table('partidos', function (Blueprint $table) {
            $table->dropIndex(['equipo_local_id']);
            $table->dropIndex(['equipo_visitante_id']);
            $table->dropIndex(['tenant_id']);
        });

        Schema::table('equipo_user', function (Blueprint $table) {
            $table->dropIndex(['equipo_id']);
            $table->dropIndex(['user_id']);
        });
    }
};
