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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('email')->unique();
            $table->timestamp('email_verificado_hace')->nullable();
            $table->string('contrasenha');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('contrasenha_tokens_resetear', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('creado_hace')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('contrasenha_tokens_resetear');
        Schema::dropIfExists('sessions');
    }
};
