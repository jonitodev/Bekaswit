<?php

/** @author Mochamad Yunan Helmy Affandi - 244107020101 */

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with(['user', 'kategori', 'area', 'fotoBarangs' => function ($q) {
            $q->where('is_primary', true);
        }])->tersedia();

        if ($request->filled('kategori')) {
            $query->byKategori($request->input('kategori'));
        }

        if ($request->filled('area')) {
            $query->byArea($request->input('area'));
        }

        $barangs   = $query->latest()->paginate(12)->appends($request->query());
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $areas     = Area::orderBy('nama_kecamatan')->get();

        return view('home', compact('barangs', 'kategoris', 'areas'));
    }
}
