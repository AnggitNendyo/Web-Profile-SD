<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function index()
    {
        $query = News::published()->with('author')->latest('published_at');

        if ($category = request('category')) {
            $query->where('category', $category);
        }

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%")
                  ->orWhere('content', 'ilike', "%{$search}%");
            });
        }

        return Inertia::render('Public/News/Index', [
            'news' => $query->paginate(9),
            'categories' => News::published()->select('category')->distinct()->whereNotNull('category')->pluck('category'),
            'filters' => request()->only(['category', 'search']),
        ]);
    }

    public function show(string $slug)
    {
        $news = News::published()->with('author')->where('slug', $slug)->firstOrFail();

        $related = News::published()
            ->where('id', '!=', $news->id)
            ->where('category', $news->category)
            ->latest('published_at')
            ->take(3)
            ->get();

        return Inertia::render('Public/News/Show', [
            'news' => $news,
            'related' => $related,
        ]);
    }
}
