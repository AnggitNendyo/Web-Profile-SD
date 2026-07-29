<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExtracurricularController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Extracurricular/Index', [
            'extracurriculars' => Extracurricular::orderBy('sort_order')->paginate(10),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Extracurricular/Form');
    }

    public function edit(Extracurricular $ekstrakurikuler)
    {
        return Inertia::render('Admin/Extracurricular/Form', [
            'extracurricular' => $ekstrakurikuler,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('extracurriculars', 'public');
        }

        Extracurricular::create($validated);

        return redirect()->route('admin.ekstrakurikuler.index')->with('success', 'Ekstrakurikuler berhasil ditambahkan.');
    }

    public function update(Request $request, Extracurricular $ekstrakurikuler)
    {
        $validated = $this->validateData($request);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('extracurriculars', 'public');
        } else {
            unset($validated['photo']);
        }

        $ekstrakurikuler->update($validated);

        return redirect()->route('admin.ekstrakurikuler.index')->with('success', 'Ekstrakurikuler berhasil diperbarui.');
    }

    public function destroy(Extracurricular $ekstrakurikuler)
    {
        $ekstrakurikuler->delete();

        return redirect()->route('admin.ekstrakurikuler.index')->with('success', 'Ekstrakurikuler berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'coach' => 'nullable|string|max:255',
            'schedule_day' => 'nullable|string|max:100',
            'schedule_time' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order' => 'nullable|integer',
        ]);
    }
}
