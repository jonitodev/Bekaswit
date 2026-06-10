{{-- @author Silva Tria Alfares - 254107023001 --}}
@extends('layouts.admin')

@section('title', 'Dasbor')
@section('page-title', 'Dasbor')

@section('content')

    {{-- ───── Welcome Banner ───── --}}
    <div class="welcome-banner">
        <span class="welcome-pill">
            <i class="bi bi-sparkles"></i> Konsol Bekaswit
        </span>
        <h2>
            Selamat datang kembali, <em>{{ Str::limit(Auth::user()->nama, 18) }}</em>.
        </h2>
        <p>
            Kelola penjual, barang, kategori, dan area lokapasar barang bekas Bekaswit dari satu tempat.
            Berikut ringkasan aktivitas terbaru.
        </p>
    </div>

    {{-- ───── Statistics Cards ───── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card tone-accent">
                <div class="stat-head">
                    <span class="stat-icon-pill"><i class="bi bi-people"></i></span>
                    <span class="stat-label">Total Penjual</span>
                </div>
                <div class="stat-value">{{ $stats['total_users'] }}</div>
                <span class="stat-trend">
                    <i class="bi bi-arrow-up-right"></i> Aktif berdagang
                </span>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card tone-danger">
                <div class="stat-head">
                    <span class="stat-icon-pill"><i class="bi bi-person-x"></i></span>
                    <span class="stat-label">Diblokir</span>
                </div>
                <div class="stat-value">{{ $stats['total_users_blocked'] }}</div>
                <span class="stat-trend is-down">
                    <i class="bi bi-shield-exclamation"></i> Perlu ditinjau
                </span>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card tone-success">
                <div class="stat-head">
                    <span class="stat-icon-pill"><i class="bi bi-bag"></i></span>
                    <span class="stat-label">Total Barang</span>
                </div>
                <div class="stat-value">{{ $stats['total_barang'] }}</div>
                <span class="stat-trend">
                    <i class="bi bi-graph-up-arrow"></i> Terdaftar
                </span>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card tone-info">
                <div class="stat-head">
                    <span class="stat-icon-pill"><i class="bi bi-check2-circle"></i></span>
                    <span class="stat-label">Tersedia</span>
                </div>
                <div class="stat-value">{{ $stats['total_barang_tersedia'] }}</div>
                <span class="stat-trend">
                    <i class="bi bi-bag-check"></i> Siap dibeli
                </span>
            </div>
        </div>
    </div>

    {{-- ───── Pending Approval Requests ───── --}}
    <div class="card admin-card mb-4">
        <div class="card-header d-flex align-items-center">
            <i class="bi bi-patch-check"></i>
            <span class="ms-1">Permintaan Persetujuan Barang</span>
            @if($stats['total_barang_pending'] > 0)
                <span class="badge bg-warning text-dark ms-2">{{ $stats['total_barang_pending'] }}</span>
            @endif
            <a href="{{ route('admin.barang.index', ['approval' => 'pending']) }}" class="btn btn-sm btn-outline-primary ms-auto">
                Lihat Semua
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table admin-table mb-0">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Penjual</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Tanggal</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingBarangs as $barang)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.barang.show', $barang) }}">
                                        {{ Str::limit($barang->nama_barang, 28) }}
                                    </a>
                                </td>
                                <td class="text-muted small">{{ $barang->user->nama }}</td>
                                <td class="text-muted small">{{ $barang->kategori->nama_kategori }}</td>
                                <td class="fw-semibold">{{ $barang->harga_formatted }}</td>
                                <td class="text-muted small">{{ $barang->created_at->format('d/m/Y') }}</td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <form method="POST" action="{{ route('admin.barang.approve', $barang) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Setujui"
                                                    onclick="return confirm('Setujui barang \'{{ $barang->nama_barang }}\'?')">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-outline-warning" title="Tolak"
                                                data-bs-toggle="modal" data-bs-target="#rejectDash{{ $barang->id }}">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada barang yang menunggu persetujuan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Reject Modals (placed outside the table to avoid backdrop overlap) --}}
    @foreach($pendingBarangs as $barang)
        <div class="modal fade" id="rejectDash{{ $barang->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.barang.reject', $barang) }}">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header">
                            <h6 class="modal-title fw-bold">Tolak Barang: {{ Str::limit($barang->nama_barang, 30) }}</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-start">
                            <label for="reasonDash-{{ $barang->id }}" class="form-label">Alasan Penolakan</label>
                            <textarea name="rejected_reason" id="reasonDash-{{ $barang->id }}" rows="3"
                                      class="form-control" placeholder="Tuliskan alasan penolakan..." required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning">Tolak Barang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- ───── Latest Data Tables ───── --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="card admin-card">
                <div class="card-header">
                    <i class="bi bi-clock-history"></i> Barang Terbaru
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table admin-table mb-0">
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
                                            <a href="{{ route('admin.barang.show', $barang) }}">
                                                {{ Str::limit($barang->nama_barang, 30) }}
                                            </a>
                                        </td>
                                        <td class="text-muted small">{{ $barang->user->nama }}</td>
                                        <td class="fw-semibold">{{ $barang->harga_formatted }}</td>
                                        <td>
                                            <span class="badge badge-status bg-{{ $barang->status === 'tersedia' ? 'success' : ($barang->status === 'booking' ? 'warning' : 'secondary') }}">
                                                {{ $barang->status_label }}
                                            </span>
                                        </td>
                                        <td class="text-muted small">{{ $barang->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data.</td></tr>
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
                    <i class="bi bi-person-plus"></i> Penjual Terbaru
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table admin-table mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Area</th>
                                    <th class="text-end">Barang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestUsers as $user)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.users.show', $user) }}">
                                                {{ $user->nama }}
                                            </a>
                                        </td>
                                        <td class="text-muted small">{{ $user->area->nama_kecamatan ?? '—' }}</td>
                                        <td class="text-end"><span class="badge bg-primary">{{ $user->barangs_count }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted py-4">Belum ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ───── Distribution ───── --}}
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card admin-card">
                <div class="card-header"><i class="bi bi-tags"></i> Barang per Kategori</div>
                <div class="card-body stat-progress">
                    @php $maxKat = $barangPerKategori->max('barangs_count') ?: 1; @endphp
                    @forelse($barangPerKategori as $kat)
                        <div class="dist-row">
                            <span class="label">{{ $kat->nama_kategori }}</span>
                            <span class="count">{{ $kat->barangs_count }}</span>
                        </div>
                        <div class="progress mb-3">
                            <div class="progress-bar" role="progressbar"
                                 style="width: {{ ($kat->barangs_count / $maxKat) * 100 }}%"
                                 aria-valuenow="{{ $kat->barangs_count }}"
                                 aria-valuemin="0"
                                 aria-valuemax="{{ $maxKat }}"></div>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Belum ada data kategori.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card admin-card">
                <div class="card-header"><i class="bi bi-geo-alt"></i> Barang per Area</div>
                <div class="card-body stat-progress">
                    @php $maxArea = $barangPerArea->max('barangs_count') ?: 1; @endphp
                    @forelse($barangPerArea as $ar)
                        <div class="dist-row">
                            <span class="label">{{ $ar->nama_kecamatan }}</span>
                            <span class="count">{{ $ar->barangs_count }}</span>
                        </div>
                        <div class="progress mb-3">
                            <div class="progress-bar" role="progressbar"
                                 style="width: {{ ($ar->barangs_count / $maxArea) * 100 }}%"
                                 aria-valuenow="{{ $ar->barangs_count }}"
                                 aria-valuemin="0"
                                 aria-valuemax="{{ $maxArea }}"></div>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Belum ada data area.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection
