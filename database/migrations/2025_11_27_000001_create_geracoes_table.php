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
        Schema::create('geracoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cor')->nullable(); // Ex: Azul Celeste, Bege, Branca, etc.
            $table->string('responsaveis')->nullable(); // Nomes dos responsáveis
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geracoes');
    }
};
