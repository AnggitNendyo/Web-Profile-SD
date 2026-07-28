<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
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
                'teachers' => Teacher::count(),
                'students' => 320, // placeholder, bisa diganti dari settings
                'news' => News::published()->count(),
                'galleries' => Gallery::count(),
            ],
        ]);
    }
}
