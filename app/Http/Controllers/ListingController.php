<?php

/** @author Mochamad Yunan Helmy Affandi - 244107020101 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListingController extends Controller
{
    public function index()
    {
        $barangs = Auth::user()
            ->barangs()
            ->with(['kategori', 'area', 'fotoBarangs' => function ($q) {
                $q->where('is_primary', true);
            }])
            ->latest()
            ->get();

        return view('listing.index', compact('barangs'));
    }
}
