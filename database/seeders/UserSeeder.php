<?php

/** @author Silva Tria Alfares - 254107023001 */
// test from alfa

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'nama'    => 'Andi Pratama',
                'email'   => 'andi@example.com',
                'no_wa'   => '081234567001',
                'area_id' => 1, // Lowokwaru
            ],
            [
                'nama'    => 'Budi Santoso',
                'email'   => 'budi@example.com',
                'no_wa'   => '081234567002',
                'area_id' => 2, // Klojen
            ],
            [
                'nama'    => 'Citra Dewi',
                'email'   => 'citra@example.com',
                'no_wa'   => '081234567003',
                'area_id' => 3, // Blimbing
            ],
            [
                'nama'    => 'Dani Setiawan',
                'email'   => 'dani@example.com',
                'no_wa'   => '081234567004',
                'area_id' => 4, // Sukun
            ],
            [
                'nama'    => 'Eka Putri',
                'email'   => 'eka@example.com',
                'no_wa'   => '081234567005',
                'area_id' => 5, // Kedungkandang
            ],
        ];

        foreach ($users as $user) {
            User::create(array_merge($user, [
                'password' => Hash::make('password'),
            ]));
        }
    }
}
