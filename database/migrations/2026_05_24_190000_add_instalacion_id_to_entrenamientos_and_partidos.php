<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entrenamientos', function (Blueprint $table) {
            $table->foreignId('instalacion_id')
                  ->nullable()
                  ->after('equipo_id')
                  ->constrained('instalaciones')
                  ->nullOnDelete();
        });

        Schema::table('partidos', function (Blueprint $table) {
            $table->foreignId('instalacion_id')
                  ->nullable()
                  ->after('torneo_id')
                  ->constrained('instalaciones')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('entrenamientos', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Instalacion::class);
            $table->dropColumn('instalacion_id');
        });

        Schema::table('partidos', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Instalacion::class);
            $table->dropColumn('instalacion_id');
        });
    }
};
