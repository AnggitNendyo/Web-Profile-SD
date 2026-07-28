<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();

        $articles = [
            [
                'title' => 'Selamat Datang di Tahun Ajaran Baru 2026/2027',
                'content' => '<p>Dengan penuh rasa syukur, kami menyambut seluruh siswa-siswi SD Negeri 1 Nusantara di tahun ajaran baru 2026/2027. Semoga tahun ini menjadi tahun yang penuh prestasi dan kegembiraan bagi seluruh warga sekolah.</p><p>Pada tahun ajaran ini, sekolah telah menyiapkan berbagai program unggulan baru yang akan menunjang proses pembelajaran siswa. Termasuk di antaranya program literasi digital, English Day setiap Kamis, dan ekstrakurikuler robotika.</p><p>Kami mengajak seluruh orang tua dan wali murid untuk bersama-sama mendukung proses pendidikan anak-anak kita menuju generasi yang cerdas, berkarakter, dan berprestasi.</p>',
                'category' => 'Pengumuman',
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => 'SD Negeri 1 Nusantara Raih Juara 1 Olimpiade Sains Tingkat Kabupaten',
                'content' => '<p>Kebanggaan besar bagi SD Negeri 1 Nusantara! Siswa kelas 6, Anisa Rahma Putri, berhasil meraih Juara 1 dalam Olimpiade Sains Nasional (OSN) tingkat Kabupaten Sejahtera bidang Matematika.</p><p>Anisa berhasil mengalahkan 150 peserta dari 45 sekolah dasar se-kabupaten. Prestasi ini merupakan buah dari kerja keras dan bimbingan intensif dari guru pembimbing, Bapak Ahmad Fauzi, S.Pd.</p><p>\"Saya sangat bangga dengan pencapaian Anisa. Ini membuktikan bahwa siswa-siswi SD Negeri 1 Nusantara mampu bersaing di tingkat kabupaten,\" ujar Kepala Sekolah, Drs. Bambang Sutrisno, M.Pd.</p>',
                'category' => 'Prestasi',
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Perayaan Hari Pendidikan Nasional 2026',
                'content' => '<p>SD Negeri 1 Nusantara memperingati Hari Pendidikan Nasional (Hardiknas) 2026 dengan menggelar upacara bendera dan berbagai lomba antar kelas. Tema peringatan tahun ini adalah \"Bergerak Bersama, Lanjutkan Merdeka Belajar\".</p><p>Rangkaian kegiatan meliputi upacara bendera di pagi hari yang diikuti seluruh warga sekolah, dilanjutkan dengan lomba membaca puisi, lomba menggambar, dan lomba cerdas cermat antar kelas.</p><p>Kegiatan ini bertujuan untuk menumbuhkan semangat belajar dan cinta tanah air pada diri setiap siswa.</p>',
                'category' => 'Kegiatan',
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Program Makan Bergizi Gratis Resmi Diluncurkan',
                'content' => '<p>Mulai bulan ini, SD Negeri 1 Nusantara resmi melaksanakan Program Makan Bergizi Gratis (MBG) dari pemerintah. Program ini memberikan makan siang bergizi kepada seluruh siswa setiap hari sekolah.</p><p>Menu yang disajikan telah dirancang oleh ahli gizi dan bervariasi setiap harinya. Program ini diharapkan dapat meningkatkan konsentrasi belajar dan status gizi siswa.</p><p>Orang tua diimbau untuk tetap memastikan anak-anak sarapan di rumah sebelum berangkat ke sekolah.</p>',
                'category' => 'Pengumuman',
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Kunjungan Industri ke Museum dan Kebun Binatang',
                'content' => '<p>Dalam rangka menunjang pembelajaran tematik, SD Negeri 1 Nusantara mengadakan kunjungan industri untuk siswa kelas 4, 5, dan 6 ke Museum Ranggawarsita dan Kebun Binatang Mangkang, Semarang.</p><p>Kegiatan ini bertujuan untuk memberikan pengalaman belajar langsung kepada siswa tentang sejarah dan keanekaragaman hayati. Siswa sangat antusias dan mendapat banyak pengetahuan baru dari kunjungan ini.</p>',
                'category' => 'Kegiatan',
                'published_at' => now()->subDays(14),
            ],
            [
                'title' => 'Pengumuman Jadwal UAS Semester Ganjil 2026/2027',
                'content' => '<p>Berikut kami sampaikan jadwal Ujian Akhir Semester (UAS) Ganjil Tahun Ajaran 2026/2027 yang akan dilaksanakan pada tanggal 8-13 Desember 2026.</p><p>Siswa diharapkan mempersiapkan diri dengan baik. Orang tua dimohon untuk mengawasi jadwal belajar anak di rumah selama masa persiapan ujian.</p><p>Jadwal lengkap per mata pelajaran dan kelas dapat diunduh melalui halaman Unduhan di website ini.</p>',
                'category' => 'Pengumuman',
                'published_at' => now()->subDays(20),
            ],
        ];

        foreach ($articles as $article) {
            $article['slug'] = Str::slug($article['title']) . '-' . Str::random(5);
            $article['author_id'] = $admin->id;
            News::create($article);
        }
    }
}
