<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use App\Models\Facility;
use App\Models\News;
use App\Models\Page;
use App\Models\PpdbSetting;
use App\Models\SchoolSetting;
use App\Models\Teacher;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Public/Home', [
            'settings'         => SchoolSetting::allAsArray(),
            'latestNews'       => News::published()
                ->with('author')
                ->latest('published_at')
                ->take(6)
                ->get(),
            'teachers'         => Teacher::orderBy('sort_order')->take(8)->get(),
            'extracurriculars' => Extracurricular::orderBy('sort_order')->take(6)->get(),
            'facilities'       => Facility::orderBy('order_index')->take(6)->get(),
            'visiMisi'         => Page::where('slug', 'visi-misi')->first(),
            'ppdbStatus'       => PpdbSetting::registrationStatus(),
            'stats'            => [
                // Siswa & prestasi diisi manual lewat Pengaturan Sekolah; guru & ekskul dihitung otomatis.
                'students'        => (int) SchoolSetting::getValue('total_students', 0),
                'teachers'        => Teacher::count(),
                'extracurriculars'=> Extracurricular::count(),
                'achievements'    => (int) SchoolSetting::getValue('total_achievements', 0),
            ],
        ]);
    }
}
