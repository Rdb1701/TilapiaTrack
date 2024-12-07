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
        Schema::create('fingerlings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fishpond_id')->constrained()->onDelete('cascade'); // Foreign key to fishponds
            $table->string('species');
            $table->date('date_deployed');
            $table->integer('quantity'); // Number of fingerlings deployed
            $table->string('weight');
            $table->string('feed_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fingerlings');
    }
};
