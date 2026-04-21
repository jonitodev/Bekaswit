<?php

/** @author Silva Tria Alfares - 254107023001 */
// test from alfa

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['seller', 'admin'])->default('seller')->after('area_id');
            $table->boolean('is_blocked')->default(false)->after('role');
            $table->timestamp('blocked_at')->nullable()->after('is_blocked');
            $table->string('blocked_reason', 255)->nullable()->after('blocked_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_blocked', 'blocked_at', 'blocked_reason']);
        });
    }
};
