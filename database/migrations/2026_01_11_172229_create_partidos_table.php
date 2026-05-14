<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('partidos', function (Blueprint $table) {
            $table->id();

            // Multi-tenant
            $table->foreignId('tenant_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🔹 FK AL TORNEO (ESTE ES EL QUE FALTABA)
            $table->foreignId('torneo_id')
                ->constrained('torneos')
                ->cascadeOnDelete();

            // Equipos
            $table->foreignId('equipo_local_id')
                ->constrained('equipos')
                ->cascadeOnDelete();

            $table->foreignId('equipo_visitante_id')
                ->constrained('equipos')
                ->cascadeOnDelete();

            // Datos del partido
            $table->date('fecha');
            $table->time('hora');
            $table->string('resultado')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partidos');
    }
};
