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
        Schema::create('harvests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fingerling_id')->constrained()->onDelete('cascade'); // Foreign key to fingerlings
            $table->date('harvest_date');
            $table->decimal('total_harvest', 10, 2); // Total kilograms of fish harvested
            $table->string('image_path')->nullable(); // Path to an image of the harvest
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harvests');
    }
};
