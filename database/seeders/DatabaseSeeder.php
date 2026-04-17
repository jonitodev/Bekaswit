<?php

/** @author Silva Tria Alfares - 254107023001 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AreaSeeder::class,
            KategoriSeeder::class,
            UserSeeder::class,
            BarangSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
