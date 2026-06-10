<?php

/** @author Silva Tria Alfares - 254107023001 */

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\FotoBarang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $barangs = [
            [
                'user_id'     => 1,
                'nama_barang' => 'Kipas Angin Maspion',
                'deskripsi'   => 'Kipas angin meja Maspion 12 inch, masih berfungsi normal. Cocok untuk kamar kos.',
                'harga'       => 75000,
                'kategori_id' => 3, // Elektronik
                'area_id'     => 1, // Lowokwaru
            ],
            [
                'user_id'     => 1,
                'nama_barang' => 'Rice Cooker Cosmos 1.2L',
                'deskripsi'   => 'Rice cooker Cosmos kapasitas 1.2 liter. Pemakaian 6 bulan, mau pindah kos.',
                'harga'       => 120000,
                'kategori_id' => 1, // Alat Masak
                'area_id'     => 1,
            ],
            [
                'user_id'     => 2,
                'nama_barang' => 'Meja Belajar Lipat',
                'deskripsi'   => 'Meja belajar lipat kayu, ukuran 60x40cm. Ringan dan mudah disimpan.',
                'harga'       => 85000,
                'kategori_id' => 2, // Furniture
                'area_id'     => 2, // Klojen
            ],
            [
                'user_id'     => 2,
                'nama_barang' => 'Setrika Philips',
                'deskripsi'   => 'Setrika Philips GC1418, kondisi 90%. Anti lengket.',
                'harga'       => 95000,
                'kategori_id' => 3, // Elektronik
                'area_id'     => 2,
            ],
            [
                'user_id'     => 3,
                'nama_barang' => 'Rak Buku 4 Tingkat',
                'deskripsi'   => 'Rak buku kayu 4 tingkat, kokoh dan rapi. Bisa untuk buku atau barang kos.',
                'harga'       => 150000,
                'kategori_id' => 2, // Furniture
                'area_id'     => 3, // Blimbing
            ],
            [
                'user_id'     => 3,
                'nama_barang' => 'Kompor Gas Portable',
                'deskripsi'   => 'Kompor gas portable 1 tungku, cocok untuk masak di kos. Tabung tidak termasuk.',
                'harga'       => 65000,
                'kategori_id' => 1, // Alat Masak
                'area_id'     => 3,
            ],
            [
                'user_id'     => 4,
                'nama_barang' => 'Kasur Lipat Single',
                'deskripsi'   => 'Kasur lipat single 90x200cm, busa tebal 8cm. Nyaman untuk kos.',
                'harga'       => 200000,
                'kategori_id' => 4, // Perlengkapan Kamar
                'area_id'     => 4, // Sukun
            ],
            [
                'user_id'     => 4,
                'nama_barang' => 'Lampu Belajar LED',
                'deskripsi'   => 'Lampu meja LED dengan 3 mode cahaya. Hemat listrik, cocok buat belajar.',
                'harga'       => 45000,
                'kategori_id' => 3, // Elektronik
                'area_id'     => 4,
            ],
            [
                'user_id'     => 5,
                'nama_barang' => 'Wajan Anti Lengket 24cm',
                'deskripsi'   => 'Wajan anti lengket diameter 24cm, merk Maxim. Masih bagus.',
                'harga'       => 35000,
                'kategori_id' => 1, // Alat Masak
                'area_id'     => 5, // Kedungkandang
            ],
            [
                'user_id'     => 5,
                'nama_barang' => 'Kursi Plastik Napolly',
                'deskripsi'   => 'Kursi plastik Napolly warna putih, kuat dan ringan.',
                'harga'       => 25000,
                'kategori_id' => 2, // Furniture
                'area_id'     => 5,
            ],
            [
                'user_id'     => 1,
                'nama_barang' => 'Dispenser Miyako Hot & Normal',
                'deskripsi'   => 'Dispenser Miyako 2 kran (panas & normal). Pemakaian 1 tahun.',
                'harga'       => 135000,
                'kategori_id' => 3, // Elektronik
                'area_id'     => 1,
            ],
            [
                'user_id'     => 3,
                'nama_barang' => 'Gorden Kamar 2x1.5m',
                'deskripsi'   => 'Gorden kamar warna biru navy, ukuran 2x1.5 meter. Bahan tebal anti sinar.',
                'harga'       => 55000,
                'kategori_id' => 4, // Perlengkapan Kamar
                'area_id'     => 3,
            ],
        ];

        // Titik kecamatan di Malang untuk demo peta (per area_id)
        $coords = [
            1 => [-7.93890, 112.60970], // Lowokwaru
            2 => [-7.98190, 112.62650], // Klojen
            3 => [-7.94300, 112.65200], // Blimbing
            4 => [-7.99300, 112.60800], // Sukun
            5 => [-7.99900, 112.66600], // Kedungkandang
        ];

        $kondisiOptions = ['like-new', 'good', 'fair'];

        foreach ($barangs as $index => $data) {
            [$lat, $lng] = $coords[$data['area_id']] ?? [-7.96660, 112.63260];

            Barang::create(array_merge($data, [
                'status'          => 'tersedia',
                'kondisi'         => $kondisiOptions[$index % 3],
                'latitude'        => $lat,
                'longitude'       => $lng,
                'approval_status' => 'approved',
                'reviewed_at'     => now(),
            ]));
        }
    }
}
