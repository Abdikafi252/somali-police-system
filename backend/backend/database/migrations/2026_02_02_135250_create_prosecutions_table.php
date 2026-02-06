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
        Schema::create('prosecutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases');
            $table->foreignId('prosecutor_id')->constrained('users');
            $table->dateTime('submission_date');
            $table->text('charges');
            $table->string('status')->default('submitted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prosecutions');
    }
};
