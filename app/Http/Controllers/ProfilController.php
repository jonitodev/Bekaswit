<?php

/** @author Izza Dhafira Fanani - 244107020106 */

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function edit()
    {
        $user  = Auth::user();
        $areas = Area::orderBy('nama_kecamatan')->get();

        return view('profil.edit', compact('user', 'areas'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama'    => ['required', 'string', 'max:100'],
            'no_wa'   => ['required', 'string', 'regex:/^08[0-9]{8,12}$/'],
            'area_id' => ['required', 'exists:areas,id'],
        ], [
            'nama.required'    => 'Nama wajib diisi.',
            'no_wa.required'   => 'Nomor WhatsApp wajib diisi.',
            'no_wa.regex'      => 'Format nomor WhatsApp tidak valid (contoh: 081234567890).',
            'area_id.required' => 'Pilih area kecamatan.',
            'area_id.exists'   => 'Area kecamatan tidak valid.',
        ]);

        Auth::user()->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
