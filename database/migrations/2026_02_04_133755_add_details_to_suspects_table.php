<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suspects', function (Blueprint $table) {
            $table->string('nickname')->nullable()->after('name'); // Naaneysta
            $table->string('mother_name')->nullable()->after('age'); // Hooyada
            $table->string('residence')->nullable()->after('gender'); // Deggan
        });
    }

    public function down(): void
    {
        Schema::table('suspects', function (Blueprint $table) {
            $table->dropColumn(['nickname', 'mother_name', 'residence']);
        });
    }
};
