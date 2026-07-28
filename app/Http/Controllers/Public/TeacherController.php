<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Inertia\Inertia;

class TeacherController extends Controller
{
    public function index()
    {
        return Inertia::render('Public/Teachers', [
            'teachers' => Teacher::orderBy('sort_order')->get(),
        ]);
    }
}
