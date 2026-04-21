{{-- @author Silva Tria Alfares - 254107023001 --}}
{{-- // test from alfa --}}
@extends('layouts.app')

@section('title', 'Dashboard Penjual - Bekaswit')

@section('content')
    <div class="container py-4">

        {{-- Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
            <div>
                <h4 class="fw-bold mb-1">Dashboard Penjual</h4>
                <p class="mb-0 small" style="color:var(--text-secondary);">
                    Halo, <strong>{{ Auth::user()->nama }}</strong> — bergabung sejak
                    {{ $memberSince->translatedFormat('d F Y') }}
                </p>
            </div>
            <a href="{{ route('barang.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Jual Barang Baru
            </a>
        </div>

        {{-- Stat Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="seller-stat-card">
                    <div class="seller-stat-icon" style="background:var(--primary-light); color:var(--primary);">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="seller-stat-value">{{ $stats['total_barang'] }}</div>
                    <div class="seller-stat-label">Total Barang</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="seller-stat-card">
                    <div class="seller-stat-icon" style="background:#DCFCE7; color:#15803D;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="seller-stat-value">{{ $stats['barang_tersedia'] }}</div>
                    <div class="seller-stat-label">Tersedia</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="seller-stat-card">
                    <div class="seller-stat-icon" style="background:#FEF9C3; color:#A16207;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="seller-stat-value">{{ $stats['barang_booking'] }}</div>
                    <div class="seller-stat-label">Booking</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="seller-stat-card">
                    <div class="seller-stat-icon" style="background:#F1F5F9; color:#64748B;">
                        <i class="bi bi-bag-check"></i>
                    </div>
                    <div class="seller-stat-value">{{ $stats['barang_terjual'] }}</div>
                    <div class="seller-stat-label">Terjual</div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Latest Items --}}
            <div class="col-lg-7">
                <div class="card border-0" style="box-shadow:var(--shadow-sm); border-radius:var(--radius);">
                    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center"
                        style="padding:1rem 1.25rem; border-color:var(--border) !important;">
                        <span class="fw-semibold" style="font-size:14px;">
                            <i class="bi bi-clock-history me-1"></i> Barang Terbaru
                        </span>
                        <a href="{{ route('listing.index') }}" class="small text-decoration-none fw-semibold">Lihat
                            Semua</a>
                    </div>
                    <div class="card-body p-0">
                        @if ($latestBarangs->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" style="font-size:14px;">
                                    <thead style="background:var(--border-light);">
                                        <tr>
                                            <th style="padding-left:1.25rem;">Foto</th>
                                            <th>Nama</th>
                                            <th>Harga</th>
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($latestBarangs as $barang)
                                            <tr>
                                                <td style="padding-left:1.25rem;">
                                                    @php $foto = $barang->fotoBarangs->first(); @endphp
                                                    @if ($foto)
                                                        <img src="{{ asset('storage/' . $foto->file_path) }}"
                                                            style="width:42px; height:42px; object-fit:cover; border-radius:var(--radius-xs);"
                                                            alt="">
                                                    @else
                                                        <div class="img-placeholder-sm"
                                                            style="width:42px; height:42px; font-size:1rem;">
                                                            <i class="bi bi-image"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('barang.show', $barang) }}"
                                                        class="text-decoration-none fw-semibold" style="color:var(--text);">
                                                        {{ Str::limit($barang->nama_barang, 30) }}
                                                    </a>
                                                </td>
                                                <td class="fw-semibold" style="color:var(--primary);">
                                                    {{ $barang->harga_formatted }}</td>
                                                <td><span
                                                        class="badge badge-{{ $barang->status }}">{{ ucfirst($barang->status) }}</span>
                                                </td>
                                                <td class="small" style="color:var(--text-muted);">
                                                    {{ $barang->created_at->format('d/m/Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size:2.5rem; color:var(--text-muted);"></i>
                                <p class="mt-2 mb-0 small" style="color:var(--text-secondary);">Belum ada barang yang
                                    dijual.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Items per Category --}}
            <div class="col-lg-5">
                <div class="card border-0" style="box-shadow:var(--shadow-sm); border-radius:var(--radius);">
                    <div class="card-header bg-transparent border-bottom"
                        style="padding:1rem 1.25rem; border-color:var(--border) !important;">
                        <span class="fw-semibold" style="font-size:14px;">
                            <i class="bi bi-tags me-1"></i> Barang per Kategori
                        </span>
                    </div>
                    <div class="card-body">
                        @if ($barangPerKategori->count() > 0)
                            @php $maxKat = $barangPerKategori->max('barangs_count') ?: 1; @endphp
                            @foreach ($barangPerKategori as $kat)
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="small fw-semibold">{{ $kat->nama_kategori }}</span>
                                    <span class="badge"
                                        style="background:var(--primary-light); color:var(--primary);">{{ $kat->barangs_count }}</span>
                                </div>
                                <div class="progress mb-3"
                                    style="height:6px; border-radius:3px; background:var(--border-light);">
                                    <div class="progress-bar" role="progressbar"
                                        style="width:{{ ($kat->barangs_count / $maxKat) * 100 }}%; background:var(--primary); border-radius:3px;">
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-3">
                                <p class="mb-0 small" style="color:var(--text-secondary);">Belum ada data kategori.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="card border-0 mt-4" style="box-shadow:var(--shadow-sm); border-radius:var(--radius);">
                    <div class="card-header bg-transparent border-bottom"
                        style="padding:1rem 1.25rem; border-color:var(--border) !important;">
                        <span class="fw-semibold" style="font-size:14px;">
                            <i class="bi bi-lightning me-1"></i> Aksi Cepat
                        </span>
                    </div>
                    <div class="card-body d-grid gap-2">
                        <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Posting Barang Baru
                        </a>
                        <a href="{{ route('listing.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-box-seam me-1"></i> Kelola Listing
                        </a>
                        <a href="{{ route('profil.edit') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-person me-1"></i> Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
