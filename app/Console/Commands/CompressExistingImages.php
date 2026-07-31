<?php

namespace App\Console\Commands;

use App\Models\Extracurricular;
use App\Models\Facility;
use App\Models\Gallery;
use App\Models\News;
use App\Models\PpdbRegistration;
use App\Models\SchoolSetting;
use App\Models\Student;
use App\Models\Teacher;
use App\Support\ImageCompressor;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

#[Signature('images:compress {--dry-run : Tampilkan apa yang akan diproses tanpa mengubah apa pun}')]
#[Description('Kompres ulang gambar lama yang sudah tersimpan menjadi WebP (hemat storage).')]
class CompressExistingImages extends Command
{
    /** Byte yang berhasil dihemat secara total. */
    protected int $bytesSaved = 0;

    protected int $processed = 0;

    protected int $skipped = 0;

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');

        if ($dry) {
            $this->warn('Mode DRY-RUN: tidak ada file/database yang diubah.');
        }

        // Model dengan kolom path tunggal: [ModelClass, kolom]
        $models = [
            [Teacher::class, 'photo'],
            [Student::class, 'photo'],
            [Facility::class, 'photo'],
            [Extracurricular::class, 'photo'],
            [News::class, 'thumbnail'],
        ];

        foreach ($models as [$class, $column]) {
            $this->processModel($class, $column, $dry);
        }

        $this->processGallery($dry);
        $this->processPpdb($dry);
        $this->processSettings($dry);

        $this->newLine();
        $this->info(sprintf(
            'Selesai. Diproses: %d, dilewati: %d, hemat: %s.',
            $this->processed,
            $this->skipped,
            $this->humanBytes($this->bytesSaved),
        ));

        return self::SUCCESS;
    }

    /**
     * Proses model Eloquent dengan satu kolom path gambar.
     */
    protected function processModel(string $class, string $column, bool $dry): void
    {
        $label = class_basename($class);

        $class::whereNotNull($column)->where($column, '!=', '')->chunkById(100, function ($rows) use ($column, $dry, $label) {
            foreach ($rows as $row) {
                $this->recompressPath($row->{$column}, $dry, "{$label}#{$row->getKey()}", function ($newPath) use ($row, $column) {
                    $row->update([$column => $newPath]);
                });
            }
        });
    }

    /**
     * Galeri: hanya baris bertipe 'foto' (tipe 'video' menyimpan ID YouTube, bukan file).
     */
    protected function processGallery(bool $dry): void
    {
        Gallery::where('type', 'foto')->whereNotNull('file_path')->chunkById(100, function ($rows) use ($dry) {
            foreach ($rows as $row) {
                $this->recompressPath($row->file_path, $dry, "Gallery#{$row->id}", function ($newPath) use ($row) {
                    $row->update(['file_path' => $newPath]);
                });
            }
        });
    }

    /**
     * PPDB: dokumen_upload bisa berupa PDF (dilewati helper) atau gambar (dikompres).
     */
    protected function processPpdb(bool $dry): void
    {
        PpdbRegistration::whereNotNull('dokumen_upload')->where('dokumen_upload', '!=', '')
            ->chunkById(100, function ($rows) use ($dry) {
                foreach ($rows as $row) {
                    $this->recompressPath($row->dokumen_upload, $dry, "Ppdb#{$row->id}", function ($newPath) use ($row) {
                        $row->update(['dokumen_upload' => $newPath]);
                    });
                }
            });
    }

    /**
     * Pengaturan sekolah: sejumlah key menyimpan path gambar (key-value store).
     */
    protected function processSettings(bool $dry): void
    {
        $keys = [
            'logo_path', 'principal_photo', 'hero_image',
            'banner_profile', 'banner_ppdb', 'banner_news',
            'banner_downloads', 'banner_calendar',
        ];

        foreach ($keys as $key) {
            $value = SchoolSetting::getValue($key);
            if (blank($value)) {
                continue;
            }

            $this->recompressPath($value, $dry, "Setting:{$key}", function ($newPath) use ($key) {
                SchoolSetting::setValue($key, $newPath);
            });
        }
    }

    /**
     * Kompres satu path; catat penghematan; jalankan $persist jika berhasil.
     *
     * @param  callable(string):void  $persist  Menyimpan path baru ke database.
     */
    protected function recompressPath(?string $path, bool $dry, string $label, callable $persist): void
    {
        if (blank($path)) {
            return;
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            $this->line("  <fg=yellow>lewati</> {$label}: file tidak ditemukan ({$path})");
            $this->skipped++;

            return;
        }

        if ($dry) {
            $lower = strtolower($path);
            $isImage = ! str_ends_with($lower, '.webp')
                && ! str_ends_with($lower, '.pdf')
                && in_array($disk->mimeType($path), ['image/jpeg', 'image/png', 'image/gif', 'image/bmp'], true);
            if ($isImage) {
                $this->line("  <fg=cyan>akan dikompres</> {$label}: {$path} (" . $this->humanBytes($disk->size($path)) . ')');
                $this->processed++;
            } else {
                $this->skipped++;
            }

            return;
        }

        $oldSize = $disk->size($path);
        // File .webp atau non-gambar (PDF) tidak diproses — recompress() kembalikan null.
        $newPath = ImageCompressor::recompress($path);

        if ($newPath === null) {
            $this->skipped++;

            return;
        }

        $newSize = $disk->size($newPath);
        $persist($newPath);

        $saved = max(0, $oldSize - $newSize);
        $this->bytesSaved += $saved;
        $this->processed++;

        $this->line(sprintf(
            '  <fg=green>ok</> %s: %s -> %s (hemat %s)',
            $label,
            $this->humanBytes($oldSize),
            $this->humanBytes($newSize),
            $this->humanBytes($saved),
        ));
    }

    protected function humanBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }
        $units = ['KB', 'MB', 'GB'];
        $value = $bytes / 1024;
        foreach ($units as $unit) {
            if ($value < 1024) {
                return sprintf('%.1f %s', $value, $unit);
            }
            $value /= 1024;
        }

        return sprintf('%.1f TB', $value);
    }
}
