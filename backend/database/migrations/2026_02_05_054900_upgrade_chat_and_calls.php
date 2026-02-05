<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->string('type')->default('text')->after('message'); // text, image, video, audio
            $table->string('file_path')->nullable()->after('type');
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
        });

        Schema::table('calls', function (Blueprint $table) {
            $table->string('call_type')->default('audio')->after('status'); // audio, video
            $table->integer('duration')->default(0)->after('call_type');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['type', 'file_path', 'delivered_at', 'read_at']);
        });

        Schema::table('calls', function (Blueprint $table) {
            $table->dropColumn(['call_type', 'duration']);
        });
    }
};
