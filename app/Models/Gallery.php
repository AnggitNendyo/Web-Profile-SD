<?php

namespace App\Models;

use App\Support\YouTube;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'title',
        'type',
        'file_path',
        'category',
    ];

    protected $appends = [
        'youtube_embed_url',
        'youtube_thumbnail_url',
    ];

    /**
     * Untuk tipe video, file_path menyimpan ID YouTube.
     * URL embed dipakai iframe di lightbox publik.
     */
    protected function youtubeEmbedUrl(): Attribute
    {
        return Attribute::get(fn () => $this->type === 'video'
            ? YouTube::embedUrl($this->file_path)
            : null);
    }

    /**
     * Thumbnail otomatis dari YouTube untuk tipe video.
     */
    protected function youtubeThumbnailUrl(): Attribute
    {
        return Attribute::get(fn () => $this->type === 'video'
            ? YouTube::thumbnailUrl($this->file_path)
            : null);
    }
}
