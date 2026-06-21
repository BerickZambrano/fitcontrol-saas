<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rendimientos', function (Blueprint $table) {
            $table->decimal('evaluacion', 3, 1)->default(0.0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rendimientos', function (Blueprint $table) {
            $table->dropColumn('evaluacion');
        });
    }
};
