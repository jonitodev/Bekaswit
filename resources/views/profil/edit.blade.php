{{-- @author Izza Dhafira Fanani - 244107020106 --}}
@extends('layouts.app')

@section('title', 'Profil Saya - Bekaswit')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="text-center mb-4">
                <span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white mb-2" style="width:56px; height:56px; font-size:22px; font-weight:700; background:var(--primary);">
                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                </span>
                <h4 class="fw-bold mb-0">Profil Saya</h4>
            </div>

            <div class="card border-0" style="box-shadow:var(--shadow);">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('profil.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   value="{{ old('nama', $user->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" class="form-control"
                                   value="{{ $user->email }}" disabled readonly style="background:var(--border-light);">
                            <small style="color:var(--text-muted); font-size:12px;">Email tidak dapat diubah.</small>
                        </div>

                        <div class="mb-3">
                            <label for="no_wa" class="form-label">Nomor WhatsApp</label>
                            <input type="text" name="no_wa" id="no_wa"
                                   class="form-control @error('no_wa') is-invalid @enderror"
                                   value="{{ old('no_wa', $user->no_wa) }}" required>
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
                                    <option value="{{ $area->id }}"
                                        {{ old('area_id', $user->area_id) == $area->id ? 'selected' : '' }}>
                                        {{ $area->nama_kecamatan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('area_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
                </div>
    </div>
</div>
@endsection
