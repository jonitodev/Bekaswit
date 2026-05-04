{{-- @author Silva Tria Alfares - 254107023001 --}}
@extends('layouts.admin')

@section('title', 'Manajemen Area')
@section('page-title', 'Manajemen Area / Kecamatan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="text-muted mb-0">Total: {{ $areas->count() }} area</p>
        <a href="{{ route('admin.area.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Area
        </a>
    </div>

    <div class="card admin-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table admin-table mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kecamatan</th>
                            <th>Kota</th>
                            <th>Jumlah User</th>
                            <th>Jumlah Barang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($areas as $area)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $area->nama_kecamatan }}</td>
                                <td class="text-muted">{{ $area->kota }}</td>
                                <td><span class="badge bg-primary">{{ $area->users_count }}</span></td>
                                <td><span class="badge bg-success">{{ $area->barangs_count }}</span></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.area.edit', $area) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($area->users_count == 0 && $area->barangs_count == 0)
                                            <form method="POST" action="{{ route('admin.area.destroy', $area) }}" id="del-area-{{ $area->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus"
                                                        onclick="confirmDelete('del-area-{{ $area->id }}', 'Hapus area \'{{ $area->nama_kecamatan }}\'?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary" disabled title="Tidak bisa dihapus, masih ada data terkait">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada area.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
