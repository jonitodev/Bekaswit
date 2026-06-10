{{-- @author Gilang Bayu Irwana - 244107020194 --}}
@extends('layouts.app')

@section('title', $barang->nama_barang . ' - Bekaswit')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css">
@endpush

@section('content')
<div class="container py-4">
    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="row g-4">
        <!-- Photo Section -->
        <div class="col-lg-6">
            @php $fotos = $barang->fotoBarangs; @endphp
            @if($fotos->count() > 0)
                <div class="detail-gallery">
                    <img src="{{ asset('storage/' . $fotos->first()->file_path) }}"
                         id="mainFoto"
                         class="img-fluid rounded-4 w-100"
                         style="height:420px; object-fit:contain; background:var(--border-light); border:1px solid var(--border);"
                         alt="{{ $barang->nama_barang }}">

                    @if($fotos->count() > 1)
                        <div class="d-flex gap-2 mt-3 flex-wrap">
                            @foreach($fotos as $index => $foto)
                                <img src="{{ asset('storage/' . $foto->file_path) }}"
                                     class="detail-thumb {{ $index === 0 ? 'is-active' : '' }}"
                                     data-full="{{ asset('storage/' . $foto->file_path) }}"
                                     alt="{{ $barang->nama_barang }} - foto {{ $index + 1 }}"
                                     style="width:72px; height:72px; object-fit:cover; border-radius:var(--radius-xs); cursor:pointer; border:2px solid {{ $index === 0 ? 'var(--primary)' : 'var(--border)' }};">
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <div class="img-placeholder rounded-4" style="height:420px; border:1px solid var(--border);">
                    <i class="bi bi-image display-1"></i>
                </div>
            @endif
        </div>

        <!-- Info Section -->
        <div class="col-lg-6">
            <h2 class="fw-bold mb-1" style="letter-spacing:-0.02em;">{{ $barang->nama_barang }}</h2>

            <p class="detail-harga mb-3">{{ $barang->harga_formatted }}</p>

            <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="badge badge-{{ $barang->status }}" style="font-size:13px;">{{ $barang->status_label }}</span>
                <span class="condition-badge {{ $barang->kondisi }}">{{ $barang->kondisi_label }}</span>
                <span class="badge badge-kategori" style="font-size:13px;">{{ $barang->kategori->nama_kategori }}</span>
                <span class="badge badge-area" style="font-size:13px;">{{ $barang->area->nama_kecamatan }}</span>
            </div>

            @if($barang->deskripsi)
                <div class="mb-3">
                    <h6 class="fw-semibold" style="color:var(--text);">Deskripsi</h6>
                    <p style="color:var(--text-secondary); line-height:1.7;">{{ $barang->deskripsi }}</p>
                </div>
            @endif

            <div class="card mb-3" style="border:1px solid var(--border); border-radius:var(--radius-sm);">
                <div class="card-body py-3">
                    <h6 class="fw-semibold mb-2" style="font-size:13px; color:var(--text-secondary); text-transform:uppercase; letter-spacing:0.05em;">
                        Info Penjual
                    </h6>
                    <p class="mb-1 fw-semibold">{{ $barang->user->nama }}</p>
                    <p class="mb-0 small" style="color:var(--text-secondary);">
                        <i class="bi bi-geo-alt"></i> {{ $barang->area->nama_kecamatan }}, Malang
                    </p>
                </div>
            </div>

            <p class="small mb-3" style="color:var(--text-muted);">
                <i class="bi bi-calendar3"></i> Diposting {{ $barang->created_at->translatedFormat('d F Y') }}
            </p>

            @if($barang->latitude && $barang->longitude)
                <div class="mb-3">
                    <h6 class="fw-semibold" style="color:var(--text);">
                        <i class="bi bi-geo-alt"></i> Lokasi
                    </h6>
                    <div id="map"
                         data-lat="{{ $barang->latitude }}"
                         data-lng="{{ $barang->longitude }}"
                         style="height:260px; border-radius:var(--radius-sm); border:1px solid var(--border);"></div>
                </div>
            @endif

            @if(!Auth::check() || $barang->user_id !== Auth::id())
                @if($barang->status === 'tersedia')
                    @php
                        $detailFoto = $barang->fotoBarangs->where('is_primary', true)->first()
                                   ?? $barang->fotoBarangs->first();
                        $detailImg  = $detailFoto ? asset('storage/' . $detailFoto->file_path) : '';
                    @endphp
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <a href="{{ generateWhatsAppLink($barang->user->no_wa, $barang->nama_barang, $barang->harga) }}"
                           target="_blank" class="btn btn-whatsapp btn-lg flex-grow-1 py-2">
                            <i class="bi bi-whatsapp"></i> Hubungi via WhatsApp
                        </a>
                        <button type="button" class="btn btn-outline-primary btn-lg py-2 js-add-cart"
                                data-id="{{ $barang->id }}"
                                data-nama="{{ $barang->nama_barang }}"
                                data-harga="{{ $barang->harga }}"
                                data-img="{{ $detailImg }}"
                                data-url="{{ route('barang.show', $barang) }}"
                                data-penjual="{{ $barang->user->nama }}"
                                data-wa="{{ $barang->user->no_wa }}">
                            <i class="bi bi-bag-plus"></i>
                            <span class="js-add-label ms-1">Keranjang</span>
                        </button>
                    </div>
                @endif
            @else
                <a href="{{ route('barang.edit', $barang) }}" class="btn btn-outline-primary w-100 py-2">
                    <i class="bi bi-pencil"></i> Edit Barang Saya
                </a>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    // Gallery: swap main image on thumbnail click
    var main = document.getElementById('mainFoto');
    document.querySelectorAll('.detail-thumb').forEach(function (thumb) {
        thumb.addEventListener('click', function () {
            if (main) main.src = this.getAttribute('data-full');
            document.querySelectorAll('.detail-thumb').forEach(function (t) {
                t.classList.remove('is-active');
                t.style.borderColor = 'var(--border)';
            });
            this.classList.add('is-active');
            this.style.borderColor = 'var(--primary)';
        });
    });

    // Read-only location map
    var mapEl = document.getElementById('map');
    if (mapEl && typeof L !== 'undefined') {
        var lat = parseFloat(mapEl.getAttribute('data-lat'));
        var lng = parseFloat(mapEl.getAttribute('data-lng'));
        var map = L.map('map', { scrollWheelZoom: false }).setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);
        L.marker([lat, lng]).addTo(map);
        setTimeout(function () { map.invalidateSize(); }, 200);
    }
})();
</script>
@endpush
