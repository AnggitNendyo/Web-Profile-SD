<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterData;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MasterDataController extends Controller
{
    public function index()
    {
        // Kelompokkan semua data per type, sudah diurutkan
        $grouped = collect(MasterData::types())->mapWithKeys(function ($label, $type) {
            return [
                $type => MasterData::ofType($type)
                    ->ordered()
                    ->get(['id', 'type', 'name', 'sort_order', 'is_active']),
            ];
        });

        return Inertia::render('Admin/MasterData/Index', [
            'grouped' => $grouped,
            'types'   => MasterData::types(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'       => ['required', 'string', 'in:' . implode(',', array_keys(MasterData::types()))],
            'name'       => ['required', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        // Cegah duplikat dalam type yang sama
        if (MasterData::ofType($validated['type'])->where('name', $validated['name'])->exists()) {
            return back()->withErrors(['name' => 'Nama ini sudah ada dalam kategori tersebut.']);
        }

        MasterData::create([
            'type'       => $validated['type'],
            'name'       => $validated['name'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active'  => true,
        ]);

        return back()->with('success', 'Data berhasil ditambahkan.');
    }

    public function update(Request $request, MasterData $masterDatum)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active'  => ['boolean'],
        ]);

        // Cegah duplikat (kecuali dirinya sendiri)
        if (MasterData::ofType($masterDatum->type)
            ->where('name', $validated['name'])
            ->where('id', '!=', $masterDatum->id)
            ->exists()) {
            return back()->withErrors(['name' => 'Nama ini sudah ada dalam kategori tersebut.']);
        }

        $masterDatum->update($validated);

        return back()->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(MasterData $masterDatum)
    {
        $masterDatum->delete();

        return back()->with('success', 'Data berhasil dihapus.');
    }
}
