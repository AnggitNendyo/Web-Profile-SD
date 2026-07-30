<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\NewContactMessageMail;
use App\Models\ContactMessage;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        return Inertia::render('Public/Contact', [
            'settings' => SchoolSetting::allAsArray(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $contactMessage = ContactMessage::create($validated);

        // Send notification email to admin
        $adminEmail = SchoolSetting::where('key', 'email')->value('value');
        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new NewContactMessageMail($contactMessage));
            } catch (\Throwable $e) {
                // Jangan gagalkan submit hanya karena email gagal terkirim; pesan sudah tersimpan.
                Log::error('Gagal mengirim email notifikasi kontak: ' . $e->getMessage());
            }
        }

        return redirect()->route('contact')->with('success', 'Pesan Anda berhasil dikirim! Kami akan segera merespons.');
    }
}
