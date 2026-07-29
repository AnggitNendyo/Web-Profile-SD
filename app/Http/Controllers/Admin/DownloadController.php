<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Models\MasterData;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DownloadController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Download/Index', [
            'downloads'  => Download::with('uploader')->latest()->paginate(10),
            'categories' => MasterData::getByType('download_category'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip|max:10240',
        ]);

        $validated['file_path'] = $request->file('file')->store('downloads', 'public');
        $validated['uploaded_by'] = $request->user()->id;
        unset($validated['file']);

        Download::create($validated);

        return redirect()->route('admin.unduhan.index')->with('success', 'Dokumen berhasil diunggah.');
    }

    public function destroy(Download $unduhan)
    {
        $unduhan->delete();

        return redirect()->route('admin.unduhan.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}
