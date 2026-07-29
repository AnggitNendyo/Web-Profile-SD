<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function edit()
    {
        return Inertia::render('Admin/Setting/Index', [
            'settings' => SchoolSetting::allAsArray(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'school_name' => 'nullable|string|max:255',
            'npsn' => 'nullable|string|max:50',
            'accreditation' => 'nullable|string|max:50',
            'established_year' => 'nullable|string|max:10',
            'total_students' => 'nullable|integer|min:0',
            'total_achievements' => 'nullable|integer|min:0',
            'motto' => 'nullable|string|max:255',
            'principal_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'map_embed_url' => 'nullable|string|max:2000',
            'facebook_url' => 'nullable|string|max:255',
            'instagram_url' => 'nullable|string|max:255',
            'youtube_url' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hero_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'banner_profile' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'banner_ppdb' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'banner_news' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'banner_downloads' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'banner_calendar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('settings', 'public');
        }
        unset($validated['logo']);

        // Background images: store uploaded file and keep its path under the same key.
        $imageKeys = ['hero_image', 'banner_profile', 'banner_ppdb', 'banner_news', 'banner_downloads', 'banner_calendar'];
        foreach ($imageKeys as $key) {
            if ($request->hasFile($key)) {
                $validated[$key] = $request->file($key)->store('settings', 'public');
            } else {
                unset($validated[$key]);
            }
        }

        foreach ($validated as $key => $value) {
            SchoolSetting::setValue($key, $value);
        }

        return redirect()->route('admin.settings.edit')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
