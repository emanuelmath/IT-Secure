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
        Schema::create('historial_traslados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')->constrained('equipos');
            $table->foreignId('cc_origen_id')->constrained('centros_de_computo');
            $table->foreignId('cc_destino_id')->constrained('centros_de_computo');
            $table->foreignId('autorizado_por')->constrained('usuarios');

            $table->date('fecha_traslado');
            $table->text('motivo');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_traslados');
    }
};
