<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estudos_celula', function (Blueprint $table) {
            $table->id();
            $table->string('tema');
            $table->date('data');
            $table->string('pdf_path')->nullable();
            $table->string('youtube_url')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudos_celula');
    }
};
