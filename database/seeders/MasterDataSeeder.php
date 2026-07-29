<?php

namespace Database\Seeders;

use App\Models\MasterData;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Kategori Berita
            ['type' => 'news_category',              'name' => 'Pengumuman',         'sort_order' => 1],
            ['type' => 'news_category',              'name' => 'Prestasi',           'sort_order' => 2],
            ['type' => 'news_category',              'name' => 'Kegiatan Sekolah',   'sort_order' => 3],
            ['type' => 'news_category',              'name' => 'Informasi PPDB',     'sort_order' => 4],
            ['type' => 'news_category',              'name' => 'Pendidikan',         'sort_order' => 5],
            ['type' => 'news_category',              'name' => 'Lainnya',            'sort_order' => 6],

            // Kategori Ekstrakurikuler
            ['type' => 'extracurricular_category',   'name' => 'Olahraga',           'sort_order' => 1],
            ['type' => 'extracurricular_category',   'name' => 'Seni & Budaya',      'sort_order' => 2],
            ['type' => 'extracurricular_category',   'name' => 'Akademik',           'sort_order' => 3],
            ['type' => 'extracurricular_category',   'name' => 'Keagamaan',          'sort_order' => 4],
            ['type' => 'extracurricular_category',   'name' => 'Kepramukaan',        'sort_order' => 5],
            ['type' => 'extracurricular_category',   'name' => 'Lainnya',            'sort_order' => 6],

            // Kategori Unduhan
            ['type' => 'download_category',          'name' => 'Formulir',           'sort_order' => 1],
            ['type' => 'download_category',          'name' => 'Surat & SK',         'sort_order' => 2],
            ['type' => 'download_category',          'name' => 'Panduan',            'sort_order' => 3],
            ['type' => 'download_category',          'name' => 'Kalender Sekolah',   'sort_order' => 4],
            ['type' => 'download_category',          'name' => 'Laporan',            'sort_order' => 5],
            ['type' => 'download_category',          'name' => 'Lainnya',            'sort_order' => 6],

            // Kategori Kalender Akademik
            ['type' => 'calendar_category',          'name' => 'Libur Nasional',     'sort_order' => 1],
            ['type' => 'calendar_category',          'name' => 'Libur Sekolah',      'sort_order' => 2],
            ['type' => 'calendar_category',          'name' => 'Ujian',              'sort_order' => 3],
            ['type' => 'calendar_category',          'name' => 'Penilaian',          'sort_order' => 4],
            ['type' => 'calendar_category',          'name' => 'Kegiatan Sekolah',   'sort_order' => 5],
            ['type' => 'calendar_category',          'name' => 'Penerimaan Rapor',   'sort_order' => 6],
            ['type' => 'calendar_category',          'name' => 'Lainnya',            'sort_order' => 7],
        ];

        foreach ($data as $item) {
            MasterData::firstOrCreate(
                ['type' => $item['type'], 'name' => $item['name']],
                ['sort_order' => $item['sort_order'], 'is_active' => true]
            );
        }
    }
}
