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
        Schema::create('deployments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('station_id')->nullable()->constrained('stations');
            $table->foreignId('facility_id')->nullable()->constrained('facilities');
            $table->string('duty_type')->nullable(); // patrol, guard, investigation
            $table->string('shift'); // Morning, Evening, Night
            $table->string('status')->default('on_duty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deployments');
    }
};
