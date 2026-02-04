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
        Schema::create('victims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crime_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->integer('age')->nullable();
            $table->string('gender')->nullable(); // Male, Female
            $table->text('injury_type')->nullable(); // Dhaawaca, Dhimashada, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('victims');
    }
};
