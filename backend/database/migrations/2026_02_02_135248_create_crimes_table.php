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
        Schema::create('crimes', function (Blueprint $table) {
            $table->id();
            $table->string('case_number')->unique();
            $table->string('crime_type')->nullable(); // Matches spec
            $table->text('description');
            $table->string('location');
            $table->dateTime('crime_date'); // Matches spec
            $table->foreignId('reported_by')->constrained('users'); // Matches spec
            $table->string('status')->default('pending'); // pending, investigating, closed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crimes');
    }
};
