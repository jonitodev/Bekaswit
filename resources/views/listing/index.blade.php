{{-- @author Gilang Bayu Irwana - 244107020194 --}}
@extends('layouts.app')

@section('title', 'Listing Saya - Bekaswit')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Listing Saya</h4>
        <a href="{{ route('barang.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Jual Barang Baru
        </a>
    </div>

    @if($barangs->count() > 0)
        <div class="card border-0" style="box-shadow:var(--shadow-sm); border-radius:var(--radius);">
            <div class="table-responsive">
                <table class="table table-hover align-middle listing-table mb-0">
                    <thead>
                        <tr>
                            <th style="padding-left:1.25rem;">Foto</th>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barangs as $barang)
                            <tr>
                                <td style="padding-left:1.25rem;">
                                    @php
                                        $foto = $barang->fotoBarangs->where('is_primary', true)->first()
                                             ?? $barang->fotoBarangs->first();
                                    @endphp
                                    @if($foto)
                                        <img src="{{ asset('storage/' . $foto->file_path) }}" class="barang-thumb" alt="{{ $barang->nama_barang }}">
                                    @else
                                        <div class="img-placeholder-sm"><i class="bi bi-image"></i></div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('barang.show', $barang) }}" class="text-decoration-none fw-semibold" style="color:var(--text);">
                                        {{ Str::limit($barang->nama_barang, 40) }}
                                    </a>
                                </td>
                                <td class="fw-semibold" style="color:var(--primary);">{{ $barang->harga_formatted }}</td>
                                <td><span class="badge badge-kategori">{{ $barang->kategori->nama_kategori }}</span></td>
                                <td>
                                    <form method="POST" action="{{ route('barang.updateStatus', $barang) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="form-select form-select-sm" style="width:auto; font-size:13px;">
                                            <option value="tersedia" {{ $barang->status === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                            <option value="booking" {{ $barang->status === 'booking' ? 'selected' : '' }}>Booking</option>
                                            <option value="terjual" {{ $barang->status === 'terjual' ? 'selected' : '' }}>Terjual</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="small" style="color:var(--text-muted);">{{ $barang->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('barang.edit', $barang) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('barang.destroy', $barang) }}"
                                              onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size:4rem; color:var(--text-muted);"></i>
            <p class="mt-3" style="color:var(--text-secondary); font-size:1.05rem;">Belum ada barang yang Anda jual.</p>
            <a href="{{ route('barang.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Mulai Jual Barang
            </a>
        </div>
    @endif
</div>
@endsection
