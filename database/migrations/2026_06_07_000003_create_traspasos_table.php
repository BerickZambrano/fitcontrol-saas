<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('traspasos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jugador_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('equipo_origen_id')->constrained('equipos')->cascadeOnDelete();
            $table->foreignId('equipo_destino_id')->constrained('equipos')->cascadeOnDelete();
            $table->enum('estado', ['pendiente', 'aceptado', 'rechazado'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('traspasos');
    }
};
