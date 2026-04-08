<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('entrenamientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('equipo_id')->constrained()->cascadeOnDelete();
            $table->string('nombre');

            $table->date('fecha');
            $table->time('hora');
            $table->string('ubicacion');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entrenamientos');
    }
};
