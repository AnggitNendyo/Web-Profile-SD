<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\AcademicCalendar;
use Inertia\Inertia;

class CalendarController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        return Inertia::render('Public/Calendar', [
            'upcoming' => AcademicCalendar::upcoming()->orderBy('start_date')->get(),
            'past' => AcademicCalendar::where(function ($q) use ($today) {
                $q->whereDate('end_date', '<', $today)
                    ->orWhere(function ($q2) use ($today) {
                        $q2->whereNull('end_date')->whereDate('start_date', '<', $today);
                    });
            })->orderByDesc('start_date')->limit(20)->get(),
            // Semua agenda untuk tampilan grid bulanan.
            'all' => AcademicCalendar::orderBy('start_date')->get(),
        ]);
    }
}
