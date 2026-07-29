<?php

namespace App\Models;

use App\Support\YouTube;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class News extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'thumbnail',
        'video_url',
        'category',
        'published_at',
        'author_id',
    ];

    protected $appends = [
        'youtube_embed_url',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    /**
     * video_url menyimpan ID YouTube; URL embed dipakai iframe di halaman berita.
     */
    protected function youtubeEmbedUrl(): Attribute
    {
        return Attribute::get(fn () => YouTube::embedUrl($this->video_url));
    }

    protected static function booted(): void
    {
        static::creating(function (News $news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }
}
