<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Añade el índice faltante en rendimientos.partido_id.
     * El reporte de rendimiento hace JOIN entre rendimientos y partidos
     * usando este campo — sin índice hace full-table-scan.
     */
    public function up(): void
    {
        Schema::table('rendimientos', function (Blueprint $table) {
            $table->index('partido_id', 'rendimientos_partido_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rendimientos', function (Blueprint $table) {
            $table->dropIndex('rendimientos_partido_id_index');
        });
    }
};
