<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\SchoolSetting;
use App\Models\Facility;
use App\Models\Teacher;
use App\Models\Extracurricular;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index()
    {
        $settings = SchoolSetting::allAsArray();

        $stats = [
            'teachers_count' => Teacher::count(),
            'extracurriculars_count' => Extracurricular::count(),
            'students_count' => $settings['total_students'] ?? 0,
        ];

        return Inertia::render('Public/Profile', [
            'settings' => $settings,
            'page' => Page::where('slug', 'profil')->first(),
            'visiMisi' => Page::where('slug', 'visi-misi')->first(),
            'facilities' => Facility::orderBy('order_index')->get(),
            'dynamicStats' => $stats,
        ]);
    }
}
