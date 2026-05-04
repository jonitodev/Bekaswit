{{-- @author Mochamad Yunan Helmy Affandi - 244107020101 --}}
@extends('layouts.app')

@section('title', 'Hasil Pencarian - Bekaswit')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-1">
        Hasil Pencarian
    </h4>
    @if(request('q'))
        <p class="mb-3" style="color:var(--text-secondary);">Menampilkan hasil untuk "<strong>{{ request('q') }}</strong>"</p>
    @else
        <p class="mb-3" style="color:var(--text-secondary);">Jelajahi semua barang yang tersedia</p>
    @endif

    @include('components.filter-bar', ['kategoris' => $kategoris, 'areas' => $areas])

    @if($barangs->count() > 0)
        <p class="small mb-3" style="color:var(--text-muted);">Menampilkan {{ $barangs->firstItem() }}&ndash;{{ $barangs->lastItem() }} dari {{ $barangs->total() }} barang</p>

        <div class="row">
            @foreach($barangs as $barang)
                @include('components.barang-card', ['barang' => $barang])
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-2">
            {{ $barangs->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-search" style="font-size:4rem; color:var(--text-muted);"></i>
            <p class="mt-3" style="color:var(--text-secondary); font-size:1.05rem;">Tidak ada barang yang ditemukan.</p>
            <a href="{{ route('home') }}" class="btn btn-outline-primary">Kembali ke Beranda</a>
        </div>
    @endif
</div>
@endsection
