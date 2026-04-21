{{-- @author Silva Tria Alfares - 254107023001 --}}
{{-- // test from alfa --}}
@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card border-primary">
                <div class="stat-value">{{ $stats['total_users'] }}</div>
                <div class="stat-label">Total Penjual</div>
                <i class="bi bi-people stat-icon"></i>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card border-danger">
                <div class="stat-value">{{ $stats['total_users_blocked'] }}</div>
                <div class="stat-label">Penjual Diblokir</div>
                <i class="bi bi-person-x stat-icon"></i>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card border-success">
                <div class="stat-value">{{ $stats['total_barang'] }}</div>
                <div class="stat-label">Total Barang</div>
                <i class="bi bi-box-seam stat-icon"></i>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card border-info">
                <div class="stat-value">{{ $stats['total_barang_tersedia'] }}</div>
                <div class="stat-label">Barang Tersedia</div>
                <i class="bi bi-check-circle stat-icon"></i>
            </div>
        </div>
    </div>

    <!-- Latest Data Tables -->
    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="card admin-card">
                <div class="card-header">
                    <i class="bi bi-clock-history"></i> 5 Barang Terbaru
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm admin-table mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Penjual</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestBarangs as $barang)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.barang.show', $barang) }}"
                                                class="text-decoration-none">
                                                {{ Str::limit($barang->nama_barang, 30) }}
                                            </a>
                                        </td>
                                        <td class="text-muted small">{{ $barang->user->nama }}</td>
                                        <td class="fw-semibold">{{ $barang->harga_formatted }}</td>
                                        <td>
                                            <span
                                                class="badge badge-status bg-{{ $barang->status === 'tersedia' ? 'success' : ($barang->status === 'booking' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($barang->status) }}
                                            </span>
                                        </td>
                                        <td class="text-muted small">{{ $barang->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">Belum ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card admin-card">
                <div class="card-header">
                    <i class="bi bi-person-plus"></i> 5 Penjual Terbaru
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm admin-table mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Area</th>
                                    <th>Barang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestUsers as $user)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.users.show', $user) }}" class="text-decoration-none">
                                                {{ $user->nama }}
                                            </a>
                                        </td>
                                        <td class="text-muted small">{{ $user->area->nama_kecamatan ?? '-' }}</td>
                                        <td><span class="badge bg-secondary">{{ $user->barangs_count }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">Belum ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Distribution Charts (Progress Bars) -->
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card admin-card">
                <div class="card-header"><i class="bi bi-tags"></i> Barang per Kategori</div>
                <div class="card-body stat-progress">
                    @php $maxKat = $barangPerKategori->max('barangs_count') ?: 1; @endphp
                    @foreach ($barangPerKategori as $kat)
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small fw-semibold">{{ $kat->nama_kategori }}</span>
                            <span class="badge bg-primary">{{ $kat->barangs_count }}</span>
                        </div>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-primary"
                                style="width: {{ ($kat->barangs_count / $maxKat) * 100 }}%"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card admin-card">
                <div class="card-header"><i class="bi bi-geo-alt"></i> Barang per Area</div>
                <div class="card-body stat-progress">
                    @php $maxArea = $barangPerArea->max('barangs_count') ?: 1; @endphp
                    @foreach ($barangPerArea as $ar)
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small fw-semibold">{{ $ar->nama_kecamatan }}</span>
                            <span class="badge bg-success">{{ $ar->barangs_count }}</span>
                        </div>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-success"
                                style="width: {{ ($ar->barangs_count / $maxArea) * 100 }}%"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
