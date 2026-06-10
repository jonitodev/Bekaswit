{{-- @author Izza Dhafira Fanani - 244107020106 --}}
@extends('layouts.app')

@section('title', 'Daftar Penjual - Bekaswit')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="text-center mb-4">
                <a href="{{ route('home') }}" class="text-decoration-none">
                    <h4 class="fw-bold" style="color:var(--primary); letter-spacing:-0.03em;">
                        <i class="bi bi-recycle"></i> Bekaswit
                    </h4>
                </a>
                <p style="color:var(--text-secondary); font-size:14px;">Daftar sebagai penjual untuk mulai jual barang bekas</p>
            </div>

            <div class="card border-0" style="box-shadow:var(--shadow);">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('register.process') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" placeholder="nama@email.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Kata Sandi</label>
                                <input type="password" name="password" id="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Minimal 8 karakter" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="form-control" placeholder="Ulangi kata sandi" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="no_wa" class="form-label">Nomor WhatsApp</label>
                            <input type="text" name="no_wa" id="no_wa"
                                   class="form-control @error('no_wa') is-invalid @enderror"
                                   value="{{ old('no_wa') }}" placeholder="08xxxxxxxxxx" required>
                            @error('no_wa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="area_id" class="form-label">Area Kecamatan</label>
                            <select name="area_id" id="area_id"
                                    class="form-select @error('area_id') is-invalid @enderror" required>
                                <option value="">Pilih kecamatan...</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                        {{ $area->nama_kecamatan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('area_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Anti-scammer: terms & conditions agreement --}}
                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="terms" id="terms" value="1"
                                       class="form-check-input @error('terms') is-invalid @enderror"
                                       {{ old('terms') ? 'checked' : '' }} required>
                                <label for="terms" class="form-check-label small" style="color:var(--text-secondary);">
                                    Saya telah membaca dan menyetujui
                                    <a href="#" class="fw-semibold text-decoration-none"
                                       data-bs-toggle="modal" data-bs-target="#termsModal">
                                        Syarat &amp; Ketentuan Penjual
                                    </a>
                                    serta berkomitmen untuk berjualan secara jujur, bukan penipu.
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" id="btnDaftar" class="btn btn-primary w-100 py-2" disabled>
                            Daftar sebagai Penjual
                        </button>
                    </form>

                    <p class="text-center mt-3 mb-0 small" style="color:var(--text-secondary);">
                        Sudah punya akun penjual? <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Masuk di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Terms & Conditions Modal (Anti-Scammer Policy) --}}
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
        <div class="modal-content border-0" style="border-radius:16px;">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold mb-1" id="termsModalLabel">
                        <i class="bi bi-shield-check me-2" style="color:var(--primary);"></i>
                        Syarat &amp; Ketentuan Penjual
                    </h5>
                    <p class="small mb-0" style="color:var(--text-secondary);">
                        Kebijakan anti-penipuan Bekaswit. Wajib dibaca sebelum mendaftar.
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <div class="alert alert-warning border-0 small mb-4" style="border-radius:12px;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Penting:</strong> Penipuan dalam bentuk apa pun akan berakibat
                    pemblokiran akun permanen dan pelaporan ke pihak berwajib.
                </div>

                <h6 class="fw-bold mt-3">1. Identitas Asli &amp; Valid</h6>
                <ul class="small" style="color:var(--text-secondary);">
                    <li>Wajib menggunakan nama lengkap sesuai identitas (KTP).</li>
                    <li>Nomor WhatsApp harus aktif, milik pribadi, dan dapat dihubungi.</li>
                    <li>Email yang didaftarkan harus valid dan masih digunakan.</li>
                    <li>Dilarang membuat akun ganda atau menggunakan identitas orang lain.</li>
                </ul>

                <h6 class="fw-bold mt-3">2. Kejujuran Produk</h6>
                <ul class="small" style="color:var(--text-secondary);">
                    <li>Foto barang <strong>wajib asli</strong> hasil pemotretan sendiri, bukan
                        hasil unduh dari internet atau marketplace lain.</li>
                    <li>Deskripsi barang harus sesuai kondisi nyata, termasuk cacat atau
                        kerusakan (jika ada).</li>
                    <li>Harga yang dicantumkan harus jelas dan tidak menyesatkan
                        (tidak ada biaya tersembunyi).</li>
                    <li>Stok barang harus tersedia saat ditayangkan.</li>
                </ul>

                <h6 class="fw-bold mt-3">3. Larangan Penipuan</h6>
                <ul class="small" style="color:var(--text-secondary);">
                    <li>Dilarang meminta pembayaran di muka (DP) tanpa kejelasan barang.</li>
                    <li>Dilarang mengalihkan transaksi ke platform lain untuk menghindari
                        pelacakan.</li>
                    <li>Dilarang menjual barang ilegal, palsu (tiruan), hasil curian, atau
                        barang yang melanggar hukum.</li>
                    <li>Dilarang melakukan manipulasi ulasan, penilaian, atau testimoni palsu.</li>
                </ul>

                <h6 class="fw-bold mt-3">4. Komunikasi &amp; Transaksi</h6>
                <ul class="small" style="color:var(--text-secondary);">
                    <li>Wajib merespons calon pembeli dengan sopan dalam waktu wajar
                        (maksimal 1x24 jam).</li>
                    <li>Disarankan menggunakan metode COD (Bayar di Tempat) untuk
                        keamanan kedua belah pihak.</li>
                    <li>Jika transfer, gunakan rekening atas nama sendiri dan berikan
                        bukti pengiriman yang valid.</li>
                </ul>

                <h6 class="fw-bold mt-3">5. Sanksi Pelanggaran</h6>
                <ul class="small" style="color:var(--text-secondary);">
                    <li><strong>Peringatan 1:</strong> Tayangan barang dihapus oleh admin.</li>
                    <li><strong>Peringatan 2:</strong> Akun dibekukan sementara (7 hari).</li>
                    <li><strong>Pelanggaran berat:</strong> Akun diblokir permanen dan data
                        dilaporkan ke pihak berwajib jika terbukti melakukan penipuan.</li>
                </ul>

                <h6 class="fw-bold mt-3">6. Persetujuan Data</h6>
                <p class="small" style="color:var(--text-secondary);">
                    Dengan mendaftar, Anda mengizinkan Bekaswit menyimpan dan memproses
                    data Anda untuk keperluan verifikasi, keamanan transaksi, dan
                    pencegahan penipuan sesuai dengan Kebijakan Privasi yang berlaku.
                </p>

                <div class="alert alert-light border small mt-4" style="border-radius:12px; background:rgba(0,0,0,0.03);">
                    <i class="bi bi-info-circle me-2"></i>
                    Dengan mencentang kotak persetujuan, Anda menyatakan telah membaca,
                    memahami, dan setuju untuk terikat pada seluruh ketentuan di atas.
                </div>
            </div>

            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
                <button type="button" class="btn btn-primary" id="btnAgreeTerms" data-bs-dismiss="modal">
                    <i class="bi bi-check2-circle me-1"></i> Saya Setuju
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Anti-scammer: enable submit button only when terms are agreed
    (function () {
        var checkbox  = document.getElementById('terms');
        var btnSubmit = document.getElementById('btnDaftar');
        var btnAgree  = document.getElementById('btnAgreeTerms');

        if (!checkbox || !btnSubmit) return;

        function syncSubmitState() {
            btnSubmit.disabled = !checkbox.checked;
        }

        checkbox.addEventListener('change', syncSubmitState);

        if (btnAgree) {
            btnAgree.addEventListener('click', function () {
                checkbox.checked = true;
                syncSubmitState();
            });
        }

        syncSubmitState();
    })();
</script>
@endpush
