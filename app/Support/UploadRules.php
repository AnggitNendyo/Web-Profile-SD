<?php

namespace App\Support;

/**
 * Sumber tunggal aturan validasi unggahan (ukuran & tipe file).
 *
 * Semua controller memakai helper ini agar batas ukuran konsisten dan
 * mudah diubah dari satu tempat. Ukuran dalam kilobyte (satuan rule Laravel `max:`).
 */
class UploadRules
{
    /** Batas ukuran gambar mentah sebelum dikompres (KB). 5 MB. */
    public const IMAGE_MAX_KB = 5120;

    /** Batas ukuran dokumen unduhan: PDF/Office/ZIP (KB). 10 MB. */
    public const DOCUMENT_MAX_KB = 10240;

    /** Batas ukuran dokumen PPDB: PDF atau gambar (KB). 5 MB. */
    public const PPDB_DOC_MAX_KB = 5120;

    /** Ekstensi gambar yang diterima. */
    public const IMAGE_MIMES = 'jpg,jpeg,png,webp';

    /**
     * Aturan untuk field gambar. Otomatis dikompres ke WebP setelah lolos.
     *
     * @param  bool  $required  true untuk wajib diisi, false untuk opsional.
     */
    public static function image(bool $required = false): string
    {
        $presence = $required ? 'required' : 'nullable';

        return "{$presence}|image|mimes:" . self::IMAGE_MIMES . '|max:' . self::IMAGE_MAX_KB;
    }

    /**
     * Aturan untuk dokumen unduhan (PDF, Office, ZIP).
     */
    public static function document(bool $required = true): string
    {
        $presence = $required ? 'required' : 'nullable';

        return "{$presence}|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip|max:" . self::DOCUMENT_MAX_KB;
    }

    /**
     * Aturan untuk dokumen PPDB: boleh PDF atau gambar.
     */
    public static function ppdbDocument(bool $required = false): string
    {
        $presence = $required ? 'required' : 'nullable';

        return "{$presence}|file|mimes:pdf,jpg,jpeg,png|max:" . self::PPDB_DOC_MAX_KB;
    }
}
