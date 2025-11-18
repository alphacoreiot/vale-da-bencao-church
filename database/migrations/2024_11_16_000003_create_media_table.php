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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            $table->foreignId('content_id')->nullable()->constrained('section_contents')->onDelete('cascade');
            $table->enum('type', ['image', 'video', 'audio']);
            $table->string('path');
            $table->string('thumbnail')->nullable();
            $table->bigInteger('size')->default(0)->comment('Size in bytes');
            $table->string('mime_type');
            $table->string('alt_text')->nullable();
            $table->timestamps();
            
            $table->index(['section_id', 'type']);
            $table->index('content_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
