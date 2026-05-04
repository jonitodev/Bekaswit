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
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Minimal 8 karakter" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="form-control" placeholder="Ulangi password" required>
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

                        <button type="submit" class="btn btn-primary w-100 py-2">Daftar sebagai Penjual</button>
                    </form>

                    <p class="text-center mt-3 mb-0 small" style="color:var(--text-secondary);">
                        Sudah punya akun penjual? <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Masuk di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
