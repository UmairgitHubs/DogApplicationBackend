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
        Schema::create('supported_languages', function (Blueprint $table) {
            $table->id();
            $table->string('language')->unique();
            $table->string('code')->unique();
            $table->boolean('status')->default(false);
            $table->string('translation_file')->nullable();
            $table->integer('usage_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supported_languages');
    }
};
