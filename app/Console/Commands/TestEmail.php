<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    /**
     * Contoh: php artisan mail:test anggitnendyo9@gmail.com
     */
    protected $signature = 'mail:test {email : Alamat email tujuan}';

    protected $description = 'Kirim email tes untuk memastikan konfigurasi SMTP berfungsi';

    public function handle(): int
    {
        $email = $this->argument('email');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error("Alamat email tidak valid: {$email}");

            return self::FAILURE;
        }

        $this->info("Mengirim email tes ke {$email} ...");
        $this->line('Mailer   : ' . config('mail.default'));
        $this->line('Host     : ' . config('mail.mailers.smtp.host'));
        $this->line('Pengirim : ' . config('mail.from.address'));

        try {
            Mail::raw(
                'Ini email tes dari Web Profile SD. Jika Anda menerima pesan ini, konfigurasi email sudah berfungsi dengan benar.',
                function ($message) use ($email) {
                    $message->to($email)->subject('Tes Email — Web Profile SD');
                }
            );
        } catch (\Throwable $e) {
            $this->error('GAGAL mengirim email:');
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->newLine();
        $this->info('✔ Email tes terkirim tanpa error. Silakan cek inbox (dan folder Spam).');

        return self::SUCCESS;
    }
}
