<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AcademicCalendar extends Model
{
    /**
     * Kategori agenda akademik. Dipakai untuk validasi & pewarnaan di UI.
     */
    public const CATEGORIES = ['Kegiatan', 'Ujian', 'Libur', 'Pendaftaran', 'Rapat'];

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'category',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    /**
     * Agenda yang belum berakhir: gunakan end_date bila ada, jika tidak
     * jatuh ke start_date. Diurutkan dari yang paling dekat.
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query->where(function (Builder $q) use ($today) {
            $q->whereDate('end_date', '>=', $today)
                ->orWhere(function (Builder $q2) use ($today) {
                    $q2->whereNull('end_date')->whereDate('start_date', '>=', $today);
                });
        });
    }
}
