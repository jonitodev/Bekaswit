<?php

/** @author Silva Tria Alfares - 254107023001 */
// test from alfa

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminBarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with(['user', 'kategori', 'area', 'fotoBarangs' => function ($q) {
            $q->where('is_primary', true);
        }]);

        if ($request->filled('q')) {
            $query->where('nama_barang', 'LIKE', "%{$request->input('q')}%");
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->input('kategori'));
        }

        if ($request->filled('area')) {
            $query->where('area_id', $request->input('area'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $barangs    = $query->latest()->paginate(15)->appends($request->query());
        $kategoris  = Kategori::orderBy('nama_kategori')->get();
        $areas      = Area::orderBy('nama_kecamatan')->get();

        return view('admin.barang.index', compact('barangs', 'kategoris', 'areas'));
    }

    public function show(Barang $barang)
    {
        $barang->load(['user.area', 'kategori', 'area', 'fotoBarangs']);

        return view('admin.barang.show', compact('barang'));
    }

    public function destroy(Barang $barang)
    {
        $namaBarang = $barang->nama_barang;
        $namaUser   = $barang->user->nama;

        foreach ($barang->fotoBarangs as $foto) {
            Storage::disk('public')->delete($foto->file_path);
        }

        $barang->delete();

        return redirect()->route('admin.barang.index')
            ->with('success', "Barang '{$namaBarang}' milik {$namaUser} berhasil dihapus.");
    }
}
