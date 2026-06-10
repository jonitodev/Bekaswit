@php $banner = $banner ?? null; @endphp

<div class="row g-3">
    <div class="col-md-7">
        <div class="mb-3">
            <label for="title" class="form-label">Judul</label>
            <input type="text" name="title" id="title"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $banner->title ?? '') }}"
                   placeholder="Contoh: Obral &lt;em&gt;Perabotan&lt;/em&gt; Antik" required>
            <small class="text-muted">Bungkus kata dengan <code>&lt;em&gt;...&lt;/em&gt;</code> untuk teks miring beraksen.</small>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="eyebrow" class="form-label">Eyebrow <span class="text-muted">(teks kecil di atas judul)</span></label>
            <input type="text" name="eyebrow" id="eyebrow"
                   class="form-control @error('eyebrow') is-invalid @enderror"
                   value="{{ old('eyebrow', $banner->eyebrow ?? '') }}" placeholder="Contoh: Edisi Pekan Ini">
            @error('eyebrow')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="subtitle" class="form-label">Subjudul</label>
            <textarea name="subtitle" id="subtitle" rows="2"
                      class="form-control @error('subtitle') is-invalid @enderror"
                      placeholder="Deskripsi singkat banner">{{ old('subtitle', $banner->subtitle ?? '') }}</textarea>
            @error('subtitle')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="row g-3">
            <div class="col-sm-4">
                <label for="tag" class="form-label">Tag</label>
                <input type="text" name="tag" id="tag"
                       class="form-control @error('tag') is-invalid @enderror"
                       value="{{ old('tag', $banner->tag ?? '') }}" placeholder="-60% / Baru">
                @error('tag')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-sm-8">
                <label for="cta_text" class="form-label">Teks Tombol (CTA)</label>
                <input type="text" name="cta_text" id="cta_text"
                       class="form-control @error('cta_text') is-invalid @enderror"
                       value="{{ old('cta_text', $banner->cta_text ?? '') }}" placeholder="Lihat Koleksi">
                @error('cta_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mb-3 mt-3">
            <label for="cta_link" class="form-label">Tautan Tombol (CTA)</label>
            <input type="text" name="cta_link" id="cta_link"
                   class="form-control @error('cta_link') is-invalid @enderror"
                   value="{{ old('cta_link', $banner->cta_link ?? '') }}" placeholder="/cari?kategori=1">
            <small class="text-muted">Bisa URL lengkap atau path internal (contoh: <code>/cari?kategori=1</code>).</small>
            @error('cta_link')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="row g-3">
            <div class="col-sm-4">
                <label for="sort_order" class="form-label">Urutan</label>
                <input type="number" name="sort_order" id="sort_order" min="0" max="999"
                       class="form-control @error('sort_order') is-invalid @enderror"
                       value="{{ old('sort_order', $banner->sort_order ?? 0) }}">
                @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-sm-8 d-flex align-items-end">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                           {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Tampilkan banner ini (aktif)</label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <label for="image" class="form-label">Gambar Banner</label>
        <div class="banner-preview mb-2" id="bannerPreview"
             style="aspect-ratio:2/1; border-radius:12px; overflow:hidden; border:1px solid var(--border); background:var(--surface-2); display:flex; align-items:center; justify-content:center;">
            @if($banner && $banner->image_path)
                <img src="{{ $banner->image_url }}" alt="" style="width:100%; height:100%; object-fit:cover;">
            @else
                <span class="text-muted small"><i class="bi bi-image"></i> Pratinjau gambar</span>
            @endif
        </div>
        <input type="file" name="image" id="image" accept="image/*"
               class="form-control @error('image') is-invalid @enderror" {{ $banner ? '' : 'required' }}>
        <small class="text-muted">Disarankan rasio lebar (mis. 1800&times;900). Maks 4MB.</small>
        @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-circle"></i> Simpan
    </button>
    <a href="{{ route('admin.banner.index') }}" class="btn btn-outline-secondary">Batal</a>
</div>

@push('scripts')
<script>
    (function () {
        var input = document.getElementById('image');
        var box   = document.getElementById('bannerPreview');
        if (!input || !box) return;
        input.addEventListener('change', function () {
            var file = this.files && this.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function (e) {
                box.innerHTML = '<img src="' + e.target.result + '" alt="" style="width:100%;height:100%;object-fit:cover;">';
            };
            reader.readAsDataURL(file);
        });
    })();
</script>
@endpush
