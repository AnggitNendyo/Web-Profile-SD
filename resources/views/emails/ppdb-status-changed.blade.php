<x-mail::message>
# Status Pendaftaran PPDB Diperbarui

Yth. Bapak/Ibu **{{ $registration->nama_ortu }}**,

Kami ingin memberitahukan bahwa status pendaftaran PPDB putra/putri Anda telah diperbarui:

<x-mail::panel>
**No. Pendaftaran:** {{ $registration->no_pendaftaran }}<br>
**Nama Siswa:** {{ $registration->nama_siswa }}<br>
**Status Terbaru:** {{ $statusLabel }}
</x-mail::panel>

@if($registration->notes)
**Catatan dari Panitia:**

> {{ $registration->notes }}
@endif

Anda juga dapat mengecek status pendaftaran kapan saja melalui halaman berikut:

<x-mail::button :url="$checkUrl">
Cek Status Pendaftaran
</x-mail::button>

Jika ada pertanyaan lebih lanjut, silakan hubungi pihak sekolah.

Salam hangat,<br>
Panitia PPDB {{ config('app.name') }}
</x-mail::message>
