{{-- @author Silva Tria Alfares - 254107023001 --}}
@extends('layouts.admin')

@section('title', 'Detail Barang')
@section('page-title', 'Detail Barang')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css">
@endpush

@section('content')
    <a href="{{ route('admin.barang.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="row g-4">
        <!-- Photo Section -->
        <div class="col-md-6">
            @if($barang->fotoBarangs->count() > 1)
                <div id="carouselAdmin" class="carousel slide rounded overflow-hidden shadow-sm" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        @foreach($barang->fotoBarangs as $i => $foto)
                            <button type="button" data-bs-target="#carouselAdmin" data-bs-slide-to="{{ $i }}"
                                    class="{{ $i === 0 ? 'active' : '' }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner">
                        @foreach($barang->fotoBarangs as $i => $foto)
                            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $foto->file_path) }}" class="d-block w-100"
                                     style="height:350px; object-fit:contain; background:#f8f9fa;" alt="">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselAdmin" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselAdmin" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            @elseif($barang->fotoBarangs->count() === 1)
                <img src="{{ asset('storage/' . $barang->fotoBarangs->first()->file_path) }}"
                     class="img-fluid rounded shadow-sm w-100" style="max-height:350px; object-fit:contain; background:#f8f9fa;" alt="">
            @else
                <div class="d-flex align-items-center justify-content-center bg-light rounded" style="height:350px;">
                    <i class="bi bi-image display-1 text-muted"></i>
                </div>
            @endif
        </div>

        <!-- Info Section -->
        <div class="col-md-6">
            <div class="card admin-card">
                <div class="card-body">
                    <h4 class="fw-bold mb-2">{{ $barang->nama_barang }}</h4>
                    <p class="fs-4 fw-bold text-primary mb-3">{{ $barang->harga_formatted }}</p>

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge badge-status bg-{{ $barang->status === 'tersedia' ? 'success' : ($barang->status === 'booking' ? 'warning' : 'secondary') }} fs-6">
                            {{ $barang->status_label }}
                        </span>
                        @php
                            $approvalClass = ['pending' => 'bg-warning text-dark', 'approved' => 'bg-success', 'rejected' => 'bg-danger'][$barang->approval_status] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $approvalClass }} fs-6">{{ $barang->approval_label }}</span>
                        <span class="badge bg-info text-dark fs-6">{{ $barang->kondisi_label }}</span>
                        <span class="badge bg-primary fs-6">{{ $barang->kategori->nama_kategori }}</span>
                        <span class="badge bg-secondary fs-6">{{ $barang->area->nama_kecamatan }}</span>
                    </div>

                    @if($barang->approval_status === 'rejected' && $barang->rejected_reason)
                        <div class="alert alert-danger small">
                            <strong>Alasan penolakan:</strong> {{ $barang->rejected_reason }}
                        </div>
                    @endif

                    @if($barang->deskripsi)
                        <h6 class="fw-semibold">Deskripsi</h6>
                        <p class="text-muted">{{ $barang->deskripsi }}</p>
                    @endif

                    <p class="text-muted small mb-3">
                        <i class="bi bi-calendar3"></i> Diposting {{ $barang->created_at->translatedFormat('d F Y H:i') }}
                    </p>

                    <hr>

                    <h6 class="fw-semibold"><i class="bi bi-person"></i> Info Penjual</h6>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td class="text-muted" style="width:100px">Nama</td>
                                <td>
                                    <a href="{{ route('admin.users.show', $barang->user) }}" class="text-decoration-none">
                                        {{ $barang->user->nama }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email</td>
                                <td>{{ $barang->user->email }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">WhatsApp</td>
                                <td>{{ $barang->user->no_wa }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Area</td>
                                <td>{{ $barang->user->area->nama_kecamatan ?? '-' }}, Malang</td>
                            </tr>
                        </table>
                    </div>

                    @if($barang->latitude && $barang->longitude)
                        <h6 class="fw-semibold mt-3"><i class="bi bi-geo-alt"></i> Lokasi</h6>
                        <div id="map"
                             data-lat="{{ $barang->latitude }}"
                             data-lng="{{ $barang->longitude }}"
                             style="height:240px; border-radius:8px; border:1px solid var(--border);"></div>
                    @endif
                </div>
            </div>

            <div class="d-grid gap-2 mt-3">
                @if($barang->approval_status !== 'approved')
                    <form method="POST" action="{{ route('admin.barang.approve', $barang) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success w-100"
                                onclick="return confirm('Setujui barang ini?')">
                            <i class="bi bi-check-circle"></i> Setujui Barang
                        </button>
                    </form>
                @endif

                @if($barang->approval_status !== 'rejected')
                    <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#rejectBarangModal">
                        <i class="bi bi-x-circle"></i> Tolak Barang
                    </button>
                @endif

                <form method="POST" action="{{ route('admin.barang.destroy', $barang) }}" id="delete-barang-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-outline-danger w-100"
                            onclick="confirmDelete('delete-barang-form', 'Hapus barang \'{{ $barang->nama_barang }}\'?')">
                        <i class="bi bi-trash"></i> Hapus Barang
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if($barang->approval_status !== 'rejected')
        <div class="modal fade" id="rejectBarangModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.barang.reject', $barang) }}">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header">
                            <h6 class="modal-title fw-bold">Tolak Barang: {{ $barang->nama_barang }}</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <label for="rejected_reason" class="form-label">Alasan Penolakan</label>
                            <textarea name="rejected_reason" id="rejected_reason" rows="3"
                                      class="form-control" placeholder="Tuliskan alasan penolakan..." required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning">Tolak Barang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    var mapEl = document.getElementById('map');
    if (mapEl && typeof L !== 'undefined') {
        var lat = parseFloat(mapEl.getAttribute('data-lat'));
        var lng = parseFloat(mapEl.getAttribute('data-lng'));
        var map = L.map('map', { scrollWheelZoom: false }).setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);
        L.marker([lat, lng]).addTo(map);
        setTimeout(function () { map.invalidateSize(); }, 200);
    }
})();
</script>
@endpush
