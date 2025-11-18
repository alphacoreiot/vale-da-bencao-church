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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(5);
            $table->integer('display_order')->default(0);
            $table->integer('highlight_duration')->default(60)->comment('Duration in minutes');
            $table->timestamp('last_highlighted_at')->nullable();
            $table->timestamp('next_highlight_at')->nullable();
            $table->json('ai_agent_config')->nullable();
            $table->timestamps();
            
            $table->index(['is_active', 'priority']);
            $table->index('last_highlighted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
