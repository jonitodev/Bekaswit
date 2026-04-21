{{-- @author Izza Dhafira Fanani - 244107020106 --}}
@extends('layouts.app')

@section('title', 'Masuk Penjual - Bekaswit')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="text-center mb-4">
                <a href="{{ route('home') }}" class="text-decoration-none">
                    <h4 class="fw-bold" style="color:var(--primary); letter-spacing:-0.03em;">
                        <i class="bi bi-recycle"></i> Bekaswit
                    </h4>
                </a>
                <p style="color:var(--text-secondary); font-size:14px;">Masuk ke akun penjual Anda</p>
            </div>

            <div class="card border-0" style="box-shadow:var(--shadow);">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login.process') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Masukkan password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" name="remember" id="remember" class="form-check-input"
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember" class="form-check-label small" style="color:var(--text-secondary);">Ingat saya</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">Masuk</button>
                    </form>

                    <p class="text-center mt-3 mb-0 small" style="color:var(--text-secondary);">
                        Belum punya akun penjual? <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">Daftar di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
