<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpdbSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PpdbSettingController extends Controller
{
    public function index()
    {
        $settings = PpdbSetting::latest()->paginate(10);
        return Inertia::render('Admin/PpdbSetting/Index', [
            'settings' => $settings
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_ajaran' => 'required|string|max:50',
            'jalur' => 'required|string|max:50',
            'jadwal_buka' => 'required|date',
            'jadwal_tutup' => 'required|date|after_or_equal:jadwal_buka',
            'kuota' => 'required|integer|min:1',
            'is_active' => 'boolean'
        ]);

        PpdbSetting::create($validated);

        return redirect()->back()->with('success', 'Jalur PPDB berhasil ditambahkan.');
    }

    public function update(Request $request, PpdbSetting $ppdb_setting)
    {
        $validated = $request->validate([
            'tahun_ajaran' => 'required|string|max:50',
            'jalur' => 'required|string|max:50',
            'jadwal_buka' => 'required|date',
            'jadwal_tutup' => 'required|date|after_or_equal:jadwal_buka',
            'kuota' => 'required|integer|min:1',
            'is_active' => 'boolean'
        ]);

        $ppdb_setting->update($validated);

        return redirect()->back()->with('success', 'Jalur PPDB berhasil diperbarui.');
    }

    public function destroy(PpdbSetting $ppdb_setting)
    {
        $ppdb_setting->delete();

        return redirect()->back()->with('success', 'Jalur PPDB berhasil dihapus.');
    }
}
