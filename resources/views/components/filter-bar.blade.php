{{-- @author Mochamad Yunan Helmy Affandi - 244107020101 --}}
<div class="filter-bar mb-4">
    <form method="GET" action="{{ route('barang.search') }}">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
                <label for="q" class="form-label small fw-semibold">Cari Barang</label>
                <input type="text" name="q" id="q" class="form-control"
                       placeholder="Cari barang bekas..." value="{{ request('q') }}">
            </div>
            <div class="col-6 col-md-2">
                <label for="kategori" class="form-label small fw-semibold">Kategori</label>
                <select name="kategori" id="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label for="area" class="form-label small fw-semibold">Area</label>
                <select name="area" id="area" class="form-select">
                    <option value="">Semua Area</option>
                    @foreach($areas as $ar)
                        <option value="{{ $ar->id }}" {{ request('area') == $ar->id ? 'selected' : '' }}>
                            {{ $ar->nama_kecamatan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label for="sort" class="form-label small fw-semibold">Urutkan</label>
                <select name="sort" id="sort" class="form-select">
                    <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                    <option value="harga_asc" {{ request('sort') == 'harga_asc' ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="harga_desc" {{ request('sort') == 'harga_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
        </div>
    </form>
</div>
