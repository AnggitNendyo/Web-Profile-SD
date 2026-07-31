<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Support\ImageCompressor;
use App\Support\YouTube;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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
            'file' => 'required_if:type,foto|nullable|image|mimes:' . \App\Support\UploadRules::IMAGE_MIMES . '|max:' . \App\Support\UploadRules::IMAGE_MAX_KB,
            'video_url' => 'required_if:type,video|nullable|string|max:255',
        ]);

        if ($validated['type'] === 'video') {
            $youtubeId = YouTube::extractId($validated['video_url'] ?? null);

            if (! $youtubeId) {
                throw ValidationException::withMessages([
                    'video_url' => 'URL YouTube tidak valid. Contoh: https://www.youtube.com/watch?v=xxxxxxxxxxx',
                ]);
            }

            // Untuk video, file_path menyimpan ID YouTube (hemat storage — tanpa upload file).
            $validated['file_path'] = $youtubeId;
        } else {
            $validated['file_path'] = ImageCompressor::store($request->file('file'), 'galleries');
        }

        unset($validated['file'], $validated['video_url']);

        Gallery::create($validated);

        return redirect()->route('admin.galeri.index')->with('success', 'Item galeri berhasil ditambahkan.');
    }

    public function destroy(Gallery $galeri)
    {
        $galeri->delete();

        return redirect()->route('admin.galeri.index')->with('success', 'Item galeri berhasil dihapus.');
    }
}
