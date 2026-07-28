<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Gallery;
use App\Models\News;
use App\Models\PpdbRegistration;
use App\Models\Teacher;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'totalNews' => News::count(),
                'publishedNews' => News::published()->count(),
                'totalTeachers' => Teacher::count(),
                'totalGalleries' => Gallery::count(),
                'pendingPpdb' => PpdbRegistration::where('status', 'pending')->count(),
                'verifiedPpdb' => PpdbRegistration::where('status', 'verified')->count(),
                'totalPpdb' => PpdbRegistration::count(),
                'unreadMessages' => ContactMessage::unread()->count(),
                'totalMessages' => ContactMessage::count(),
            ],
            'latestNews' => News::with('author')->latest()->take(5)->get(),
            'latestRegistrations' => PpdbRegistration::latest()->take(5)->get(),
            'latestMessages' => ContactMessage::latest()->take(5)->get(),
        ]);
    }
}
