{{-- @author Silva Tria Alfares - 254107023001 --}}
@extends('layouts.admin')

@section('title', 'Detail Barang')
@section('page-title', 'Detail Barang')

@section('content')
    <a href="{{ route('admin.barang.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="row g-4">
        <!-- Photo Section -->
        <div class="col-md-6">
            @if($barang->fotoBarangs->count() > 1)
                <div id="carouselAdmin" class="carousel slide rounded overflow-hidden shadow-sm" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        @foreach($barang->fotoBarangs as $i => $foto)
                            <button type="button" data-bs-target="#carouselAdmin" data-bs-slide-to="{{ $i }}"
                                    class="{{ $i === 0 ? 'active' : '' }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner">
                        @foreach($barang->fotoBarangs as $i => $foto)
                            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $foto->file_path) }}" class="d-block w-100"
                                     style="height:350px; object-fit:contain; background:#f8f9fa;" alt="">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselAdmin" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselAdmin" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            @elseif($barang->fotoBarangs->count() === 1)
                <img src="{{ asset('storage/' . $barang->fotoBarangs->first()->file_path) }}"
                     class="img-fluid rounded shadow-sm w-100" style="max-height:350px; object-fit:contain; background:#f8f9fa;" alt="">
            @else
                <div class="d-flex align-items-center justify-content-center bg-light rounded" style="height:350px;">
                    <i class="bi bi-image display-1 text-muted"></i>
                </div>
            @endif
        </div>

        <!-- Info Section -->
        <div class="col-md-6">
            <div class="card admin-card">
                <div class="card-body">
                    <h4 class="fw-bold mb-2">{{ $barang->nama_barang }}</h4>
                    <p class="fs-4 fw-bold text-primary mb-3">{{ $barang->harga_formatted }}</p>

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge badge-status bg-{{ $barang->status === 'tersedia' ? 'success' : ($barang->status === 'booking' ? 'warning' : 'secondary') }} fs-6">
                            {{ ucfirst($barang->status) }}
                        </span>
                        <span class="badge bg-primary fs-6">{{ $barang->kategori->nama_kategori }}</span>
                        <span class="badge bg-secondary fs-6">{{ $barang->area->nama_kecamatan }}</span>
                    </div>

                    @if($barang->deskripsi)
                        <h6 class="fw-semibold">Deskripsi</h6>
                        <p class="text-muted">{{ $barang->deskripsi }}</p>
                    @endif

                    <p class="text-muted small mb-3">
                        <i class="bi bi-calendar3"></i> Diposting {{ $barang->created_at->translatedFormat('d F Y H:i') }}
                    </p>

                    <hr>

                    <h6 class="fw-semibold"><i class="bi bi-person"></i> Info Penjual</h6>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td class="text-muted" style="width:100px">Nama</td>
                                <td>
                                    <a href="{{ route('admin.users.show', $barang->user) }}" class="text-decoration-none">
                                        {{ $barang->user->nama }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email</td>
                                <td>{{ $barang->user->email }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">WhatsApp</td>
                                <td>{{ $barang->user->no_wa }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Area</td>
                                <td>{{ $barang->user->area->nama_kecamatan ?? '-' }}, Malang</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.barang.destroy', $barang) }}" id="delete-barang-form" class="mt-3">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-danger w-100"
                        onclick="confirmDelete('delete-barang-form', 'Hapus barang \'{{ $barang->nama_barang }}\'?')">
                    <i class="bi bi-trash"></i> Hapus Barang
                </button>
            </form>
        </div>
    </div>
@endsection
