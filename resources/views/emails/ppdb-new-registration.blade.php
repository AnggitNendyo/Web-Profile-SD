<x-mail::message>
# Pendaftaran PPDB Baru

Yth. Admin,

Ada pendaftaran PPDB baru yang masuk ke sistem. Berikut detailnya:

<x-mail::table>
| Data | Keterangan |
|:-----|:-----------|
| **No. Pendaftaran** | {{ $registration->no_pendaftaran }} |
| **Nama Siswa** | {{ $registration->nama_siswa }} |
| **Nama Orang Tua** | {{ $registration->nama_ortu }} |
| **No. HP / WA** | {{ $registration->no_hp }} |
| **Email Orang Tua** | {{ $registration->email_ortu ?? '-' }} |
| **Alamat** | {{ $registration->alamat }} |
| **Waktu Daftar** | {{ $registration->created_at->format('d/m/Y H:i') }} |
</x-mail::table>

Silakan login ke panel admin untuk memverifikasi pendaftaran ini.

<x-mail::button :url="url('/admin/ppdb/' . $registration->id)">
Lihat Detail di Admin
</x-mail::button>

Salam,<br>
{{ config('app.name') }}
</x-mail::message>
