<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Support\YouTube;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/News/Index', [
            'news' => News::with('author')->latest()->paginate(10),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/News/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'video_url' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
        ]);

        $validated['video_url'] = $this->resolveVideoId($validated['video_url'] ?? null);
        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(5);
        $validated['author_id'] = $request->user()->id;

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('news-thumbnails', 'public');
        }

        News::create($validated);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dibuat.');
    }

    public function edit(News $beritum)
    {
        return Inertia::render('Admin/News/Form', [
            'news' => $beritum,
        ]);
    }

    public function update(Request $request, News $beritum)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'video_url' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
        ]);

        // Hanya proses video_url jika field dikirim oleh form.
        // Jika tidak ada perubahan, pertahankan nilai yang sudah tersimpan.
        if ($request->has('video_url')) {
            $validated['video_url'] = $this->resolveVideoId($validated['video_url'] ?? null);
        } else {
            unset($validated['video_url']);
        }

        // Hanya update thumbnail jika ada file baru yang diupload.
        // Jika tidak ada file baru, hapus key dari validated agar nilai lama tidak tertimpa null.
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('news-thumbnails', 'public');
        } else {
            unset($validated['thumbnail']);
        }

        $beritum->update($validated);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Ubah URL YouTube menjadi ID; kosong dibiarkan null, format salah ditolak.
     */
    private function resolveVideoId(?string $input): ?string
    {
        if (blank($input)) {
            return null;
        }

        $id = YouTube::extractId($input);

        if (! $id) {
            throw ValidationException::withMessages([
                'video_url' => 'URL YouTube tidak valid. Contoh: https://www.youtube.com/watch?v=xxxxxxxxxxx',
            ]);
        }

        return $id;
    }

    public function destroy(News $beritum)
    {
        $beritum->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }
}
