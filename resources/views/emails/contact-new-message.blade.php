<x-mail::message>
    # Pesan Kontak Baru

    Yth. Admin,

    Ada pesan kontak baru yang masuk melalui website:

    <x-mail::table>
        | Data | Keterangan |
        |:-----|:-----------|
        | **Nama** | {{ $contactMessage->name }} |
        | **Email** | {{ $contactMessage->email }} |
        | **Subjek** | {{ $contactMessage->subject }} |
        | **Waktu** | {{ $contactMessage->created_at->format('d/m/Y H:i') }} |
    </x-mail::table>

    **Isi Pesan:**
    > {{ $contactMessage->message }}

    <x-mail::button :url="url('/admin/pesan/' . $contactMessage->id)">
        Lihat di Admin Panel
    </x-mail::button>

    Anda dapat membalas langsung ke email pengirim: **{{ $contactMessage->email }}**

    Salam,<br>
    {{ config('app.name') }}
</x-mail::message>