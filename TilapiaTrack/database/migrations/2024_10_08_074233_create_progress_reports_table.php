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
        Schema::create('progress_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_harvest', 10, 2); // Total fish harvested in kilograms
            $table->decimal('total_feed_consumed', 10, 2); // Total feed consumed in kilograms
            $table->decimal('total_feed_cost', 10, 2); // Total cost of feed consumed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_reports');
    }
};
