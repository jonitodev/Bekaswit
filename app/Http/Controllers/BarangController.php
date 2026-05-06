<?php

/** @author Joni Yoga Kusuma - 254107023003 */

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\FotoBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function show(Barang $barang)
    {
        $barang->load(['user', 'kategori', 'area', 'fotoBarangs']);

        return view('barang.show', compact('barang'));
    }

    /** @author Mochamad Yunan Helmy Affandi - 244107020101 */
    public function search(Request $request)
    {
        $query = Barang::with(['user', 'kategori', 'area', 'fotoBarangs' => function ($q) {
            $q->where('is_primary', true);
        }]);

        // Default: only 'tersedia'
        $status = $request->input('status', 'tersedia');
        if (in_array($status, ['tersedia', 'booking', 'terjual'])) {
            $query->where('status', $status);
        }

        if ($request->filled('q')) {
            $keyword = $request->input('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_barang', 'LIKE', "%{$keyword}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$keyword}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->byKategori($request->input('kategori'));
        }

        if ($request->filled('area')) {
            $query->byArea($request->input('area'));
        }

        $sort = $request->input('sort', 'terbaru');
        match ($sort) {
            'harga_asc'  => $query->orderBy('harga', 'asc'),
            'harga_desc' => $query->orderBy('harga', 'desc'),
            default      => $query->latest(),
        };

        $barangs    = $query->paginate(12)->appends($request->query());
        $kategoris  = Kategori::orderBy('nama_kategori')->get();
        $areas      = Area::orderBy('nama_kecamatan')->get();

        return view('barang.search', compact('barangs', 'kategoris', 'areas'));
    }

    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $areas     = Area::orderBy('nama_kecamatan')->get();

        return view('barang.create', compact('kategoris', 'areas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => ['required', 'string', 'max:150'],
            'deskripsi'   => ['nullable', 'string', 'max:1000'],
            'harga'       => ['required', 'numeric', 'min:1000', 'max:99999999'],
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'fotos'       => ['required', 'array', 'min:1', 'max:4'],
            'fotos.*'     => ['image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ], [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'harga.required'       => 'Harga wajib diisi.',
            'harga.min'            => 'Harga minimal Rp 1.000.',
            'kategori_id.required' => 'Pilih kategori barang.',
            'fotos.required'       => 'Upload minimal 1 foto barang.',
            'fotos.max'            => 'Maksimal 4 foto barang.',
            'fotos.*.image'        => 'File harus berupa gambar.',
            'fotos.*.mimes'        => 'Format gambar: JPEG, JPG, PNG, atau WebP.',
            'fotos.*.max'          => 'Ukuran foto maksimal 2MB.',
        ]);

        DB::beginTransaction();

        try {
            $barang = Barang::create([
                'user_id'     => Auth::id(),
                'nama_barang' => $validated['nama_barang'],
                'deskripsi'   => $validated['deskripsi'],
                'harga'       => $validated['harga'],
                'kategori_id' => $validated['kategori_id'],
                'status'      => 'tersedia',
                'area_id'     => Auth::user()->area_id,
            ]);

            foreach ($request->file('fotos') as $index => $foto) {
                $path = $foto->store('barang/' . Auth::id(), 'public');

                FotoBarang::create([
                    'barang_id'  => $barang->id,
                    'file_path'  => $path,
                    'is_primary' => $index === 0,
                ]);
            }

            DB::commit();

            return redirect()->route('listing.index')
                ->with('success', 'Barang berhasil diposting!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan barang. Silakan coba lagi.');
        }
    }

    public function edit(Barang $barang)
    {
        if ($barang->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit barang ini.');
        }

        $barang->load('fotoBarangs');
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $areas     = Area::orderBy('nama_kecamatan')->get();

        return view('barang.edit', compact('barang', 'kategoris', 'areas'));
    }

    public function update(Request $request, Barang $barang)
    {
        if ($barang->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit barang ini.');
        }

        $validated = $request->validate([
            'nama_barang' => ['required', 'string', 'max:150'],
            'deskripsi'   => ['nullable', 'string', 'max:1000'],
            'harga'       => ['required', 'numeric', 'min:1000', 'max:99999999'],
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'fotos'       => ['nullable', 'array', 'max:4'],
            'fotos.*'     => ['image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ], [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'harga.required'       => 'Harga wajib diisi.',
            'harga.min'            => 'Harga minimal Rp 1.000.',
            'kategori_id.required' => 'Pilih kategori barang.',
            'fotos.max'            => 'Maksimal 4 foto barang.',
            'fotos.*.image'        => 'File harus berupa gambar.',
            'fotos.*.mimes'        => 'Format gambar: JPEG, JPG, PNG, atau WebP.',
            'fotos.*.max'          => 'Ukuran foto maksimal 2MB.',
        ]);

        DB::beginTransaction();

        try {
            $barang->update([
                'nama_barang' => $validated['nama_barang'],
                'deskripsi'   => $validated['deskripsi'],
                'harga'       => $validated['harga'],
                'kategori_id' => $validated['kategori_id'],
            ]);

            if ($request->hasFile('fotos')) {
                foreach ($barang->fotoBarangs as $oldFoto) {
                    Storage::disk('public')->delete($oldFoto->file_path);
                    $oldFoto->delete();
                }

                foreach ($request->file('fotos') as $index => $foto) {
                    $path = $foto->store('barang/' . Auth::id(), 'public');

                    FotoBarang::create([
                        'barang_id'  => $barang->id,
                        'file_path'  => $path,
                        'is_primary' => $index === 0,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('listing.index')
                ->with('success', 'Barang berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui barang. Silakan coba lagi.');
        }
    }

    public function destroy(Barang $barang)
    {
        if ($barang->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus barang ini.');
        }

        foreach ($barang->fotoBarangs as $foto) {
            Storage::disk('public')->delete($foto->file_path);
        }

        $barang->delete();

        return redirect()->route('listing.index')
            ->with('success', 'Barang berhasil dihapus.');
    }

    public function updateStatus(Request $request, Barang $barang)
    {
        if ($barang->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah status barang ini.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:tersedia,booking,terjual'],
        ]);

        $barang->update(['status' => $validated['status']]);

        $statusLabel = ucfirst($validated['status']);

        return back()->with('success', "Status barang diubah menjadi {$statusLabel}.");
    }
}
