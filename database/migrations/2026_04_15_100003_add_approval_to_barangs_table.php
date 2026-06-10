<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('kondisi');
            $table->string('rejected_reason', 255)->nullable()->after('approval_status');
            $table->timestamp('reviewed_at')->nullable()->after('rejected_reason');
        });
    }

    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['approval_status', 'rejected_reason', 'reviewed_at']);
        });
    }
};
