<?php

/** @author Silva Tria Alfares - 254107023001 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_barang', 150);
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2);
            $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('restrict');
            $table->enum('status', ['tersedia', 'booking', 'terjual'])->default('tersedia');
            $table->foreignId('area_id')->constrained('areas')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
