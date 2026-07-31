<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbSetting extends Model
{
    protected $fillable = [
        'tahun_ajaran',
        'jalur',
        'kuota',
        'jadwal_buka',
        'jadwal_tutup',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'jadwal_buka' => 'date',
            'jadwal_tutup' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Jumlah pendaftar untuk jalur ini (dihitung dari registrasi yang tidak ditolak).
     */
    public function terpakai(): int
    {
        return PpdbRegistration::where('status', '!=', 'rejected')->count();
    }

    /**
     * Tentukan status pendaftaran keseluruhan berdasarkan jalur aktif,
     * jadwal buka/tutup, dan kuota. Mengembalikan array yang dipakai
     * baik oleh guard di controller maupun tampilan di form publik.
     *
     * state: 'open' | 'closed' | 'not_started' | 'ended' | 'full'
     */
    public static function registrationStatus(): array
    {
        $active = static::where('is_active', true)->latest()->get();

        if ($active->isEmpty()) {
            return [
                'open' => false,
                'state' => 'closed',
                'message' => 'Pendaftaran belum dibuka saat ini. Silakan pantau pengumuman resmi sekolah.',
                'tracks' => [],
            ];
        }

        $today = now()->startOfDay();
        
        $tracksData = $active->map(function ($track) {
            return [
                'id' => $track->id,
                'jalur' => $track->jalur,
                'kuota' => $track->kuota,
                'jadwal_buka' => $track->jadwal_buka?->toDateString(),
                'jadwal_tutup' => $track->jadwal_tutup?->toDateString(),
            ];
        })->values()->toArray();

        // Jalur dengan jadwal paling awal buka menentukan tanggal mulai.
        $earliestOpen = $active->filter(fn ($s) => $s->jadwal_buka)->min('jadwal_buka');
        if ($earliestOpen && $today->lt($earliestOpen)) {
            return [
                'open' => false,
                'state' => 'not_started',
                'message' => 'Pendaftaran akan dibuka pada ' . $earliestOpen->translatedFormat('d F Y') . '.',
                'jadwal_buka' => $earliestOpen->toDateString(),
                'tracks' => $tracksData,
            ];
        }

        // Semua jalur sudah lewat tanggal tutup?
        $latestClose = $active->filter(fn ($s) => $s->jadwal_tutup)->max('jadwal_tutup');
        if ($latestClose && $today->gt($latestClose)) {
            return [
                'open' => false,
                'state' => 'ended',
                'message' => 'Masa pendaftaran telah berakhir pada ' . $latestClose->translatedFormat('d F Y') . '.',
                'jadwal_tutup' => $latestClose->toDateString(),
                'tracks' => $tracksData,
            ];
        }

        // Kuota total dari seluruh jalur aktif.
        $totalKuota = (int) $active->sum('kuota');
        $terpakai = PpdbRegistration::where('status', '!=', 'rejected')->count();

        if ($totalKuota > 0 && $terpakai >= $totalKuota) {
            return [
                'open' => false,
                'state' => 'full',
                'message' => 'Kuota pendaftaran telah terpenuhi. Terima kasih atas antusiasme Anda.',
                'kuota' => $totalKuota,
                'terpakai' => $terpakai,
                'tracks' => $tracksData,
            ];
        }

        return [
            'open' => true,
            'state' => 'open',
            'message' => 'Pendaftaran sedang dibuka.',
            'kuota' => $totalKuota,
            'terpakai' => $terpakai,
            'sisa' => $totalKuota > 0 ? max(0, $totalKuota - $terpakai) : null,
            'jadwal_buka' => $earliestOpen?->toDateString(),
            'jadwal_tutup' => $latestClose?->toDateString(),
            'tracks' => $tracksData,
        ];
    }
}
