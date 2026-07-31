<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Support\ImageCompressor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::orderBy('order_index')->paginate(10);
        return Inertia::render('Admin/Facilities/Index', [
            'facilities' => $facilities
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        if ($request->hasFile('photo')) {
            $validated['photo'] = ImageCompressor::store($request->file('photo'), 'facilities');
        }

        Facility::create($validated);
        return back()->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function update(Request $request, Facility $facility)
    {
        $validated = $this->validateData($request);

        if ($request->hasFile('photo')) {
            if ($facility->photo) {
                Storage::disk('public')->delete($facility->photo);
            }
            $validated['photo'] = ImageCompressor::store($request->file('photo'), 'facilities');
        } else {
            unset($validated['photo']);
        }

        $facility->update($validated);
        return back()->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Facility $facility)
    {
        if ($facility->photo) {
            Storage::disk('public')->delete($facility->photo);
        }

        $facility->delete();
        return back()->with('success', 'Fasilitas berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string',
            'photo' => \App\Support\UploadRules::image(),
            'order_index' => 'nullable|integer',
        ]);
    }
}
