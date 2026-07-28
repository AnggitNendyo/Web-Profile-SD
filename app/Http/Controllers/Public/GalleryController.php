<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Inertia\Inertia;

class GalleryController extends Controller
{
    public function index()
    {
        $query = Gallery::latest();

        if ($category = request('category')) {
            $query->where('category', $category);
        }

        if ($type = request('type')) {
            $query->where('type', $type);
        }

        return Inertia::render('Public/Gallery', [
            'galleries' => $query->paginate(12),
            'categories' => Gallery::select('category')->distinct()->whereNotNull('category')->pluck('category'),
            'filters' => request()->only(['category', 'type']),
        ]);
    }
}
