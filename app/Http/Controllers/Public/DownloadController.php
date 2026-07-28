<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Download;
use Inertia\Inertia;

class DownloadController extends Controller
{
    public function index()
    {
        $query = Download::latest();

        if ($category = request('category')) {
            $query->where('category', $category);
        }

        return Inertia::render('Public/Downloads', [
            'downloads' => $query->paginate(12),
            'categories' => Download::select('category')->distinct()->whereNotNull('category')->pluck('category'),
            'filters' => request()->only(['category']),
        ]);
    }
}
