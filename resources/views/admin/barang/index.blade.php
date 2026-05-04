{{-- @author Silva Tria Alfares - 254107023001 --}}
@extends('layouts.admin')

@section('title', 'Manajemen Barang')
@section('page-title', 'Manajemen Barang')

@section('content')
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
                            <option value="booking" {{ request('status') === 'booking' ? 'selected' : '' }}>Booking</option>
                            <option value="terjual" {{ request('status') === 'terjual' ? 'selected' : '' }}>Terjual</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
                    </div>
                    @if(request()->hasAny(['q', 'kategori', 'area', 'status']))
                        <div class="col-md-2">
                            <a href="{{ route('admin.barang.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
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
                                        {{ ucfirst($barang->status) }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ $barang->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="d-flex gap-1">
<<<<<<< HEAD
                                        <a href="{{ route('admin.barang.show', $barang) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.barang.destroy', $barang) }}" id="del-b-{{ $barang->id }}">
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus"
                                                    onclick="confirmDelete('del-b-{{ $barang->id }}', 'Hapus barang \'{{ $barang->nama_barang }}\'?')">
=======
                                        <a href="{{ route('admin.barang.show', $barang) }}"
                                            class="btn btn-sm btn-outline-primary" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                            </tr>
                        @empty
<<<<<<< HEAD
                            <tr><td colspan="10" class="text-center text-muted py-4">Tidak ada barang ditemukan.</td></tr>
=======
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">Tidak ada barang ditemukan.</td>
                            </tr>
>>>>>>> 580b3871e22f562c606801a6347f07e8e263baef
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<<<<<<< HEAD
    @if($barangs->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted">Menampilkan {{ $barangs->firstItem() }} - {{ $barangs->lastItem() }} dari {{ $barangs->total() }} barang</small>
=======
    @if ($barangs->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted">Menampilkan {{ $barangs->firstItem() }} - {{ $barangs->lastItem() }} dari
                {{ $barangs->total() }} barang</small>
>>>>>>> 580b3871e22f562c606801a6347f07e8e263baef
            {{ $barangs->links() }}
        </div>
    @endif
@endsection
