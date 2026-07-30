<x-mail::message>
# Pendaftaran PPDB Berhasil

Yth. Bapak/Ibu **{{ $registration->nama_ortu }}**,

Terima kasih, pendaftaran PPDB untuk putra/putri Anda telah berhasil kami terima. Berikut adalah detail pendaftaran Anda:

<x-mail::panel>
**No. Pendaftaran:** {{ $registration->no_pendaftaran }}<br>
**Nama Siswa:** {{ $registration->nama_siswa }}<br>
**Status:** Menunggu Verifikasi
</x-mail::panel>

Harap simpan **No. Pendaftaran** ini dengan baik. Anda dapat mengecek status pendaftaran putra/putri Anda kapan saja melalui halaman berikut:

<x-mail::button :url="$checkUrl">
Cek Status Pendaftaran
</x-mail::button>

Jika ada pertanyaan lebih lanjut, silakan hubungi pihak sekolah.

Salam hangat,<br>
Panitia PPDB {{ config('app.name') }}
</x-mail::message>
