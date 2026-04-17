{{-- @author Silva Tria Alfares - 254107023001 --}}
@extends('layouts.admin')

@section('title', 'Tambah Area')
@section('page-title', 'Tambah Area / Kecamatan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card admin-card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.area.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="nama_kecamatan" class="form-label">Nama Kecamatan</label>
                            <input type="text" name="nama_kecamatan" id="nama_kecamatan"
                                   class="form-control @error('nama_kecamatan') is-invalid @enderror"
                                   value="{{ old('nama_kecamatan') }}" placeholder="Masukkan nama kecamatan" required autofocus>
                            @error('nama_kecamatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="kota" class="form-label">Kota</label>
                            <input type="text" name="kota" id="kota"
                                   class="form-control @error('kota') is-invalid @enderror"
                                   value="{{ old('kota', 'Malang') }}" required>
                            @error('kota')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Simpan
                            </button>
                            <a href="{{ route('admin.area.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
