{{-- @author Mochamad Yunan Helmy Affandi - 244107020101 --}}
@extends('layouts.app')

@section('title', 'Hasil Pencarian — Bekaswit')

@section('content')
<section>
    <div class="container">
        <div class="shop-wrap">

            {{-- Sidebar filter --}}
            @include('components.filter-bar', ['kategoris' => $kategoris, 'areas' => $areas])

            {{-- Hasil pencarian --}}
            <div>
                <header class="section-head">
                    <div>
                        <h2>
                            @if(request('q'))
                                Hasil untuk <em style="font-style:italic; color:var(--accent);">"{{ request('q') }}"</em>
                            @else
                                Jelajahi <em style="font-style:italic; color:var(--accent);">Semua</em>
                            @endif
                        </h2>
                        <p>
                            @if($barangs->count() > 0)
                                Menampilkan {{ $barangs->firstItem() }}–{{ $barangs->lastItem() }} dari {{ $barangs->total() }} barang.
                            @else
                                Tidak ada barang yang sesuai filter.
                            @endif
                        </p>
                    </div>
                    @if($barangs->count() > 0)
                        <span class="results-count">{{ $barangs->total() }} barang</span>
                    @endif
                </header>

                @if($barangs->count() > 0)
                    <div class="product-grid">
                        @foreach($barangs as $i => $barang)
                            @include('components.barang-card', ['barang' => $barang, 'index' => $i])
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center mt-5">
                        {{ $barangs->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <i class="bi bi-search"></i>
                        <p class="mt-3">Tidak ada barang yang ditemukan untuk pencarian Anda.</p>
                        <a href="{{ route('home') }}" class="btn btn-primary mt-2">
                            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</section>
@endsection
