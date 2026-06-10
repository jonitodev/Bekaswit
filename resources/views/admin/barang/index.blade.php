{{-- @author Silva Tria Alfares - 254107023001 --}}
@extends('layouts.admin')

@section('title', 'Manajemen Barang')
@section('page-title', 'Manajemen Barang')

@section('content')
    @if($pendingCount > 0)
        <div class="alert alert-warning d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-clock-history"></i>
            <span>Ada <strong>{{ $pendingCount }}</strong> barang menunggu persetujuan.</span>
            <a href="{{ route('admin.barang.index', ['approval' => 'pending']) }}" class="btn btn-sm btn-warning ms-auto">Lihat</a>
        </div>
    @endif

    <!-- Filter Bar -->
    <div class="card admin-card mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.barang.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <input type="text" name="q" class="form-control" placeholder="Cari nama barang..."
                               value="{{ request('q') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="kategori" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="area" class="form-select">
                            <option value="">Semua Area</option>
                            @foreach($areas as $ar)
                                <option value="{{ $ar->id }}" {{ request('area') == $ar->id ? 'selected' : '' }}>
                                    {{ $ar->nama_kecamatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="tersedia" {{ request('status') === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="booking" {{ request('status') === 'booking' ? 'selected' : '' }}>Dipesan</option>
                            <option value="terjual" {{ request('status') === 'terjual' ? 'selected' : '' }}>Terjual</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="approval" class="form-select">
                            <option value="">Semua Persetujuan</option>
                            <option value="pending" {{ request('approval') === 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                            <option value="approved" {{ request('approval') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                            <option value="rejected" {{ request('approval') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
                    </div>
                    @if(request()->hasAny(['q', 'kategori', 'area', 'status', 'approval']))
                        <div class="col-md-2">
                            <a href="{{ route('admin.barang.index') }}" class="btn btn-outline-secondary w-100">Atur Ulang</a>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Barang Table -->
    <div class="card admin-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table admin-table mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>Nama Barang</th>
                            <th>Penjual</th>
                            <th>Harga</th>
                            <th>Kategori</th>
                            <th>Area</th>
                            <th>Status</th>
                            <th>Persetujuan</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangs as $barang)
                            <tr>
                                <td>{{ $barangs->firstItem() + $loop->index }}</td>
                                <td>
                                    @php $foto = $barang->fotoBarangs->first(); @endphp
                                    @if($foto)
                                        <img src="{{ asset('storage/' . $foto->file_path) }}" class="thumb" alt="">
                                    @else
                                        <div class="thumb-placeholder"><i class="bi bi-image"></i></div>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ Str::limit($barang->nama_barang, 30) }}</td>
                                <td>
                                    <a href="{{ route('admin.users.show', $barang->user) }}" class="text-decoration-none small">
                                        {{ $barang->user->nama }}
                                    </a>
                                </td>
                                <td class="fw-semibold">{{ $barang->harga_formatted }}</td>
                                <td><span class="badge bg-primary">{{ $barang->kategori->nama_kategori }}</span></td>
                                <td><span class="badge bg-secondary">{{ $barang->area->nama_kecamatan }}</span></td>
                                <td>
                                    <span class="badge badge-status bg-{{ $barang->status === 'tersedia' ? 'success' : ($barang->status === 'booking' ? 'warning' : 'secondary') }}">
                                        {{ $barang->status_label }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $approvalClass = ['pending' => 'bg-warning text-dark', 'approved' => 'bg-success', 'rejected' => 'bg-danger'][$barang->approval_status] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $approvalClass }}">{{ $barang->approval_label }}</span>
                                </td>
                                <td class="text-muted small">{{ $barang->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.barang.show', $barang) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($barang->approval_status !== 'approved')
                                            <form method="POST" action="{{ route('admin.barang.approve', $barang) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Setujui"
                                                        onclick="return confirm('Setujui barang \'{{ $barang->nama_barang }}\'?')">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if($barang->approval_status !== 'rejected')
                                            <button type="button" class="btn btn-sm btn-outline-warning" title="Tolak"
                                                    data-bs-toggle="modal" data-bs-target="#rejectModal{{ $barang->id }}">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        @endif
                                        <form method="POST" action="{{ route('admin.barang.destroy', $barang) }}" id="del-b-{{ $barang->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus"
                                                    onclick="confirmDelete('del-b-{{ $barang->id }}', 'Hapus barang \'{{ $barang->nama_barang }}\'?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="11" class="text-center text-muted py-4">Tidak ada barang ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($barangs->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted">Menampilkan {{ $barangs->firstItem() }} - {{ $barangs->lastItem() }} dari {{ $barangs->total() }} barang</small>
            {{ $barangs->links() }}
        </div>
    @endif

    {{-- Reject Modals (placed outside the table to avoid backdrop overlap) --}}
    @foreach($barangs as $barang)
        @if($barang->approval_status !== 'rejected')
            <div class="modal fade" id="rejectModal{{ $barang->id }}" tabindex="-1">
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
                                <label for="reason-{{ $barang->id }}" class="form-label">Alasan Penolakan</label>
                                <textarea name="rejected_reason" id="reason-{{ $barang->id }}" rows="3"
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
        @endif
    @endforeach
@endsection
