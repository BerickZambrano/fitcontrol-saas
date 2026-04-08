<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();

            $table->string('nombre');
            $table->string('categoria'); // PostgreSQL: enum → string (profesional, amateur, formativo)

            $table->string('logo_equipo')->nullable();
            $table->string('ubi_equipo')->nullable();
            $table->string('contacto_equipo')->nullable();
            $table->string('categoria_equipo')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};
