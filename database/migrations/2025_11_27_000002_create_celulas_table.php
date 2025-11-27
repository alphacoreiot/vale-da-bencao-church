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
        Schema::create('celulas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('geracao_id')->constrained('geracoes')->onDelete('cascade');
            $table->string('nome')->nullable(); // Nome da célula (quando informado)
            $table->string('lider'); // Nome do líder/casal líder
            $table->string('bairro')->nullable();
            $table->string('ponto_referencia')->nullable();
            $table->string('contato')->nullable();
            $table->string('dia_semana')->nullable(); // Dia da semana da reunião
            $table->time('horario')->nullable(); // Horário da reunião
            $table->text('observacoes')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('celulas');
    }
};
