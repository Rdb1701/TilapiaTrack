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
        Schema::create('feed_consumptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fingerling_id')->constrained()->onDelete('cascade'); // Foreign key to fingerlings
            $table->foreignId('feed_id')->constrained()->onDelete('cascade'); // Foreign key to feeds
            $table->decimal('quantity', 10, 2); // Quantity of feed consumed in kilograms
            $table->date('consumption_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_consumptions');
    }
};
