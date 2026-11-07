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
        Schema::create('audio_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('filename');
            $table->string('original_filename');
            $table->string('file_path');
            $table->string('file_type')->default('audio/mpeg');
            $table->bigInteger('file_size'); // in bytes
            $table->integer('duration')->nullable(); // in seconds
            $table->text('transcription')->nullable();
            $table->json('metadata')->nullable(); // for additional audio metadata
            $table->boolean('is_favorite')->default(false);
            $table->timestamp('last_played_at')->nullable();
            $table->integer('play_count')->default(0);
            $table->timestamps();

            // Indexes for better performance
            $table->index(['user_id', 'created_at']);
            $table->index(['user_id', 'last_played_at']);
            $table->index(['user_id', 'is_favorite']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audio_files');
    }
};
