// Batas ukuran unggahan sisi klien — HARUS selaras dengan App\Support\UploadRules (backend).
// Memberi pesan error cepat sebelum file besar dikirim ke server.

export const IMAGE_MAX_MB = 5;
export const DOCUMENT_MAX_MB = 10;
export const PPDB_DOC_MAX_MB = 5;

/**
 * Validasi ukuran file. Mengembalikan pesan error (string) jika melebihi batas,
 * atau null jika ukuran valid.
 *
 * @param {File} file
 * @param {number} maxMb  Batas dalam megabyte.
 */
export function checkFileSize(file, maxMb) {
    if (!file) return null;
    const maxBytes = maxMb * 1024 * 1024;
    if (file.size > maxBytes) {
        const sizeMb = (file.size / 1024 / 1024).toFixed(1);
        return `Ukuran file ${sizeMb} MB melebihi batas ${maxMb} MB. Silakan pilih file yang lebih kecil.`;
    }
    return null;
}
