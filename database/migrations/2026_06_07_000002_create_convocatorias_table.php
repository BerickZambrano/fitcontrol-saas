<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('convocatorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partido_id')->constrained('partidos')->cascadeOnDelete();
            $table->foreignId('jugador_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('equipo_id')->constrained('equipos')->cascadeOnDelete();
            $table->enum('estado_asistencia', ['convocado', 'confirmado', 'rechazado', 'asistio', 'falto'])->default('convocado');
            $table->timestamps();
            $table->softDeletes();
            
            // Un jugador no puede ser convocado dos veces al mismo partido
            $table->unique(['partido_id', 'jugador_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('convocatorias');
    }
};
