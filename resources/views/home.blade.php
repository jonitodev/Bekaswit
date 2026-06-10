{{-- @author Gilang Bayu Irwana - 244107020194 --}}
@extends('layouts.app')

@section('title', 'Bekaswit — Temukan Barang Bekas Pilihan Anda')

@section('content')

    {{-- ───── Hero Banner Slider (full-width) ───── --}}
    @php
        // Banner dikelola admin lewat panel. Jika kosong, gunakan slide bawaan.
        $heroSlides = [];

        if (isset($banners) && $banners->isNotEmpty()) {
            foreach ($banners as $banner) {
                $link = $banner->cta_link ?: route('barang.search');
                if ($link && ! \Illuminate\Support\Str::startsWith($link, ['http://', 'https://'])) {
                    $link = url($link);
                }
                $heroSlides[] = [
                    'eyebrow' => $banner->eyebrow,
                    'title'   => $banner->title,
                    'sub'     => $banner->subtitle,
                    'cta'     => $banner->cta_text ?: 'Lihat Selengkapnya',
                    'href'    => $link,
                    'tag'     => $banner->tag,
                    'image'   => $banner->image_url,
                ];
            }
        }

        $fallbackSlides = [
            [
                'eyebrow' => 'Edisi Pekan Ini',
                'title'   => "Obral <em>Perabotan</em> Antik",
                'sub'     => 'Kursi, meja, dan rak kayu jati pilihan dari indekos di Malang.',
                'cta'     => 'Lihat Koleksi',
                'href'    => route('barang.search') . '?kategori=perabotan',
                'tag'     => '-60%',
                'image'   => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=1800&h=900&fit=crop',
            ],
            [
                'eyebrow' => 'Koleksi Bekas Terbaru',
                'title'   => "<em>Busana</em> Era 90-an",
                'sub'     => 'Celana jin, jaket, dan kaus klasik dengan cerita tersendiri.',
                'cta'     => 'Belanja Sekarang',
                'href'    => route('barang.search') . '?kategori=busana',
                'tag'     => 'Baru',
                'image'   => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=1800&h=900&fit=crop',
            ],
            [
                'eyebrow' => 'Harta Tersembunyi',
                'title'   => "<em>Buku</em> & Piringan Hitam Langka",
                'sub'     => 'Buku sastra langka dan piringan hitam klasik mulai dari Rp 25 ribu.',
                'cta'     => 'Jelajahi',
                'href'    => route('barang.search') . '?kategori=buku',
                'tag'     => 'Langka',
                'image'   => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=1800&h=900&fit=crop',
            ],
            [
                'eyebrow' => 'Sudut Elektronik',
                'title'   => "<em>Elektronik</em> Retro",
                'sub'     => 'Polaroid, pemutar kaset, hingga kipas Maspion legendaris.',
                'cta'     => 'Lihat Semua',
                'href'    => route('barang.search') . '?kategori=elektronik',
                'tag'     => 'Populer',
                'image'   => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=1800&h=900&fit=crop',
            ],
        ];

        $heroSlides = $heroSlides ?: $fallbackSlides;
    @endphp

    <section class="hero-shop hero-shop--banner">
        <div class="container">
            <div class="hero-slider hero-slider--wide" id="heroSlider" aria-roledescription="carousel">
                <div class="hero-slider-track">
                    @foreach($heroSlides as $i => $slide)
                        <div class="hero-slide {{ $i === 0 ? 'is-active' : '' }}"
                             style="background-image: url('{{ $slide['image'] }}');"
                             role="group"
                             aria-roledescription="slide"
                             aria-label="{{ $i + 1 }} dari {{ count($heroSlides) }}">
                            @if(!empty($slide['tag']))
                                <span class="hero-slide-tag">{{ $slide['tag'] }}</span>
                            @endif
                            <div class="hero-slide-content">
                                <span class="hero-slide-eyebrow">{{ $slide['eyebrow'] }}</span>
                                <h3 class="hero-slide-title">{!! $slide['title'] !!}</h3>
                                <p class="hero-slide-sub">{{ $slide['sub'] }}</p>
                                <a href="{{ $slide['href'] }}" class="hero-slide-cta">
                                    {{ $slide['cta'] }} <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="hero-slider-nav prev" aria-label="Slide sebelumnya">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button type="button" class="hero-slider-nav next" aria-label="Slide berikutnya">
                    <i class="bi bi-chevron-right"></i>
                </button>

                <div class="hero-slider-dots" role="tablist">
                    @foreach($heroSlides as $i => $slide)
                        <button type="button"
                                class="{{ $i === 0 ? 'is-active' : '' }}"
                                data-index="{{ $i }}"
                                role="tab"
                                aria-label="Slide {{ $i + 1 }}"></button>
                    @endforeach
                </div>

                <div class="hero-slider-progress" aria-hidden="true"></div>
            </div>
        </div>
    </section>

    {{-- ───── Shop Section: Filter Sidebar + Product Grid ───── --}}
    <section id="shop">
        <div class="container">
            <div class="shop-wrap">

                {{-- Sidebar --}}
                @include('components.filter-bar', ['kategoris' => $kategoris, 'areas' => $areas])

                {{-- Main grid --}}
                <div>
                    <header class="section-head" data-reveal>
                        <div>
                            <h2>Pilihan Terkurasi</h2>
                            <p>Pilihan terbaik minggu ini, langsung dari penjual lokal Malang.</p>
                        </div>
                        @php $totalCount = $barangs->count(); @endphp
                        <span class="results-count">
                            {{ $totalCount > 0 ? $totalCount : 6 }} barang ditampilkan
                        </span>
                    </header>

                    @if($barangs->count() > 0)
                        <div class="product-grid">
                            @foreach($barangs as $i => $barang)
                                @include('components.barang-card', ['barang' => $barang, 'index' => $i])
                            @endforeach
                        </div>

                        @if(method_exists($barangs, 'links'))
                            <div class="d-flex justify-content-center mt-5">
                                {{ $barangs->links() }}
                            </div>
                        @endif
                    @else
                        @php
                            // Dummy data realistis (fallback saat database kosong)
                            $dummies = [
                                [
                                    'id' => 'd1',
                                    'nama_barang' => 'Kursi Lipat Kayu Antik',
                                    'harga' => 145000,
                                    'harga_asli' => 320000,
                                    'kategori' => 'Perabotan',
                                    'area' => 'Lowokwaru',
                                    'kondisi' => 'good',
                                    'badge' => 'rare',
                                    'image_url' => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=600&h=750&fit=crop',
                                    'is_dummy' => true,
                                ],
                                [
                                    'id' => 'd2',
                                    'nama_barang' => 'Jaket Jin Levi\'s Original Era 90-an',
                                    'harga' => 220000,
                                    'harga_asli' => 850000,
                                    'kategori' => 'Busana',
                                    'area' => 'Klojen',
                                    'kondisi' => 'like-new',
                                    'badge' => 'new',
                                    'image_url' => 'https://images.unsplash.com/photo-1544022613-e87ca75a784a?w=600&h=750&fit=crop',
                                    'is_dummy' => true,
                                ],
                                [
                                    'id' => 'd3',
                                    'nama_barang' => 'Setumpuk Buku Sastra Indonesia',
                                    'harga' => 85000,
                                    'harga_asli' => 240000,
                                    'kategori' => 'Buku',
                                    'area' => 'Sukun',
                                    'kondisi' => 'good',
                                    'badge' => null,
                                    'image_url' => 'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?w=600&h=750&fit=crop',
                                    'is_dummy' => true,
                                ],
                                [
                                    'id' => 'd4',
                                    'nama_barang' => 'Kipas Angin Berdiri Maspion',
                                    'harga' => 165000,
                                    'harga_asli' => 380000,
                                    'kategori' => 'Elektronik',
                                    'area' => 'Blimbing',
                                    'kondisi' => 'good',
                                    'badge' => null,
                                    'image_url' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=600&h=750&fit=crop',
                                    'is_dummy' => true,
                                ],
                                [
                                    'id' => 'd5',
                                    'nama_barang' => 'Polaroid Fujifilm Instax Mini 9',
                                    'harga' => 350000,
                                    'harga_asli' => 750000,
                                    'kategori' => 'Elektronik',
                                    'area' => 'Lowokwaru',
                                    'kondisi' => 'like-new',
                                    'badge' => 'rare',
                                    'image_url' => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=600&h=750&fit=crop',
                                    'is_dummy' => true,
                                ],
                                [
                                    'id' => 'd6',
                                    'nama_barang' => 'Rak Buku Kayu Jati 4 Tingkat',
                                    'harga' => 425000,
                                    'harga_asli' => 980000,
                                    'kategori' => 'Perabotan',
                                    'area' => 'Kedungkandang',
                                    'kondisi' => 'fair',
                                    'badge' => null,
                                    'image_url' => 'https://images.unsplash.com/photo-1594620302200-9a762244a156?w=600&h=750&fit=crop',
                                    'is_dummy' => true,
                                ],
                            ];
                        @endphp

                        <div class="product-grid">
                            @foreach($dummies as $i => $dummy)
                                @include('components.barang-card', ['barang' => $dummy, 'index' => $i])
                            @endforeach
                        </div>

                        <div class="empty-state mt-5">
                            <i class="bi bi-info-circle"></i>
                            <p class="mt-2">Belum ada barang asli yang terdaftar. Yang ditampilkan di atas adalah pratinjau contoh.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>

    {{-- ───── Newsletter ───── --}}
    <section class="newsletter">
        <div class="container">
            <div class="newsletter-card" data-reveal="zoom">
                <h2>Jangan ketinggalan <em style="font-style:italic; color:var(--accent);">barang langka</em>.</h2>
                <p>Dapatkan kurasi mingguan barang bekas terbaik langsung di kotak masuk Anda.</p>
                <form class="newsletter-form" onsubmit="event.preventDefault(); this.querySelector('input').value=''; this.querySelector('button').textContent='Berhasil ✓';">
                    <input type="email" placeholder="nama@contoh.com" required>
                    <button type="submit">Berlangganan</button>
                </form>
            </div>
        </div>
    </section>

@endsection
