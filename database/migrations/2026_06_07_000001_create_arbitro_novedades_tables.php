<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidencia_partidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partido_id')->constrained('partidos')->cascadeOnDelete();
            $table->foreignId('jugador_id')->constrained('users')->cascadeOnDelete();
            $table->enum('tipo', ['amarilla', 'roja', 'lesion', 'observacion']);
            $table->integer('minuto')->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sanciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jugador_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('partido_id_origen')->constrained('partidos')->cascadeOnDelete();
            $table->integer('cantidad_partidos_suspension');
            $table->integer('partidos_cumplidos')->default(0);
            $table->enum('estado', ['activa', 'cumplida'])->default('activa');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sanciones');
        Schema::dropIfExists('incidencia_partidos');
    }
};
