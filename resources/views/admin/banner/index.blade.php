@extends('layouts.admin')

@section('title', 'Kelola Banner')
@section('page-title', 'Kelola Banner Beranda')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="text-muted mb-0">Atur banner/slide yang tampil di bagian atas halaman utama.</p>
        <a href="{{ route('admin.banner.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Banner
        </a>
    </div>

    <div class="card admin-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table admin-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:70px;">Urutan</th>
                            <th style="width:140px;">Gambar</th>
                            <th>Judul</th>
                            <th>Tag</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banners as $banner)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $banner->sort_order }}</span></td>
                                <td>
                                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}"
                                         style="width:120px; height:60px; object-fit:cover; border-radius:8px; border:1px solid var(--border);">
                                </td>
                                <td>
                                    @if($banner->eyebrow)
                                        <div class="small text-muted">{{ $banner->eyebrow }}</div>
                                    @endif
                                    <span class="fw-semibold">{!! strip_tags($banner->title, '<em>') !!}</span>
                                </td>
                                <td>
                                    @if($banner->tag)
                                        <span class="badge bg-warning text-dark">{{ $banner->tag }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($banner->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('admin.banner.edit', $banner) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.banner.destroy', $banner) }}" id="del-banner-{{ $banner->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus"
                                                    onclick="confirmDelete('del-banner-{{ $banner->id }}', 'Hapus banner ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada banner. Tambahkan banner pertama Anda.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
