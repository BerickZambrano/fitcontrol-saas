<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            $table->string('fase')->nullable()->after('resultado')
                  ->comment('Fase del torneo: grupo, octavos, cuartos, semifinal, final, etc.');
        });
    }

    public function down(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            $table->dropColumn('fase');
        });
    }
};
