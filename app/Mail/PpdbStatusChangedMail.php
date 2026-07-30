<?php

namespace App\Mail;

use App\Models\PpdbRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PpdbStatusChangedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $statusLabel;
    public string $checkUrl;

    public function __construct(
        public PpdbRegistration $registration,
    ) {
        $labels = [
            'pending' => 'Menunggu Verifikasi',
            'verified' => 'Berkas Terverifikasi',
            'rejected' => 'Ditolak',
            'accepted' => 'Diterima sebagai Siswa',
        ];
        $this->statusLabel = $labels[$registration->status] ?? $registration->status;
        $this->checkUrl = route('ppdb.check');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[PPDB] Status Pendaftaran Diperbarui - ' . $this->registration->no_pendaftaran,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.ppdb-status-changed',
        );
    }
}
