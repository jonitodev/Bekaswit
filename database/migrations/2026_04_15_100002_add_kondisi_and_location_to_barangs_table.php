<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->enum('kondisi', ['like-new', 'good', 'fair'])->default('good')->after('status');
            $table->decimal('latitude', 10, 8)->nullable()->after('area_id');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
    }

    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['kondisi', 'latitude', 'longitude']);
        });
    }
};
