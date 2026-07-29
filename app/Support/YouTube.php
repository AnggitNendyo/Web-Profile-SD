<?php

namespace App\Support;

class YouTube
{
    /**
     * Ekstrak ID video YouTube dari berbagai format URL (atau ID mentah).
     *
     * Mendukung:
     *  - https://www.youtube.com/watch?v=VIDEO_ID
     *  - https://youtu.be/VIDEO_ID
     *  - https://www.youtube.com/embed/VIDEO_ID
     *  - https://www.youtube.com/shorts/VIDEO_ID
     *  - VIDEO_ID (11 karakter) langsung
     */
    public static function extractId(?string $input): ?string
    {
        $input = trim((string) $input);

        if ($input === '') {
            return null;
        }

        // Sudah berupa ID mentah (11 karakter alfanumerik + - _).
        if (preg_match('/^[A-Za-z0-9_-]{11}$/', $input)) {
            return $input;
        }

        $patterns = [
            '/(?:youtube\.com\/watch\?(?:.*&)?v=)([A-Za-z0-9_-]{11})/',
            '/(?:youtu\.be\/)([A-Za-z0-9_-]{11})/',
            '/(?:youtube\.com\/embed\/)([A-Za-z0-9_-]{11})/',
            '/(?:youtube\.com\/shorts\/)([A-Za-z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * URL embed untuk iframe.
     */
    public static function embedUrl(?string $id): ?string
    {
        return $id ? "https://www.youtube.com/embed/{$id}" : null;
    }

    /**
     * URL thumbnail resolusi tinggi.
     */
    public static function thumbnailUrl(?string $id): ?string
    {
        return $id ? "https://img.youtube.com/vi/{$id}/hqdefault.jpg" : null;
    }
}
