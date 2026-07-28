<?php

namespace Database\Seeders;

use App\Models\SchoolSetting;
use Illuminate\Database\Seeder;

class SchoolSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'school_name' => 'SD Negeri 1 Nusantara',
            'address' => 'Jl. Pendidikan No. 123, Kecamatan Maju, Kabupaten Sejahtera, Provinsi Jawa Tengah 50123',
            'phone' => '(024) 1234567',
            'email' => 'info@sdnegeri1nusantara.sch.id',
            'social_instagram' => 'https://instagram.com/sdn1nusantara',
            'social_facebook' => 'https://facebook.com/sdn1nusantara',
            'social_youtube' => 'https://youtube.com/@sdn1nusantara',
            'map_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.0!2d110.4!3d-6.9!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1',
            'npsn' => '20301234',
            'accreditation' => 'A',
            'established_year' => '1985',
            'total_students' => '320',
            'motto' => 'Cerdas, Berkarakter, dan Berprestasi',
            'principal_name' => 'Drs. Bambang Sutrisno, M.Pd.',
        ];

        foreach ($settings as $key => $value) {
            SchoolSetting::create(['key' => $key, 'value' => $value]);
        }
    }
}
