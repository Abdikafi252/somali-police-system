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
        Schema::table('prosecutions', function (Blueprint $table) {
            $table->foreignId('court_id')->after('prosecutor_id')->nullable()->constrained('facilities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prosecutions', function (Blueprint $table) {
            //
        });
    }
};
