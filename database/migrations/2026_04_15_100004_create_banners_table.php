<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('eyebrow', 80)->nullable();
            $table->string('title', 150);
            $table->string('subtitle', 255)->nullable();
            $table->string('tag', 30)->nullable();
            $table->string('cta_text', 50)->nullable();
            $table->string('cta_link', 255)->nullable();
            $table->string('image_path', 255);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
