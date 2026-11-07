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
        Schema::create('dog_breeds', function (Blueprint $table) {
            $table->id();
            $table->string('breed_name')->unique();
            $table->string('bark_characteristics');
            $table->string('response_voice_style');
            $table->string('frequency');
            $table->integer('analysis_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dog_breeds');
    }
};
