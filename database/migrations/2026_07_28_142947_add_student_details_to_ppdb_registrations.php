<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->string('nisn', 20)->nullable()->after('nama_siswa');
            $table->string('nik', 20)->nullable()->after('nisn');
            $table->string('tempat_lahir')->nullable()->after('nik');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->string('jenis_kelamin', 20)->nullable()->after('tanggal_lahir');
            $table->string('asal_sekolah')->nullable()->after('jenis_kelamin');
            $table->string('nama_ayah')->nullable()->after('nama_ortu');
            $table->string('nama_ibu')->nullable()->after('nama_ayah');
        });
    }

    public function down(): void
    {
        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'nisn',
                'nik',
                'tempat_lahir',
                'tanggal_lahir',
                'jenis_kelamin',
                'asal_sekolah',
                'nama_ayah',
                'nama_ibu',
            ]);
        });
    }
};
