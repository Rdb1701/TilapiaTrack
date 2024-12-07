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
        Schema::create('feeding_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feed_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('age_range')->nullable();
            $table->string('feeding_frequency')->nullable();
            $table->string('feed_time')->nullable();
            $table->string('fish_amount')->nullable();
            $table->string('fish_size')->nullable();
            $table->string('protein_content')->nullable();
            $table->string('feed_type_image')->nullable();
            $table->string('typical_weight_range')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feeding_programs');
    }
};
