<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = [
            ['name' => 'Drs. Bambang Sutrisno, M.Pd.', 'position' => 'Kepala Sekolah', 'subject' => null, 'nip' => '196508151990031005', 'education' => [['degree' => 'S1', 'program' => 'Pendidikan Jasmani', 'university' => 'Universitas Negeri Jakarta', 'year' => '1988'], ['degree' => 'S2', 'program' => 'Manajemen Pendidikan', 'university' => 'Universitas Negeri Jakarta', 'year' => '2005']], 'bio' => 'Berpengalaman lebih dari 20 tahun dalam dunia pendidikan. Memiliki visi untuk memajukan sekolah menjadi institusi unggulan di tingkat nasional.', 'sort_order' => 1],
            ['name' => 'Sri Wahyuni, S.Pd.', 'position' => 'Wakil Kepala Sekolah', 'subject' => 'PKn', 'nip' => '197203121995032001', 'education' => [['degree' => 'S1', 'program' => 'Pendidikan Pancasila dan Kewarganegaraan', 'university' => 'Universitas Pendidikan Indonesia', 'year' => '1995']], 'bio' => 'Aktif dalam membina karakter siswa melalui program-program ekstrakurikuler dan penegakan disiplin sekolah.', 'sort_order' => 2],
            ['name' => 'Ahmad Fauzi, S.Pd.', 'position' => 'Guru Kelas 6', 'subject' => 'Matematika', 'nip' => '198105232006041002', 'education' => [['degree' => 'S1', 'program' => 'Pendidikan Matematika', 'university' => 'Universitas Negeri Malang', 'year' => '2004']], 'bio' => 'Fokus pada pengembangan metode pembelajaran matematika yang menyenangkan dan mudah dipahami oleh siswa sekolah dasar.', 'sort_order' => 3],
            ['name' => 'Siti Nurhaliza, S.Pd.', 'position' => 'Guru Kelas 5', 'subject' => 'Bahasa Indonesia', 'nip' => '198507142008042003', 'education' => [['degree' => 'S1', 'program' => 'Pendidikan Bahasa dan Sastra Indonesia', 'university' => 'Universitas Negeri Yogyakarta', 'year' => '2007']], 'bio' => 'Mengembangkan minat baca siswa melalui program literasi sekolah dan kelas menulis kreatif.', 'sort_order' => 4],
            ['name' => 'Budi Santoso, S.Pd.', 'position' => 'Guru Kelas 4', 'subject' => 'IPA', 'nip' => '198209032009041001', 'education' => [['degree' => 'S1', 'program' => 'Pendidikan Ilmu Pengetahuan Alam', 'university' => 'Universitas Sebelas Maret', 'year' => '2005']], 'bio' => 'Mendorong siswa untuk berpikir kritis dan mencintai lingkungan melalui praktik langsung di laboratorium alam.', 'sort_order' => 5],
            ['name' => 'Ratna Dewi, S.Pd.', 'position' => 'Guru Kelas 3', 'subject' => 'IPS', 'nip' => '199001152012042001', 'education' => [['degree' => 'S1', 'program' => 'Pendidikan Sejarah', 'university' => 'Universitas Diponegoro', 'year' => '2012']], 'bio' => 'Mengenalkan sejarah dan kebudayaan lokal kepada siswa dengan pendekatan yang interaktif.', 'sort_order' => 6],
            ['name' => 'Eko Prasetyo, S.Pd.', 'position' => 'Guru Olahraga', 'subject' => 'PJOK', 'nip' => '198811072013041002', 'education' => [['degree' => 'S1', 'program' => 'Pendidikan Jasmani, Kesehatan, dan Rekreasi', 'university' => 'Universitas Negeri Surabaya', 'year' => '2011']], 'bio' => 'Melatih dan membina tim olahraga sekolah yang sering memenangkan kejuaraan tingkat kabupaten.', 'sort_order' => 7],
            ['name' => 'Dewi Kartika, S.Pd.', 'position' => 'Guru Kelas 2', 'subject' => 'Tematik', 'nip' => '199205182014042001', 'education' => [['degree' => 'S1', 'program' => 'Pendidikan Guru Sekolah Dasar (PGSD)', 'university' => 'Universitas Terbuka', 'year' => '2014']], 'bio' => 'Sangat menyukai anak-anak dan berkomitmen memberikan fondasi belajar yang kuat pada masa pertumbuhan.', 'sort_order' => 8],
            ['name' => 'Hendra Gunawan, S.Pd.I.', 'position' => 'Guru PAI', 'subject' => 'Pendidikan Agama Islam', 'nip' => '198704212010041003', 'education' => [['degree' => 'S1', 'program' => 'Pendidikan Agama Islam', 'university' => 'UIN Syarif Hidayatullah', 'year' => '2009']], 'bio' => 'Membimbing akhlak siswa dan aktif sebagai koordinator kegiatan keagamaan di lingkungan sekolah.', 'sort_order' => 9],
            ['name' => 'Lina Marlina, S.Pd.', 'position' => 'Guru Kelas 1', 'subject' => 'Tematik', 'nip' => '199308222016042001', 'education' => [['degree' => 'S1', 'program' => 'Pendidikan Guru Sekolah Dasar (PGSD)', 'university' => 'Universitas Pendidikan Indonesia', 'year' => '2015']], 'bio' => 'Membantu transisi siswa dari TK ke SD dengan pendekatan pembelajaran bermain sambil belajar yang menyenangkan.', 'sort_order' => 10],
            ['name' => 'Yanto', 'position' => 'Tenaga Administrasi', 'subject' => null, 'nip' => null, 'education' => [['degree' => 'D3', 'program' => 'Administrasi Perkantoran', 'university' => 'Politeknik Negeri Jakarta', 'year' => '2010']], 'bio' => 'Mengelola tata usaha dan administrasi sekolah dengan rapi dan teliti. Siap melayani kebutuhan administratif siswa dan wali murid.', 'sort_order' => 11],
            ['name' => 'Suparman', 'position' => 'Penjaga Sekolah', 'subject' => null, 'nip' => null, 'education' => [['degree' => 'SMA', 'program' => 'IPS', 'university' => 'SMA Negeri 1', 'year' => '1995']], 'bio' => 'Menjaga keamanan dan ketertiban lingkungan sekolah dengan penuh dedikasi agar siswa dapat belajar dengan tenang.', 'sort_order' => 12],
        ];

        foreach ($teachers as $teacher) {
            Teacher::create($teacher);
        }
    }
}
