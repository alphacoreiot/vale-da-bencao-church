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
        Schema::create('rotation_configs', function (Blueprint $table) {
            $table->id();
            $table->enum('rotation_type', ['circular', 'priority', 'scheduled', 'random'])->default('priority');
            $table->integer('interval_minutes')->default(60);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rotation_configs');
    }
};
