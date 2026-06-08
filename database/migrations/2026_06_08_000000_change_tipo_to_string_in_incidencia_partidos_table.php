<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incidencia_partidos', function (Blueprint $table) {
            $table->string('tipo')->change();
        });
    }

    public function down(): void
    {
        Schema::table('incidencia_partidos', function (Blueprint $table) {
            $table->enum('tipo', ['amarilla', 'roja', 'lesion', 'observacion'])->change();
        });
    }
};
