{{-- @author Mochamad Yunan Helmy Affandi - 244107020101 --}}
{{--
    Variabel yang diharapkan:
    - $barang  : objek Barang ATAU array dummy dengan key:
                 id, nama_barang, harga, harga_asli (opsional), kategori, area,
                 status (tersedia|booking|terjual), kondisi (like-new|good|fair),
                 image_url (opsional), badge (new|rare|null), is_dummy (bool)
    - $index   : urutan untuk staggered animation (opsional)
--}}

@php
    // Normalisasi data: dukung Eloquent model maupun array dummy
    $isArr = is_array($barang);

    $id            = $isArr ? ($barang['id'] ?? uniqid()) : $barang->id;
    $nama          = $isArr ? $barang['nama_barang'] : $barang->nama_barang;
    $harga         = $isArr ? $barang['harga'] : (float) $barang->harga;
    $hargaFmt      = 'Rp ' . number_format($harga, 0, ',', '.');
    $hargaAsli     = $isArr ? ($barang['harga_asli'] ?? null) : null;
    $hargaAsliFmt  = $hargaAsli ? 'Rp ' . number_format($hargaAsli, 0, ',', '.') : null;
    $diskon        = ($hargaAsli && $hargaAsli > 0) ? round((1 - ($harga / $hargaAsli)) * 100) : null;

    $kategori      = $isArr ? $barang['kategori'] : optional($barang->kategori)->nama_kategori;
    $area          = $isArr ? $barang['area']     : optional($barang->area)->nama_kecamatan;
    $status        = $isArr ? ($barang['status'] ?? 'tersedia') : $barang->status;

    // Kondisi: like-new / good / fair (dari kolom asli; dummy memakai key array)
    if ($isArr) {
        $kondisi = $barang['kondisi'] ?? 'good';
    } else {
        $kondisi = $barang->kondisi ?? 'good';
    }
    $kondisiLabel = ['like-new' => 'Seperti Baru', 'good' => 'Baik', 'fair' => 'Cukup'][$kondisi];
    $statusLabel  = ['tersedia' => 'Tersedia', 'booking' => 'Dipesan', 'terjual' => 'Terjual'][$status] ?? ucfirst($status);

    // Glow badge (new / rare / none)
    $glow = $isArr ? ($barang['badge'] ?? null) : null;

    // Resolve image URL
    if ($isArr) {
        $imgUrl = $barang['image_url'] ?? null;
    } else {
        $foto = $barang->fotoBarangs->where('is_primary', true)->first()
              ?? $barang->fotoBarangs->first();
        $imgUrl = $foto ? asset('storage/' . $foto->file_path) : null;
    }

    // Detail link: dummy item tidak punya halaman detail
    $isDummy   = $isArr && ($barang['is_dummy'] ?? false);
    $detailUrl = $isDummy ? '#' : route('barang.show', $barang);

    // Data penjual untuk keranjang + checkout WhatsApp
    if ($isArr) {
        $penjual = $barang['penjual'] ?? 'Penjual';
        $wa      = $barang['wa'] ?? '';
    } else {
        $penjual = optional($barang->user)->nama ?? 'Penjual';
        $wa      = optional($barang->user)->no_wa ?? '';
    }

    // Stagger delay (max 12 items lalu reset)
    $delay = (($index ?? 0) % 12) * 80;
@endphp

<article class="product-card" style="--stagger: {{ $delay }}ms;">
    {{-- Wishlist heart --}}
    <button type="button" class="wishlist-btn" data-id="{{ $id }}" aria-label="Tambah ke daftar keinginan">
        <i class="bi bi-heart"></i>
        <i class="bi bi-heart-fill"></i>
    </button>

    {{-- Media: aspect 4:5 dengan badge & quick view --}}
    <a href="{{ $detailUrl }}" class="product-media d-block">
        @if($imgUrl)
            <img src="{{ $imgUrl }}" alt="{{ $nama }}" loading="lazy" width="400" height="500">
        @else
            <div class="media-fallback">
                <i class="bi bi-bag-heart"></i>
            </div>
        @endif

        <div class="media-badges">
            <div class="d-flex flex-column gap-2">
                @if($kategori)
                    <span class="tag-pill">{{ $kategori }}</span>
                @endif
                @if($glow === 'new')
                    <span class="glow-badge is-new">Baru</span>
                @elseif($glow === 'rare')
                    <span class="glow-badge is-rare">Barang Langka</span>
                @endif
            </div>
        </div>

        <div class="quick-view">
            <i class="bi bi-eye"></i> Lihat Cepat
        </div>
    </a>

    {{-- Body --}}
    <div class="product-body">
        <div class="product-meta">
            <span class="condition-badge {{ $kondisi }}">{{ $kondisiLabel }}</span>
            @if($status !== 'tersedia')
                <span class="tag-pill" style="background: var(--surface-2); color: var(--text-muted);">
                    {{ $statusLabel }}
                </span>
            @endif
        </div>

        <a href="{{ $detailUrl }}" class="text-decoration-none">
            <h3 class="product-title">{{ Str::limit($nama, 60) }}</h3>
        </a>

        <div class="product-prices">
            <span class="price-now">{{ $hargaFmt }}</span>
            @if($hargaAsliFmt)
                <span class="price-orig">{{ $hargaAsliFmt }}</span>
            @endif
            @if($diskon)
                <span class="price-discount">-{{ $diskon }}%</span>
            @endif
        </div>

        <div class="product-foot">
            <span class="location">
                <i class="bi bi-geo-alt-fill"></i>
                <span>{{ $area ?? 'Malang' }}</span>
            </span>
            <button type="button" class="add-cart-btn" aria-label="Tambah ke keranjang"
                    data-id="{{ $id }}"
                    data-nama="{{ $nama }}"
                    data-harga="{{ $harga }}"
                    data-img="{{ $imgUrl }}"
                    data-url="{{ $detailUrl }}"
                    data-penjual="{{ $penjual }}"
                    data-wa="{{ $wa }}">
                <i class="bi bi-bag-plus"></i>
                <i class="bi bi-check2"></i>
            </button>
        </div>
    </div>
</article>
