<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class MasterData extends Model
{
    protected $table = 'master_data';

    protected $fillable = [
        'type',
        'name',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active'  => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    // ------- Scopes -------

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // ------- Helpers -------

    /**
     * Ambil semua nama aktif untuk type tertentu (untuk dropdown).
     */
    public static function getByType(string $type): Collection
    {
        return static::ofType($type)->active()->ordered()->pluck('name');
    }

    /**
     * Definisi semua tipe master data yang dikenali aplikasi.
     */
    public static function types(): array
    {
        return [
            'news_category'             => 'Kategori Berita',
            'extracurricular_category'  => 'Kategori Ekstrakurikuler',
            'download_category'         => 'Kategori Unduhan',
            'calendar_category'         => 'Kategori Kalender Akademik',
        ];
    }
}
