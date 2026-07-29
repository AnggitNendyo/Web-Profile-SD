<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use App\Models\SchoolSetting;
use Inertia\Inertia;

class ExtracurricularController extends Controller
{
    public function index()
    {
        return Inertia::render('Public/Extracurriculars', [
            'extracurriculars' => Extracurricular::orderBy('sort_order')->get(),
            'settings' => SchoolSetting::allAsArray(),
        ]);
    }
}
