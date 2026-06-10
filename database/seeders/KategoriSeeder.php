<?php

/** @author Silva Tria Alfares - 254107023001 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['nama_kategori' => 'Alat Masak', 'slug' => 'alat-masak'],
            ['nama_kategori' => 'Perabotan', 'slug' => 'perabotan'],
            ['nama_kategori' => 'Elektronik', 'slug' => 'elektronik'],
            ['nama_kategori' => 'Perlengkapan Kamar', 'slug' => 'perlengkapan-kamar'],
        ];

        foreach ($kategoris as $kategori) {
            DB::table('kategoris')->insert(array_merge($kategori, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
