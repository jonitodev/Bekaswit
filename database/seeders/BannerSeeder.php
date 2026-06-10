<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'eyebrow'    => 'Edisi Pekan Ini',
                'title'      => 'Obral <em>Perabotan</em> Antik',
                'subtitle'   => 'Kursi, meja, dan rak kayu jati pilihan dari indekos di Malang.',
                'tag'        => '-60%',
                'cta_text'   => 'Lihat Koleksi',
                'cta_link'   => '/cari?kategori=perabotan',
                'image_path' => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=1800&h=900&fit=crop',
                'sort_order' => 1,
            ],
            [
                'eyebrow'    => 'Koleksi Bekas Terbaru',
                'title'      => '<em>Busana</em> Era 90-an',
                'subtitle'   => 'Celana jin, jaket, dan kaus klasik dengan cerita tersendiri.',
                'tag'        => 'Baru',
                'cta_text'   => 'Belanja Sekarang',
                'cta_link'   => '/cari?kategori=busana',
                'image_path' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=1800&h=900&fit=crop',
                'sort_order' => 2,
            ],
            [
                'eyebrow'    => 'Harta Tersembunyi',
                'title'      => '<em>Buku</em> & Piringan Hitam Langka',
                'subtitle'   => 'Buku sastra langka dan piringan hitam klasik mulai dari Rp 25 ribu.',
                'tag'        => 'Langka',
                'cta_text'   => 'Jelajahi',
                'cta_link'   => '/cari?kategori=buku',
                'image_path' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=1800&h=900&fit=crop',
                'sort_order' => 3,
            ],
            [
                'eyebrow'    => 'Sudut Elektronik',
                'title'      => '<em>Elektronik</em> Retro',
                'subtitle'   => 'Polaroid, pemutar kaset, hingga kipas Maspion legendaris.',
                'tag'        => 'Populer',
                'cta_text'   => 'Lihat Semua',
                'cta_link'   => '/cari?kategori=elektronik',
                'image_path' => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=1800&h=900&fit=crop',
                'sort_order' => 4,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create(array_merge($banner, ['is_active' => true]));
        }
    }
}
