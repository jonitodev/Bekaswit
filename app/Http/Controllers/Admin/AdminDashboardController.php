<?php

/** @author Silva Tria Alfares - 254107023001 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'            => User::where('role', 'seller')->count(),
            'total_users_blocked'    => User::where('role', 'seller')->where('is_blocked', true)->count(),
            'total_barang'           => Barang::count(),
            'total_barang_tersedia'  => Barang::where('status', 'tersedia')->count(),
            'total_barang_terjual'   => Barang::where('status', 'terjual')->count(),
            'total_kategori'         => Kategori::count(),
            'total_area'             => Area::count(),
        ];

        $latestBarangs = Barang::with(['user', 'kategori'])
            ->latest()->take(5)->get();

        $latestUsers = User::where('role', 'seller')
            ->withCount('barangs')
            ->with('area')
            ->latest()->take(5)->get();

        $barangPerKategori = Kategori::withCount('barangs')
            ->orderByDesc('barangs_count')->get();

        $barangPerArea = Area::withCount('barangs')
            ->orderByDesc('barangs_count')->get();

        return view('admin.dashboard', compact(
            'stats',
            'latestBarangs',
            'latestUsers',
            'barangPerKategori',
            'barangPerArea'
        ));
    }
}
