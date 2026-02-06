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
        Schema::table('suspects', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('arrest_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suspects', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }
};
