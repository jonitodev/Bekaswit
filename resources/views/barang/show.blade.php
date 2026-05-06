{{-- @author Gilang Bayu Irwana - 244107020194 --}}
@extends('layouts.app')

@section('title', $barang->nama_barang . ' - Bekaswit')

@section('content')
<div class="container py-4">
    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="row g-4">
        <!-- Photo Section -->
        <div class="col-lg-6">
            @if($barang->fotoBarangs->count() > 1)
                <div id="carouselBarang" class="carousel slide detail-carousel rounded-4 overflow-hidden" data-bs-ride="carousel"
                     style="border:1px solid var(--border);">
                    <div class="carousel-indicators">
                        @foreach($barang->fotoBarangs as $index => $foto)
                            <button type="button" data-bs-target="#carouselBarang" data-bs-slide-to="{{ $index }}"
                                    class="{{ $index === 0 ? 'active' : '' }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner">
                        @foreach($barang->fotoBarangs as $index => $foto)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $foto->file_path) }}" class="d-block w-100" alt="{{ $barang->nama_barang }}">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselBarang" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselBarang" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            @elseif($barang->fotoBarangs->count() === 1)
                <img src="{{ asset('storage/' . $barang->fotoBarangs->first()->file_path) }}"
                     class="img-fluid rounded-4 w-100" style="max-height:420px; object-fit:contain; background:var(--border-light); border:1px solid var(--border);"
                     alt="{{ $barang->nama_barang }}">
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
                <span class="badge badge-{{ $barang->status }}" style="font-size:13px;">{{ ucfirst($barang->status) }}</span>
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

            @if(!Auth::check() || $barang->user_id !== Auth::id())
                @if($barang->status === 'tersedia')
                    <a href="{{ generateWhatsAppLink($barang->user->no_wa, $barang->nama_barang, $barang->harga) }}"
                       target="_blank" class="btn btn-whatsapp btn-lg w-100 py-2">
                        <i class="bi bi-whatsapp"></i> Hubungi Penjual via WhatsApp
                    </a>
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
