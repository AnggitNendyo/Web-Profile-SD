<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbRegistration extends Model
{
    protected $fillable = [
        'no_pendaftaran',
        'nama_siswa',
        'nisn',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'asal_sekolah',
        'nama_ortu',
        'nama_ayah',
        'nama_ibu',
        'alamat',
        'no_hp',
        'email_ortu',
        'dokumen_upload',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (PpdbRegistration $registration) {
            if (empty($registration->no_pendaftaran)) {
                $registration->no_pendaftaran = 'PPDB-' . now()->format('Y') . '-'
                    . str_pad((string) (static::whereYear('created_at', now()->year)->count() + 1), 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
