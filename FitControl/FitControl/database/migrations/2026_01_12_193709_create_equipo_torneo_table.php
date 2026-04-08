<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('equipo_torneo', function (Blueprint $table) {
            $table->id();

            // Multi-tenant
            $table->foreignId('tenant_id')
                ->constrained()
                ->cascadeOnDelete();

            // FK torneo
            $table->foreignId('torneo_id')
                ->constrained('torneos')
                ->cascadeOnDelete();

            // FK equipo
            $table->foreignId('equipo_id')
                ->constrained('equipos')
                ->cascadeOnDelete();

            $table->timestamps();

            // Evita duplicados (mismo equipo en mismo torneo)
            $table->unique(['torneo_id', 'equipo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipo_torneo');
    }
};
