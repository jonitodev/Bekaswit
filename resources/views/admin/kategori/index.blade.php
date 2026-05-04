{{-- @author Silva Tria Alfares - 254107023001 --}}
@extends('layouts.admin')

@section('title', 'Manajemen Kategori')
@section('page-title', 'Manajemen Kategori')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="text-muted mb-0">Total: {{ $kategoris->count() }} kategori</p>
        <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Kategori
        </a>
    </div>

    <div class="card admin-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table admin-table mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Slug</th>
                            <th>Jumlah Barang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategoris as $kategori)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $kategori->nama_kategori }}</td>
                                <td class="text-muted">{{ $kategori->slug }}</td>
                                <td><span class="badge bg-primary">{{ $kategori->barangs_count }}</span></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.kategori.edit', $kategori) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($kategori->barangs_count == 0)
                                            <form method="POST" action="{{ route('admin.kategori.destroy', $kategori) }}" id="del-kat-{{ $kategori->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus"
                                                        onclick="confirmDelete('del-kat-{{ $kategori->id }}', 'Hapus kategori \'{{ $kategori->nama_kategori }}\'?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary" disabled title="Tidak bisa dihapus, masih ada barang">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada kategori.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
