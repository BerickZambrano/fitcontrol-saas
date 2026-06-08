<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE incidencia_partidos DROP CONSTRAINT IF EXISTS incidencia_partidos_tipo_check;');
    }

    public function down(): void
    {
        // No se requiere revertir el drop del constraint ya que queremos mantener la columna tipo como string
    }
};
