<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = [
            ['name' => 'Drs. Bambang Sutrisno, M.Pd.', 'position' => 'Kepala Sekolah', 'subject' => null, 'nip' => '196508151990031005', 'sort_order' => 1],
            ['name' => 'Sri Wahyuni, S.Pd.', 'position' => 'Wakil Kepala Sekolah', 'subject' => 'PKn', 'nip' => '197203121995032001', 'sort_order' => 2],
            ['name' => 'Ahmad Fauzi, S.Pd.', 'position' => 'Guru Kelas 6', 'subject' => 'Matematika', 'nip' => '198105232006041002', 'sort_order' => 3],
            ['name' => 'Siti Nurhaliza, S.Pd.', 'position' => 'Guru Kelas 5', 'subject' => 'Bahasa Indonesia', 'nip' => '198507142008042003', 'sort_order' => 4],
            ['name' => 'Budi Santoso, S.Pd.', 'position' => 'Guru Kelas 4', 'subject' => 'IPA', 'nip' => '198209032009041001', 'sort_order' => 5],
            ['name' => 'Ratna Dewi, S.Pd.', 'position' => 'Guru Kelas 3', 'subject' => 'IPS', 'nip' => '199001152012042001', 'sort_order' => 6],
            ['name' => 'Eko Prasetyo, S.Pd.', 'position' => 'Guru Olahraga', 'subject' => 'PJOK', 'nip' => '198811072013041002', 'sort_order' => 7],
            ['name' => 'Dewi Kartika, S.Pd.', 'position' => 'Guru Kelas 2', 'subject' => 'Tematik', 'nip' => '199205182014042001', 'sort_order' => 8],
            ['name' => 'Hendra Gunawan, S.Pd.I.', 'position' => 'Guru PAI', 'subject' => 'Pendidikan Agama Islam', 'nip' => '198704212010041003', 'sort_order' => 9],
            ['name' => 'Lina Marlina, S.Pd.', 'position' => 'Guru Kelas 1', 'subject' => 'Tematik', 'nip' => '199308222016042001', 'sort_order' => 10],
            ['name' => 'Yanto', 'position' => 'Tenaga Administrasi', 'subject' => null, 'nip' => null, 'sort_order' => 11],
            ['name' => 'Suparman', 'position' => 'Penjaga Sekolah', 'subject' => null, 'nip' => null, 'sort_order' => 12],
        ];

        foreach ($teachers as $teacher) {
            Teacher::create($teacher);
        }
    }
}
