<?php

/** @author Silva Tria Alfares - 254107023001 */

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;

class SellerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $barangs = $user->barangs();

        $stats = [
            'total_barang'     => $barangs->count(),
            'barang_tersedia'  => (clone $barangs)->where('status', 'tersedia')->count(),
            'barang_booking'   => (clone $barangs)->where('status', 'booking')->count(),
            'barang_terjual'   => (clone $barangs)->where('status', 'terjual')->count(),
        ];

        $barangPerKategori = Kategori::withCount(['barangs' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        }])->having('barangs_count', '>', 0)
          ->orderByDesc('barangs_count')
          ->get();

        $latestBarangs = $user->barangs()
            ->with(['kategori', 'fotoBarangs' => fn ($q) => $q->where('is_primary', true)])
            ->latest()
            ->take(5)
            ->get();

        $memberSince = $user->created_at;

        return view('seller.dashboard', compact(
            'stats',
            'barangPerKategori',
            'latestBarangs',
            'memberSince'
        ));
    }
}
