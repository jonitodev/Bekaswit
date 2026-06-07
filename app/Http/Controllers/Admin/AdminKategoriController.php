<?php

/** @author Silva Tria Alfares - 254107023001 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminKategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::withCount('barangs')->orderBy('nama_kategori')->get();

        return view('admin.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:50', 'unique:kategoris,nama_kategori'],
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Nama kategori sudah ada.',
        ]);

        Kategori::create([
            'nama_kategori' => $validated['nama_kategori'],
            'slug'          => Str::slug($validated['nama_kategori']),
        ]);

        return redirect()->route('admin.kategori.index')
            ->with('success', "Kategori '{$validated['nama_kategori']}' berhasil ditambahkan.");
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:50', "unique:kategoris,nama_kategori,{$kategori->id}"],
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Nama kategori sudah ada.',
        ]);

        $kategori->update([
            'nama_kategori' => $validated['nama_kategori'],
            'slug'          => Str::slug($validated['nama_kategori']),
        ]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        $count = $kategori->barangs()->count();

        if ($count > 0) {
            return back()->with('error', "Kategori tidak bisa dihapus karena masih memiliki {$count} barang.");
        }

        $nama = $kategori->nama_kategori;
        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', "Kategori '{$nama}' berhasil dihapus.");
    }
}
