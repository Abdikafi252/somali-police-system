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
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crime_id')->constrained('crimes');
            $table->string('case_number')->unique();
            $table->foreignId('assigned_to')->nullable()->constrained('users'); // CID Sarkaalka
            $table->string('status')->default('assigned'); // assigned, investigation, prosecution, court, closed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
