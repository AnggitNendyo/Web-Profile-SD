<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GalleryController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Gallery/Index', [
            'galleries' => Gallery::latest()->paginate(12),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:foto,video',
            'category' => 'nullable|string|max:100',
            'file' => 'required|file|mimes:jpg,jpeg,png,webp,mp4,webm|max:10240',
        ]);

        $validated['file_path'] = $request->file('file')->store('galleries', 'public');
        unset($validated['file']);

        Gallery::create($validated);

        return redirect()->route('admin.galeri.index')->with('success', 'Item galeri berhasil ditambahkan.');
    }

    public function destroy(Gallery $galeri)
    {
        $galeri->delete();

        return redirect()->route('admin.galeri.index')->with('success', 'Item galeri berhasil dihapus.');
    }
}
