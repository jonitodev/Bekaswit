<?php

/** @author Silva Tria Alfares - 254107023001 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;

class AdminAreaController extends Controller
{
    public function index()
    {
        $areas = Area::withCount(['users', 'barangs'])->orderBy('nama_kecamatan')->get();

        return view('admin.area.index', compact('areas'));
    }

    public function create()
    {
        return view('admin.area.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kecamatan' => ['required', 'string', 'max:100', 'unique:areas,nama_kecamatan'],
            'kota'           => ['required', 'string', 'max:100'],
        ], [
            'nama_kecamatan.required' => 'Nama kecamatan wajib diisi.',
            'nama_kecamatan.unique'   => 'Nama kecamatan sudah ada.',
            'kota.required'           => 'Nama kota wajib diisi.',
        ]);

        Area::create($validated);

        return redirect()->route('admin.area.index')
            ->with('success', "Area '{$validated['nama_kecamatan']}' berhasil ditambahkan.");
    }

    public function edit(Area $area)
    {
        return view('admin.area.edit', compact('area'));
    }

    public function update(Request $request, Area $area)
    {
        $validated = $request->validate([
            'nama_kecamatan' => ['required', 'string', 'max:100', "unique:areas,nama_kecamatan,{$area->id}"],
            'kota'           => ['required', 'string', 'max:100'],
        ], [
            'nama_kecamatan.required' => 'Nama kecamatan wajib diisi.',
            'nama_kecamatan.unique'   => 'Nama kecamatan sudah ada.',
            'kota.required'           => 'Nama kota wajib diisi.',
        ]);

        $area->update($validated);

        return redirect()->route('admin.area.index')
            ->with('success', 'Area berhasil diperbarui.');
    }

    public function destroy(Area $area)
    {
        $usersCount  = $area->users()->count();
        $barangsCount = $area->barangs()->count();

        if ($usersCount > 0 || $barangsCount > 0) {
            return back()->with('error', "Area tidak bisa dihapus karena masih memiliki {$usersCount} pengguna dan {$barangsCount} barang.");
        }

        $nama = $area->nama_kecamatan;
        $area->delete();

        return redirect()->route('admin.area.index')
            ->with('success', "Area '{$nama}' berhasil dihapus.");
    }
}
