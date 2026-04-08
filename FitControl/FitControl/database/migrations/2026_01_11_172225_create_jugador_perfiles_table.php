<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jugador_perfiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

            $table->string('posicion')->nullable();
            $table->integer('dorsal')->nullable();
            $table->decimal('altura', 5, 2)->nullable();
            $table->decimal('peso', 5, 2)->nullable();
            $table->string('pierna_habil')->nullable(); // PostgreSQL: enum → string (derecha, izquierda, ambas)

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jugador_perfiles');
    }
};
