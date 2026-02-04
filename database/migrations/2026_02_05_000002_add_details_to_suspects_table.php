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
            if (!Schema::hasColumn('suspects', 'nickname')) {
                $table->string('nickname')->nullable()->after('name');
            }
            if (!Schema::hasColumn('suspects', 'mother_name')) {
                $table->string('mother_name')->nullable()->after('age');
            }
            if (!Schema::hasColumn('suspects', 'address')) {
                $table->string('address')->nullable()->after('gender');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suspects', function (Blueprint $table) {
            $table->dropColumn(['nickname', 'mother_name', 'address']);
        });
    }
};
