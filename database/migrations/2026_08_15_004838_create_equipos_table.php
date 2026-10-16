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
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('centros_de_computo_id')->constrained('centros_de_computo');
            $table->string('codigo_inventario', 50)->unique();

            $table->enum('tipo', ['desktop', 'laptop', 'router', 'switch', 'servidor', 'impresora']);
            $table->string('marca', 50)->nullable();
            $table->string('modelo', 50)->nullable();
            $table->string('numero_serie', 100)->nullable();

            $table->enum('estado', ['operativo', 'mantenimiento', 'averiado', 'baja_definitiva'])->default('operativo');

            $table->integer('dias_frecuencia_mantenimiento')->default(180);
            $table->date('fecha_ultimo_mantenimiento')->nullable();

            $table->foreignId('registrado_por')->nullable()->constrained('usuarios');
            $table->foreignId('eliminado_por')->nullable()->constrained('usuarios');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};
