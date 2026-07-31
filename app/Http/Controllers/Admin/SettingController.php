<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolSetting;
use App\Support\ImageCompressor;
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
            'principal_greeting' => 'nullable|string|max:2000',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'map_embed_url' => 'nullable|string|max:2000',
            'facebook_url' => 'nullable|string|max:255',
            'instagram_url' => 'nullable|string|max:255',
            'youtube_url' => 'nullable|string|max:255',
            'logo' => \App\Support\UploadRules::image(),
            'principal_photo' => \App\Support\UploadRules::image(),
            'hero_image' => \App\Support\UploadRules::image(),
            'banner_profile' => \App\Support\UploadRules::image(),
            'banner_ppdb' => \App\Support\UploadRules::image(),
            'banner_news' => \App\Support\UploadRules::image(),
            'banner_downloads' => \App\Support\UploadRules::image(),
            'banner_calendar' => \App\Support\UploadRules::image(),
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = ImageCompressor::store($request->file('logo'), 'settings');
        }
        unset($validated['logo']);

        if ($request->hasFile('principal_photo')) {
            $validated['principal_photo'] = ImageCompressor::store($request->file('principal_photo'), 'settings');
        } else {
            unset($validated['principal_photo']);
        }

        // Background images: store uploaded file and keep its path under the same key.
        $imageKeys = ['hero_image', 'banner_profile', 'banner_ppdb', 'banner_news', 'banner_downloads', 'banner_calendar'];
        foreach ($imageKeys as $key) {
            if ($request->hasFile($key)) {
                $validated[$key] = ImageCompressor::store($request->file($key), 'settings');
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
