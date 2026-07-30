<?php

namespace App\Mail;

use App\Models\PpdbRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewPpdbRegistrationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public PpdbRegistration $registration,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[PPDB] Pendaftaran Baru - ' . $this->registration->nama_siswa,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.ppdb-new-registration',
        );
    }
}
