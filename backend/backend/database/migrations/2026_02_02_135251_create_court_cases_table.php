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
        Schema::create('court_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prosecution_id')->constrained('prosecutions');
            $table->foreignId('judge_id')->constrained('users');
            $table->dateTime('hearing_date')->nullable();
            $table->text('verdict')->nullable();
            $table->string('status')->default('open'); // open, adjourned, closed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('court_cases');
    }
};
