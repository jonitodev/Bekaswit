{{-- @author Joni Yoga Kusuma - 254107023003 --}}
@extends('layouts.app')

@section('title', 'Jual Barang - Bekaswit')

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

                        <div class="mb-4">
                            <label for="fotos" class="form-label">Foto Barang</label>
                            <input type="file" name="fotos[]" id="fotos" multiple accept="image/*"
                                   class="form-control @error('fotos') is-invalid @enderror @error('fotos.*') is-invalid @enderror" required>
                            <small style="color:var(--text-muted); font-size:12px;">Maks 4 foto, ukuran maks 2MB per foto (JPEG, PNG, WebP)</small>
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
<script>
/* @author Joni Yoga Kusuma - 254107023003 */
document.getElementById('fotos').addEventListener('change', function(e) {
    const preview = document.getElementById('photoPreview');
    preview.innerHTML = '';

    if (this.files.length > 4) {
        alert('Maksimal 4 foto!');
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
