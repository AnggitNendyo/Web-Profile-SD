<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use App\Models\Gallery;
use App\Models\News;
use App\Models\PpdbSetting;
use App\Models\SchoolSetting;
use App\Models\Teacher;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Public/Home', [
            'settings' => SchoolSetting::allAsArray(),
            'latestNews' => News::published()
                ->with('author')
                ->latest('published_at')
                ->take(6)
                ->get(),
            'teachers' => Teacher::orderBy('sort_order')->take(8)->get(),
            'galleries' => Gallery::latest()->take(8)->get(),
            'ppdbSetting' => PpdbSetting::latest()->first(),
            'stats' => [
                // Siswa & prestasi diisi manual lewat Pengaturan Sekolah; guru & ekskul dihitung otomatis.
                'students' => (int) SchoolSetting::getValue('total_students', 0),
                'teachers' => Teacher::count(),
                'extracurriculars' => Extracurricular::count(),
                'achievements' => (int) SchoolSetting::getValue('total_achievements', 0),
            ],
        ]);
    }
}
