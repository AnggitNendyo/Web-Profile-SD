<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Page/Index', [
            'pages' => Page::latest()->get(),
        ]);
    }

    public function edit(string $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        return Inertia::render('Admin/Page/Form', [
            'page' => $page,
        ]);
    }

    public function update(Request $request, string $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $validated['updated_by'] = $request->user()->id;

        $page->update($validated);

        return redirect()->route('admin.pages.edit', $slug)->with('success', 'Halaman berhasil diperbarui.');
    }
}
