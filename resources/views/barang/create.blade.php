{{-- @author Joni Yoga Kusuma - 254107023003 --}}
@extends('layouts.app')

@section('title', 'Jual Barang - Bekaswit')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css">
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <h4 class="fw-bold mb-1">Posting Barang Baru</h4>
            <p class="mb-4" style="color:var(--text-secondary);">Isi detail barang yang ingin Anda jual</p>

            <div class="card border-0" style="box-shadow:var(--shadow);">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('barang.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <input type="text" name="nama_barang" id="nama_barang"
                                   class="form-control @error('nama_barang') is-invalid @enderror"
                                   value="{{ old('nama_barang') }}" placeholder="Contoh: Kipas Angin Maspion" required>
                            @error('nama_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="4"
                                      class="form-control @error('deskripsi') is-invalid @enderror"
                                      placeholder="Jelaskan kondisi, merk, dan alasan jual...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="harga" class="form-label">Harga (Rp)</label>
                                <input type="number" name="harga" id="harga"
                                       class="form-control @error('harga') is-invalid @enderror"
                                       value="{{ old('harga') }}" min="1000" placeholder="50000" required>
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="kategori_id" class="form-label">Kategori</label>
                                <select name="kategori_id" id="kategori_id"
                                        class="form-select @error('kategori_id') is-invalid @enderror" required>
                                    <option value="">Pilih kategori...</option>
                                    @foreach($kategoris as $kat)
                                        <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                                            {{ $kat->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="kondisi" class="form-label">Kondisi Barang</label>
                            <select name="kondisi" id="kondisi"
                                    class="form-select @error('kondisi') is-invalid @enderror" required>
                                <option value="">Pilih kondisi...</option>
                                @foreach(['like-new' => 'Seperti Baru', 'good' => 'Baik', 'fair' => 'Cukup'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('kondisi') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('kondisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Lokasi Barang <span style="color:var(--text-muted); font-weight:400;">(opsional)</span></label>
                                <button type="button" id="useMyLocation" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-crosshair"></i> Gunakan Lokasi Saya
                                </button>
                            </div>
                            <div id="map" style="height:280px; border-radius:var(--radius-sm); border:1px solid var(--border);"></div>
                            <small id="locationHint" style="color:var(--text-muted); font-size:12px;">Klik pada peta atau bagikan lokasi untuk menandai posisi barang Anda.</small>
                            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                        </div>

                        <div class="mb-4">
                            <label for="fotos" class="form-label">Foto Barang</label>
                            <input type="file" name="fotos[]" id="fotos" multiple accept="image/*"
                                   class="form-control @error('fotos') is-invalid @enderror @error('fotos.*') is-invalid @enderror" required>
                            <small style="color:var(--text-muted); font-size:12px;">Minimal 1, maksimal 5 foto. Ukuran maks 2MB per foto (JPEG, PNG, WebP)</small>
                            @error('fotos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('fotos.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="photo-preview-container mt-2" id="photoPreview"></div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-check-circle"></i> Posting Barang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    var latInput = document.getElementById('latitude');
    var lngInput = document.getElementById('longitude');
    var malang   = [-7.9666, 112.6326];
    var hasPoint = latInput.value && lngInput.value;
    var start    = hasPoint ? [parseFloat(latInput.value), parseFloat(lngInput.value)] : malang;

    var map = L.map('map').setView(start, hasPoint ? 15 : 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    var marker = hasPoint ? L.marker(start).addTo(map) : null;

    function setPoint(latlng) {
        if (marker) {
            marker.setLatLng(latlng);
        } else {
            marker = L.marker(latlng).addTo(map);
        }
        latInput.value = latlng.lat.toFixed(8);
        lngInput.value = latlng.lng.toFixed(8);
    }

    map.on('click', function (e) { setPoint(e.latlng); });

    var btn  = document.getElementById('useMyLocation');
    var hint = document.getElementById('locationHint');
    if (btn) {
        btn.addEventListener('click', function () {
            if (!navigator.geolocation) {
                if (hint) { hint.textContent = 'Peramban tidak mendukung berbagi lokasi.'; hint.style.color = 'var(--danger, #dc3545)'; }
                return;
            }
            var original = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mencari lokasi...';
            navigator.geolocation.getCurrentPosition(function (pos) {
                var latlng = { lat: pos.coords.latitude, lng: pos.coords.longitude };
                setPoint(latlng);
                map.setView([latlng.lat, latlng.lng], 16);
                if (hint) { hint.textContent = 'Lokasi Anda berhasil ditandai. Geser penanda bila perlu.'; hint.style.color = 'var(--text-muted)'; }
                btn.disabled = false;
                btn.innerHTML = original;
            }, function (err) {
                if (hint) {
                    hint.textContent = err.code === err.PERMISSION_DENIED
                        ? 'Izin lokasi ditolak. Anda masih bisa klik peta secara manual.'
                        : 'Gagal mengambil lokasi. Coba lagi atau klik peta secara manual.';
                    hint.style.color = 'var(--danger, #dc3545)';
                }
                btn.disabled = false;
                btn.innerHTML = original;
            }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 });
        });
    }

    setTimeout(function () { map.invalidateSize(); }, 200);
})();
</script>
<script>
/* @author Joni Yoga Kusuma - 254107023003 */
document.getElementById('fotos').addEventListener('change', function(e) {
    const preview = document.getElementById('photoPreview');
    preview.innerHTML = '';

    if (this.files.length > 5) {
        alert('Maksimal 5 foto!');
        this.value = '';
        return;
    }

    Array.from(this.files).forEach(file => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endpush
