<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            $table->foreignId('arbitro_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('estado_arbitro', ['pendiente', 'aceptado', 'rechazado'])->default('pendiente');
            $table->enum('estado_partido', ['programado', 'en_juego', 'finalizado'])->default('programado');
        });
    }

    public function down(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            $table->dropForeign(['arbitro_id']);
            $table->dropColumn(['arbitro_id', 'estado_arbitro', 'estado_partido']);
        });
    }
};
