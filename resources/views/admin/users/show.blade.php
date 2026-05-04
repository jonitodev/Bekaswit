{{-- @author Silva Tria Alfares - 254107023001 --}}
@extends('layouts.admin')

@section('title', 'Detail Penjual')
@section('page-title', 'Detail Penjual')

@section('content')
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="row g-4">
        <!-- User Info Card -->
        <div class="col-lg-4">
            <div class="card admin-card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-person-circle display-3 text-muted"></i>
                    </div>
                    <h5 class="fw-bold mb-1">{{ $user->nama }}</h5>
                    <p class="text-muted mb-2">{{ $user->email }}</p>

                    @if($user->is_blocked)
                        <span class="badge bg-danger fs-6 mb-3">Diblokir</span>
                    @else
                        <span class="badge bg-success fs-6 mb-3">Aktif</span>
                    @endif

                    <hr>

                    <div class="text-start">
                        <p class="mb-2"><strong><i class="bi bi-whatsapp text-success"></i> WhatsApp:</strong> {{ $user->no_wa }}</p>
                        <p class="mb-2"><strong><i class="bi bi-geo-alt"></i> Area:</strong> {{ $user->area->nama_kecamatan ?? '-' }}, Malang</p>
                        <p class="mb-2"><strong><i class="bi bi-calendar3"></i> Terdaftar:</strong> {{ $user->created_at->translatedFormat('d F Y') }}</p>
                        <p class="mb-0"><strong><i class="bi bi-box-seam"></i> Total Barang:</strong> {{ $user->barangs->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Block Info -->
            @if($user->is_blocked)
                <div class="alert alert-danger mt-3">
                    <h6 class="fw-bold"><i class="bi bi-lock"></i> Penjual Diblokir</h6>
                    <p class="mb-1"><strong>Alasan:</strong> {{ $user->blocked_reason }}</p>
                    <p class="mb-0 small text-muted">Diblokir pada: {{ $user->blocked_at->translatedFormat('d F Y H:i') }}</p>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="d-grid gap-2 mt-3">
                @if($user->is_blocked)
                    <form method="POST" action="{{ route('admin.users.unblock', $user) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Buka blokir penjual ini?')">
                            <i class="bi bi-unlock"></i> Buka Blokir
                        </button>
                    </form>
                @else
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#blockModal">
                        <i class="bi bi-lock"></i> Blokir Penjual
                    </button>
                @endif

                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" id="delete-user-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-outline-danger w-100"
                            onclick="confirmDelete('delete-user-form', 'Hapus penjual {{ $user->nama }} dan semua datanya?')">
                        <i class="bi bi-trash"></i> Hapus Penjual
                    </button>
                </form>
            </div>
        </div>

        <!-- User's Barangs -->
        <div class="col-lg-8">
            <div class="card admin-card">
                <div class="card-header">
                    <i class="bi bi-box-seam"></i> Daftar Barang ({{ $user->barangs->count() }})
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table admin-table mb-0">
                            <thead>
                                <tr>
                                    <th>Foto</th>
                                    <th>Nama Barang</th>
                                    <th>Harga</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->barangs as $barang)
                                    <tr>
                                        <td>
                                            @php $foto = $barang->fotoBarangs->where('is_primary', true)->first() ?? $barang->fotoBarangs->first(); @endphp
                                            @if($foto)
                                                <img src="{{ asset('storage/' . $foto->file_path) }}" class="thumb" alt="">
                                            @else
                                                <div class="thumb-placeholder"><i class="bi bi-image"></i></div>
                                            @endif
                                        </td>
                                        <td class="fw-semibold">{{ Str::limit($barang->nama_barang, 30) }}</td>
                                        <td>{{ $barang->harga_formatted }}</td>
                                        <td><span class="badge bg-primary">{{ $barang->kategori->nama_kategori }}</span></td>
                                        <td>
                                            <span class="badge badge-status bg-{{ $barang->status === 'tersedia' ? 'success' : ($barang->status === 'booking' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($barang->status) }}
                                            </span>
                                        </td>
                                        <td class="text-muted small">{{ $barang->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('admin.barang.show', $barang) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.barang.destroy', $barang) }}" id="del-brg-{{ $barang->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus"
                                                            onclick="confirmDelete('del-brg-{{ $barang->id }}', 'Hapus barang ini?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted py-4">Penjual belum memiliki barang.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Block Modal -->
    @if(!$user->is_blocked)
        <div class="modal fade" id="blockModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.users.block', $user) }}">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header">
                            <h6 class="modal-title fw-bold">Blokir Penjual: {{ $user->nama }}</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="blocked_reason" class="form-label">Alasan Pemblokiran</label>
                                <textarea name="blocked_reason" id="blocked_reason" rows="3"
                                          class="form-control" placeholder="Tuliskan alasan pemblokiran..." required></textarea>
                            </div>
                            <div class="alert alert-warning small mb-0">
                                <i class="bi bi-exclamation-triangle"></i>
                                Semua barang aktif penjual ini akan dinonaktifkan.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Blokir Penjual</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
