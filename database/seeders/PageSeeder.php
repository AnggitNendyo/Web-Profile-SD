<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PpdbSetting;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::create([
            'slug' => 'profil',
            'title' => 'Profil Sekolah',
            'content' => '<h2>Sejarah Singkat</h2><p>SD Negeri 1 Nusantara didirikan pada tahun 1985 berdasarkan Surat Keputusan Bupati Kabupaten Sejahtera. Sejak berdiri, sekolah ini telah mencetak ribuan lulusan yang berkualitas dan berprestasi di berbagai bidang.</p><p>Berlokasi di jantung Kecamatan Maju, sekolah ini terus berkembang dari waktu ke waktu. Dengan fasilitas yang terus ditingkatkan dan tenaga pendidik yang profesional, SD Negeri 1 Nusantara berkomitmen untuk memberikan pendidikan terbaik bagi generasi penerus bangsa.</p><h2>Struktur Organisasi</h2><p>Sekolah dipimpin oleh Kepala Sekolah yang dibantu oleh Wakil Kepala Sekolah, Koordinator Kurikulum, Koordinator Kesiswaan, dan Koordinator Sarana Prasarana.</p><h2>Akreditasi</h2><p>SD Negeri 1 Nusantara telah meraih Akreditasi <strong>A</strong> (Unggul) dari Badan Akreditasi Nasional Sekolah/Madrasah (BAN-S/M) pada tahun 2024 dengan nilai 93.</p>',
        ]);

        Page::create([
            'slug' => 'visi-misi',
            'title' => 'Visi & Misi',
            'content' => '<h2>Visi</h2><p>\"Terwujudnya peserta didik yang <strong>cerdas, berkarakter, dan berprestasi</strong> berdasarkan iman dan taqwa.\"</p><h2>Misi</h2><ol><li>Melaksanakan pembelajaran aktif, kreatif, efektif, dan menyenangkan (PAKEM)</li><li>Menumbuhkan semangat keunggulan secara intensif kepada seluruh warga sekolah</li><li>Mendorong dan membantu siswa mengenali potensi diri agar berkembang optimal</li><li>Menumbuhkan penghayatan terhadap ajaran agama dan budaya bangsa</li><li>Menerapkan manajemen partisipatif dengan melibatkan seluruh warga sekolah dan stakeholder</li><li>Mengembangkan budaya literasi, numerasi, dan teknologi informasi</li></ol><h2>Tujuan</h2><p>Menghasilkan lulusan yang memiliki kompetensi akademik dan non-akademik yang tinggi, berkarakter mulia, serta siap melanjutkan pendidikan ke jenjang yang lebih tinggi.</p>',
        ]);

        // PPDB Settings
        PpdbSetting::create([
            'tahun_ajaran' => '2026/2027',
            'jalur' => 'Zonasi',
            'kuota' => 60,
            'jadwal_buka' => '2026-06-01',
            'jadwal_tutup' => '2026-07-15',
        ]);

        PpdbSetting::create([
            'tahun_ajaran' => '2026/2027',
            'jalur' => 'Prestasi',
            'kuota' => 15,
            'jadwal_buka' => '2026-06-01',
            'jadwal_tutup' => '2026-07-10',
        ]);

        PpdbSetting::create([
            'tahun_ajaran' => '2026/2027',
            'jalur' => 'Perpindahan',
            'kuota' => 5,
            'jadwal_buka' => '2026-06-15',
            'jadwal_tutup' => '2026-07-15',
        ]);
    }
}
