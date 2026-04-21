{{-- @author Silva Tria Alfares - 254107023001 --}}
{{-- // test from alfa --}}
@extends('layouts.admin')

@section('title', 'Manajemen Penjual')
@section('page-title', 'Manajemen Penjual')

@section('content')
    <!-- Search & Filter -->
    <div class="card admin-card mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.users.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-5">
                        <input type="text" name="q" class="form-control" placeholder="Cari nama atau email..."
                            value="{{ request('q') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="diblokir" {{ request('status') === 'diblokir' ? 'selected' : '' }}>Diblokir
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Cari</button>
                    </div>
                    @if (request()->hasAny(['q', 'status']))
                        <div class="col-md-2">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card admin-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table admin-table mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. WhatsApp</th>
                            <th>Area</th>
                            <th>Barang</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $users->firstItem() + $loop->index }}</td>
                                <td class="fw-semibold">{{ $user->nama }}</td>
                                <td class="text-muted small">{{ $user->email }}</td>
                                <td class="small">{{ $user->no_wa }}</td>
                                <td><span class="badge bg-secondary">{{ $user->area->nama_kecamatan ?? '-' }}</span></td>
                                <td><span class="badge bg-primary">{{ $user->barangs_count }}</span></td>
                                <td>
                                    @if ($user->is_blocked)
                                        <span class="badge badge-status bg-danger">Diblokir</span>
                                    @else
                                        <span class="badge badge-status bg-success">Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.users.show', $user) }}"
                                            class="btn btn-sm btn-outline-primary" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if ($user->is_blocked)
                                            <form method="POST" action="{{ route('admin.users.unblock', $user) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-success"
                                                    title="Buka Blokir"
                                                    onclick="return confirm('Buka blokir penjual {{ $user->nama }}?')">
                                                    <i class="bi bi-unlock"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Blokir"
                                                data-bs-toggle="modal" data-bs-target="#blockModal{{ $user->id }}">
                                                <i class="bi bi-lock"></i>
                                            </button>
                                        @endif

                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                            id="delete-user-{{ $user->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus"
                                                onclick="confirmDelete('delete-user-{{ $user->id }}', 'Hapus penjual {{ $user->nama }} beserta semua datanya?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Block Modal -->
                                    @if (!$user->is_blocked)
                                        <div class="modal fade" id="blockModal{{ $user->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('admin.users.block', $user) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="modal-header">
                                                            <h6 class="modal-title fw-bold">Blokir Penjual:
                                                                {{ $user->nama }}</h6>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="blocked_reason_{{ $user->id }}"
                                                                    class="form-label">Alasan Pemblokiran</label>
                                                                <textarea name="blocked_reason" id="blocked_reason_{{ $user->id }}" rows="3" class="form-control"
                                                                    placeholder="Tuliskan alasan..." required></textarea>
                                                            </div>
                                                            <div class="alert alert-warning small mb-0">
                                                                <i class="bi bi-exclamation-triangle"></i>
                                                                Semua barang aktif penjual ini akan dinonaktifkan.
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger">Blokir
                                                                Penjual</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Tidak ada penjual ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($users->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted">Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari
                {{ $users->total() }} penjual</small>
            {{ $users->links() }}
        </div>
    @endif
@endsection
