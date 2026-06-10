<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminBannerController extends Controller
{
    public function index()
    {
        $banners = Banner::ordered()->get();

        return view('admin.banner.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banner.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request, true);

        $validated['image_path'] = $request->file('image')->store('banners', 'public');
        unset($validated['image']);

        Banner::create($this->withDefaults($validated, $request));

        return redirect()->route('admin.banner.index')
            ->with('success', 'Banner berhasil ditambahkan.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banner.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $this->validateData($request, false);

        if ($request->hasFile('image')) {
            if ($banner->image_path && ! str_starts_with($banner->image_path, 'http')) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('banners', 'public');
        }
        unset($validated['image']);

        $banner->update($this->withDefaults($validated, $request));

        return redirect()->route('admin.banner.index')
            ->with('success', 'Banner berhasil diperbarui.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image_path && ! str_starts_with($banner->image_path, 'http')) {
            Storage::disk('public')->delete($banner->image_path);
        }

        $banner->delete();

        return redirect()->route('admin.banner.index')
            ->with('success', 'Banner berhasil dihapus.');
    }

    private function validateData(Request $request, bool $imageRequired): array
    {
        return $request->validate([
            'title'      => ['required', 'string', 'max:150'],
            'eyebrow'    => ['nullable', 'string', 'max:80'],
            'subtitle'   => ['nullable', 'string', 'max:255'],
            'tag'        => ['nullable', 'string', 'max:30'],
            'cta_text'   => ['nullable', 'string', 'max:50'],
            'cta_link'   => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'image'      => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
        ], [
            'title.required' => 'Judul banner wajib diisi.',
            'image.required' => 'Gambar banner wajib diunggah.',
            'image.image'    => 'Berkas harus berupa gambar.',
            'image.mimes'    => 'Format gambar: JPEG, JPG, PNG, atau WebP.',
            'image.max'      => 'Ukuran gambar maksimal 4MB.',
        ]);
    }

    private function withDefaults(array $data, Request $request): array
    {
        $data['is_active']  = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
