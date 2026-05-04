<?php

/** @author Silva Tria Alfares - 254107023001 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'seller')
            ->withCount('barangs')
            ->with('area');

        if ($request->filled('q')) {
            $keyword = $request->input('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'LIKE', "%{$keyword}%")
                  ->orWhere('email', 'LIKE', "%{$keyword}%");
            });
        }

        if ($request->filled('status')) {
            match ($request->input('status')) {
                'aktif'    => $query->where('is_blocked', false),
                'diblokir' => $query->where('is_blocked', true),
                default    => null,
            };
        }

        $users = $query->latest()->paginate(15)->appends($request->query());

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['area', 'barangs' => function ($q) {
            $q->with(['kategori', 'area', 'fotoBarangs'])->latest();
        }]);

        return view('admin.users.show', compact('user'));
    }

    public function block(Request $request, User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Tidak dapat memblokir akun admin.');
        }

        $request->validate([
            'blocked_reason' => ['required', 'string', 'max:255'],
        ], [
            'blocked_reason.required' => 'Alasan pemblokiran wajib diisi.',
        ]);

        $user->update([
            'is_blocked'     => true,
            'blocked_at'     => now(),
            'blocked_reason' => $request->input('blocked_reason'),
        ]);

        $user->barangs()->where('status', 'tersedia')->update(['status' => 'terjual']);

        return back()->with('success', "Penjual {$user->nama} berhasil diblokir.");
    }

    public function unblock(User $user)
    {
        if (!$user->is_blocked) {
            return back()->with('error', 'Penjual ini tidak sedang diblokir.');
        }

        $user->update([
            'is_blocked'     => false,
            'blocked_at'     => null,
            'blocked_reason' => null,
        ]);

        return back()->with('success', "Penjual {$user->nama} berhasil dibuka blokirnya.");
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Tidak dapat menghapus akun admin.');
        }

        $nama = $user->nama;

        foreach ($user->barangs as $barang) {
            foreach ($barang->fotoBarangs as $foto) {
                Storage::disk('public')->delete($foto->file_path);
            }
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Penjual {$nama} dan semua datanya berhasil dihapus.");
    }
}
