{{-- @author Mochamad Yunan Helmy Affandi - 244107020101 --}}
<div class="col-12 col-md-6 col-lg-4 mb-4">
    <a href="{{ route('barang.show', $barang) }}" class="text-decoration-none">
        <div class="card barang-card h-100">
            @php
                $foto = $barang->fotoBarangs->where('is_primary', true)->first()
                     ?? $barang->fotoBarangs->first();
            @endphp

            @if($foto)
                <img src="{{ asset('storage/' . $foto->file_path) }}" class="card-img-top" alt="{{ $barang->nama_barang }}">
            @else
                <div class="img-placeholder">
                    <i class="bi bi-image"></i>
                </div>
            @endif

            <div class="card-body">
                <h6 class="card-title">{{ Str::limit($barang->nama_barang, 50) }}</h6>
                <p class="harga mb-2">{{ $barang->harga_formatted }}</p>
                <div class="d-flex flex-wrap gap-1">
                    <span class="badge badge-kategori">{{ $barang->kategori->nama_kategori }}</span>
                    <span class="badge badge-area">{{ $barang->area->nama_kecamatan }}</span>
                    <span class="badge badge-{{ $barang->status }}">{{ ucfirst($barang->status) }}</span>
                </div>
            </div>
        </div>
    </a>
</div>
