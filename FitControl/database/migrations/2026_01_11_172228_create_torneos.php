<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('torneos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('nombre');
            $table->string('categoria')->nullable(); // juvenil, sub-15, etc
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->string('estado')->default('activo'); // activo, finalizado

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('torneos');
    }
};
