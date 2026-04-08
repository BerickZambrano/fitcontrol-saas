<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('instalaciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();

            $table->string('nombre');
            $table->string('tipo'); // cancha, gimnasio, piscina
            $table->string('ubicacion')->nullable();
            $table->integer('capacidad')->nullable();
            $table->string('estado'); // disponible, ocupada, mantenimiento

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instalaciones');
    }
};
