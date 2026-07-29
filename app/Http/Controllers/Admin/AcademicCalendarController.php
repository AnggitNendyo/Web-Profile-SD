<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicCalendar;
use App\Models\MasterData;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AcademicCalendarController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Calendar/Index', [
            'events'     => AcademicCalendar::orderBy('start_date')->paginate(15),
            'categories' => MasterData::getByType('calendar_category'),
        ]);
    }

    public function store(Request $request)
    {
        AcademicCalendar::create($this->validated($request));

        return redirect()->route('admin.kalender.index')->with('success', 'Agenda akademik berhasil ditambahkan.');
    }

    public function update(Request $request, AcademicCalendar $kalender)
    {
        $kalender->update($this->validated($request));

        return redirect()->route('admin.kalender.index')->with('success', 'Agenda akademik berhasil diperbarui.');
    }

    public function destroy(AcademicCalendar $kalender)
    {
        $kalender->delete();

        return redirect()->route('admin.kalender.index')->with('success', 'Agenda akademik berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'category' => 'nullable|string|max:100',
        ]);
    }
}
