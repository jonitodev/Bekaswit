<?php

/** @author Silva Tria Alfares - 254107023001 */
// test from alfa

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            ['nama_kecamatan' => 'Lowokwaru', 'kota' => 'Malang'],
            ['nama_kecamatan' => 'Klojen', 'kota' => 'Malang'],
            ['nama_kecamatan' => 'Blimbing', 'kota' => 'Malang'],
            ['nama_kecamatan' => 'Sukun', 'kota' => 'Malang'],
            ['nama_kecamatan' => 'Kedungkandang', 'kota' => 'Malang'],
        ];

        foreach ($areas as $area) {
            DB::table('areas')->insert(array_merge($area, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
