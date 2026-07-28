<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\SchoolSetting;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index()
    {
        return Inertia::render('Public/Profile', [
            'settings' => SchoolSetting::allAsArray(),
            'page' => Page::where('slug', 'profil')->first(),
            'visiMisi' => Page::where('slug', 'visi-misi')->first(),
        ]);
    }
}
