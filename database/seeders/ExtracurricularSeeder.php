<?php

namespace Database\Seeders;

use App\Models\Extracurricular;
use Illuminate\Database\Seeder;

class ExtracurricularSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => 'Pramuka',
                'category' => 'Kepramukaan',
                'coach' => 'Kak Budi Santoso',
                'schedule_day' => 'Jumat',
                'schedule_time' => '14.00 - 16.00',
                'description' => 'Kegiatan kepramukaan untuk melatih kemandirian, kedisiplinan, dan kepemimpinan siswa.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Futsal',
                'category' => 'Olahraga',
                'coach' => 'Pak Andi Wijaya',
                'schedule_day' => 'Rabu',
                'schedule_time' => '15.00 - 17.00',
                'description' => 'Melatih kerja sama tim, sportivitas, dan kebugaran melalui olahraga futsal.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Tari Tradisional',
                'category' => 'Seni',
                'coach' => 'Bu Sari Dewi',
                'schedule_day' => 'Sabtu',
                'schedule_time' => '09.00 - 11.00',
                'description' => 'Mengenalkan dan melestarikan seni tari tradisional Nusantara kepada peserta didik.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Drumband',
                'category' => 'Seni',
                'coach' => 'Pak Rizal Fahmi',
                'schedule_day' => 'Selasa',
                'schedule_time' => '14.00 - 16.00',
                'description' => 'Melatih kekompakan dan bakat musik melalui kegiatan drumband sekolah.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Matematika Club',
                'category' => 'Akademik',
                'coach' => 'Bu Rina Marlina',
                'schedule_day' => 'Kamis',
                'schedule_time' => '13.00 - 14.30',
                'description' => 'Mengasah kemampuan berpikir logis dan mempersiapkan siswa untuk olimpiade matematika.',
                'sort_order' => 5,
            ],
            [
                'name' => 'Tahfidz Al-Qur\'an',
                'category' => 'Keagamaan',
                'coach' => 'Ustadz Ahmad Fauzi',
                'schedule_day' => 'Senin',
                'schedule_time' => '13.00 - 14.30',
                'description' => 'Membimbing siswa menghafal Al-Qur\'an dengan metode yang menyenangkan.',
                'sort_order' => 6,
            ],
        ];

        foreach ($items as $item) {
            Extracurricular::create($item);
        }
    }
}
