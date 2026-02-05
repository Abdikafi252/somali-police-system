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
        Schema::create('station_officers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('officer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('station_id')->constrained('stations')->onDelete('cascade');
            $table->foreignId('commander_id')->constrained('station_commanders')->onDelete('cascade');
            $table->string('rank')->nullable();
            $table->string('duty_type')->nullable(); // patrol / guard / investigation
            $table->date('assigned_date')->nullable();
            $table->string('status')->default('active'); // active / transferred / retired
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('station_officers');
    }
};
