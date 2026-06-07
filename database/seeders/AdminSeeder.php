<?php

/** @author Silva Tria Alfares - 254107023001 */

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bekaswit.com'],
            [
                'nama'     => 'Admin Bekaswit',
                'password' => Hash::make('admin123'),
                'no_wa'    => '081234567890',
                'area_id'  => 1,
                'role'     => 'admin',
            ]
        );
    }
}
