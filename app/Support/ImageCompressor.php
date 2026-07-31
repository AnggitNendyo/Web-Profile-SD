<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

/**
 * Kompres & konversi gambar upload menjadi WebP agar hemat storage
 * namun kualitas tetap bagus untuk ditampilkan ke user.
 *
 * File non-gambar (PDF, dokumen) tetap disimpan apa adanya lewat storeAs.
 */
class ImageCompressor
{
    /** Lebar/tinggi maksimum (px). Foto lebih besar diperkecil proporsional. */
    public const MAX_DIMENSION = 1920;

    /** Kualitas WebP (0-100). 82 = keseimbangan bagus antara ukuran & visual. */
    public const QUALITY = 82;

    /**
     * Simpan file upload ke disk. Jika gambar (jpg/png/webp/gif/bmp),
     * di-resize + dikompres ke WebP. File lain disimpan tanpa perubahan.
     *
     * @return string Path relatif file tersimpan (untuk kolom database).
     */
    public static function store(UploadedFile $file, string $directory, string $disk = 'public'): string
    {
        if (! self::isCompressibleMime($file->getMimeType())) {
            return $file->store($directory, $disk);
        }

        $encoded = self::encode($file->getRealPath());

        $path = rtrim($directory, '/') . '/' . Str::random(40) . '.webp';
        Storage::disk($disk)->put($path, $encoded);

        return $path;
    }

    /**
     * Kompres ulang file gambar yang SUDAH tersimpan di disk (mis. upload lama).
     * Menulis versi .webp baru, menghapus file lama, lalu mengembalikan path baru.
     *
     * @return string|null Path baru (.webp), atau null jika file bukan gambar
     *                     yang perlu/bisa dikompres (path lama dibiarkan utuh).
     */
    public static function recompress(string $path, string $disk = 'public'): ?string
    {
        $storage = Storage::disk($disk);

        if (! $storage->exists($path)) {
            return null;
        }

        // File .webp sudah lewat store() kita (di-resize + dikompres) → jangan
        // diproses ulang agar tidak terjadi kompresi ganda yang menurunkan kualitas.
        if (Str::endsWith(Str::lower($path), '.webp')) {
            return null;
        }

        $mime = $storage->mimeType($path) ?: '';
        if (! self::isCompressibleMime($mime)) {
            return null;
        }

        $encoded = self::encode($storage->path($path));

        $directory = trim(dirname($path), '/.');
        $newPath = ($directory === '' ? '' : $directory . '/') . Str::random(40) . '.webp';
        $storage->put($newPath, $encoded);
        $storage->delete($path);

        return $newPath;
    }

    /**
     * Decode gambar dari path absolut, perkecil bila melebihi batas, encode ke WebP.
     */
    protected static function encode(string $absolutePath): string
    {
        $manager = new ImageManager(\Intervention\Image\Drivers\Gd\Driver::class);
        $image = $manager->decodePath($absolutePath);

        // Perkecil hanya jika melebihi batas; jangan perbesar gambar kecil.
        $image->scaleDown(width: self::MAX_DIMENSION, height: self::MAX_DIMENSION);

        return (string) $image->encode(new WebpEncoder(quality: self::QUALITY));
    }

    /**
     * Apakah MIME merupakan gambar raster yang bisa dikompres oleh GD.
     * SVG dilewati (vektor — tidak perlu & tidak didukung GD).
     */
    protected static function isCompressibleMime(?string $mime): bool
    {
        return in_array($mime, [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/gif',
            'image/bmp',
        ], true);
    }
}
