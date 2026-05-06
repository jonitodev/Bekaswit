{{-- @author Gilang Bayu Irwana - 244107020194 --}}
@extends('layouts.app')

@section('title', 'Beranda - Bekaswit')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container position-relative">
            <h1>Bekaswit &mdash; Bekas Jadi Duit</h1>
            <p class="hero-sub mb-4">Temukan barang bekas kos berkualitas di area Malang</p>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <form method="GET" action="{{ route('barang.search') }}">
                        <div class="input-group hero-search">
                            <input type="text" name="q" class="form-control" placeholder="Cari kipas, meja, rak buku...">
                            <button class="btn" type="submit">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Content -->
    <div class="container py-4">
        @include('components.filter-bar', ['kategoris' => $kategoris, 'areas' => $areas])

        @if($barangs->count() > 0)
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
                <i class="bi bi-inbox" style="font-size:4rem; color:var(--text-muted);"></i>
                <p class="mt-3" style="color:var(--text-secondary); font-size:1.05rem;">Belum ada barang yang tersedia.</p>
            </div>
        @endif
    </div>
@endsection
