<?php

namespace Database\Seeders;

use App\Models\AcademicCalendar;
use Illuminate\Database\Seeder;

class AcademicCalendarSeeder extends Seeder
{
    public function run(): void
    {
        $year = now()->year;

        $events = [
            ['title' => 'Awal Tahun Ajaran Baru', 'start' => "$year-07-14", 'end' => null, 'category' => 'Kegiatan', 'desc' => 'Hari pertama masuk sekolah semester ganjil.'],
            ['title' => 'Masa Pengenalan Lingkungan Sekolah (MPLS)', 'start' => "$year-07-14", 'end' => "$year-07-16", 'category' => 'Kegiatan', 'desc' => 'Kegiatan orientasi bagi peserta didik baru.'],
            ['title' => 'Peringatan HUT Kemerdekaan RI', 'start' => "$year-08-17", 'end' => null, 'category' => 'Libur', 'desc' => 'Upacara bendera dan lomba-lomba kemerdekaan.'],
            ['title' => 'Penilaian Tengah Semester Ganjil', 'start' => "$year-09-22", 'end' => "$year-09-27", 'category' => 'Ujian', 'desc' => 'Ujian tengah semester untuk seluruh kelas.'],
            ['title' => 'Pembagian Rapor Tengah Semester', 'start' => "$year-10-11", 'end' => null, 'category' => 'Kegiatan', 'desc' => null],
            ['title' => 'Penilaian Akhir Semester Ganjil', 'start' => "$year-12-01", 'end' => "$year-12-06", 'category' => 'Ujian', 'desc' => 'Ujian akhir semester ganjil.'],
            ['title' => 'Pembagian Rapor Semester Ganjil', 'start' => "$year-12-19", 'end' => null, 'category' => 'Kegiatan', 'desc' => null],
            ['title' => 'Libur Semester Ganjil', 'start' => "$year-12-22", 'end' => "$year-12-31", 'category' => 'Libur', 'desc' => 'Libur akhir semester ganjil.'],
        ];

        foreach ($events as $e) {
            AcademicCalendar::create([
                'title' => $e['title'],
                'description' => $e['desc'],
                'start_date' => $e['start'],
                'end_date' => $e['end'],
                'category' => $e['category'],
            ]);
        }
    }
}
