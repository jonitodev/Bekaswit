<?php

/** @author Izza Dhafira Fanani - 244107020106 */

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->is_blocked) {
                $reason = $user->blocked_reason ?? 'Tidak ada alasan.';
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => "Akun Anda telah diblokir. Alasan: {$reason}. Hubungi admin untuk informasi lebih lanjut.",
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Selamat datang kembali, ' . $user->nama . '!');
            }

            return redirect()->intended(route('seller.dashboard'))
                ->with('success', 'Selamat datang kembali, ' . $user->nama . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        $areas = Area::orderBy('nama_kecamatan')->get();

        return view('auth.register', compact('areas'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'no_wa'    => ['required', 'string', 'regex:/^08[0-9]{8,12}$/'],
            'area_id'  => ['required', 'exists:areas,id'],
        ], [
            'nama.required'     => 'Nama wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 8 karakter.',
            'password.confirmed'=> 'Konfirmasi password tidak cocok.',
            'no_wa.required'    => 'Nomor WhatsApp wajib diisi.',
            'no_wa.regex'       => 'Format nomor WhatsApp tidak valid (contoh: 081234567890).',
            'area_id.required'  => 'Pilih area kecamatan.',
            'area_id.exists'    => 'Area kecamatan tidak valid.',
        ]);

        $user = User::create([
            'nama'     => $validated['nama'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'no_wa'    => $validated['no_wa'],
            'area_id'  => $validated['area_id'],
        ]);

        Auth::login($user);

        return redirect()->route('seller.dashboard')
            ->with('success', 'Registrasi berhasil! Selamat datang di Bekaswit.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
