{{-- @author Mochamad Yunan Helmy Affandi - 244107020101 --}}
{{--
    Sidebar filter — collapsible accordion (smooth max-height transition).
    Variabel: $kategoris (Collection|array), $areas (Collection|array)
--}}

<aside class="filter-sidebar">
    <h3>Saring</h3>
    <p class="filter-sub">Sempurnakan pencarian barang bekas Anda.</p>

    <form method="GET" action="{{ route('barang.search') }}" id="filterForm">
        {{-- Cari --}}
        <div class="filter-group">
            <button type="button" class="filter-toggle" aria-expanded="true" aria-controls="filterSearch">
                <span>Cari</span>
                <i class="bi bi-chevron-down"></i>
            </button>
            <div class="filter-content" id="filterSearch" data-collapsed="false">
                <div class="inner">
                    <input type="text" name="q" class="form-control"
                           placeholder="Cari barang..." value="{{ request('q') }}">
                </div>
            </div>
        </div>

        {{-- Kategori --}}
        <div class="filter-group">
            <button type="button" class="filter-toggle" aria-expanded="true" aria-controls="filterKategori">
                <span>Kategori</span>
                <i class="bi bi-chevron-down"></i>
            </button>
            <div class="filter-content" id="filterKategori" data-collapsed="false">
                <div class="inner chip-group">
                    <a href="{{ request()->fullUrlWithQuery(['kategori' => null]) }}"
                       class="chip {{ ! request('kategori') ? 'is-active' : '' }}">Semua</a>
                    @foreach($kategoris as $kat)
                        <a href="{{ request()->fullUrlWithQuery(['kategori' => $kat->id]) }}"
                           class="chip {{ request('kategori') == $kat->id ? 'is-active' : '' }}">
                            {{ $kat->nama_kategori }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Area --}}
        <div class="filter-group">
            <button type="button" class="filter-toggle" aria-expanded="true" aria-controls="filterArea">
                <span>Area</span>
                <i class="bi bi-chevron-down"></i>
            </button>
            <div class="filter-content" id="filterArea" data-collapsed="false">
                <div class="inner">
                    <select name="area" class="form-select">
                        <option value="">Semua Area</option>
                        @foreach($areas as $ar)
                            <option value="{{ $ar->id }}" {{ request('area') == $ar->id ? 'selected' : '' }}>
                                {{ $ar->nama_kecamatan }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Kondisi --}}
        <div class="filter-group">
            <button type="button" class="filter-toggle" aria-expanded="true" aria-controls="filterKondisi">
                <span>Kondisi</span>
                <i class="bi bi-chevron-down"></i>
            </button>
            <div class="filter-content" id="filterKondisi" data-collapsed="false">
                <div class="inner chip-group">
                    @foreach(['like-new' => 'Seperti Baru', 'good' => 'Baik', 'fair' => 'Cukup'] as $val => $label)
                        <a href="{{ request()->fullUrlWithQuery(['kondisi' => $val]) }}"
                           class="chip {{ request('kondisi') == $val ? 'is-active' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Harga --}}
        <div class="filter-group">
            <button type="button" class="filter-toggle" aria-expanded="true" aria-controls="filterHarga">
                <span>Rentang Harga</span>
                <i class="bi bi-chevron-down"></i>
            </button>
            <div class="filter-content" id="filterHarga" data-collapsed="false">
                <div class="inner">
                    <div class="price-input-row">
                        <input type="number" name="min" class="form-control" placeholder="Min" value="{{ request('min') }}">
                        <span class="text-muted">—</span>
                        <input type="number" name="max" class="form-control" placeholder="Maks" value="{{ request('max') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Urutkan --}}
        <div class="filter-group">
            <button type="button" class="filter-toggle" aria-expanded="false" aria-controls="filterSort">
                <span>Urutkan</span>
                <i class="bi bi-chevron-down"></i>
            </button>
            <div class="filter-content" id="filterSort" data-collapsed="true">
                <div class="inner">
                    <select name="sort" class="form-select">
                        <option value="terbaru"    {{ request('sort') == 'terbaru'    ? 'selected' : '' }}>Terbaru</option>
                        <option value="harga_asc"  {{ request('sort') == 'harga_asc'  ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="harga_desc" {{ request('sort') == 'harga_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="filter-actions">
            <a href="{{ route('barang.search') }}" class="btn btn-outline-secondary">Atur Ulang</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel"></i> Terapkan
            </button>
        </div>
    </form>
</aside>
