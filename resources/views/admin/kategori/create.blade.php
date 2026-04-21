{{-- @author Silva Tria Alfares - 254107023001 --}}
{{-- // test from alfa --}}
@extends('layouts.admin')

@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card admin-card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.kategori.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori</label>
                            <input type="text" name="nama_kategori" id="nama_kategori"
                                class="form-control @error('nama_kategori') is-invalid @enderror"
                                value="{{ old('nama_kategori') }}" placeholder="Masukkan nama kategori" required autofocus>
                            @error('nama_kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Slug akan otomatis di-generate.</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Simpan
                            </button>
                            <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
