<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\NewPpdbRegistrationMail;
use App\Mail\PpdbRegistrationSuccessMail;
use App\Models\PpdbRegistration;
use App\Models\PpdbSetting;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class PpdbController extends Controller
{
    public function index()
    {
        return Inertia::render('Public/Ppdb', [
            'settings' => PpdbSetting::where('is_active', true)->latest()->get(),
            'schoolSettings' => SchoolSetting::allAsArray(),
            'registrationStatus' => PpdbSetting::registrationStatus(),
        ]);
    }

    public function store(Request $request)
    {
        // Guard #2: tolak jika pendaftaran tidak sedang dibuka (belum mulai,
        // sudah berakhir, kuota penuh, atau tidak ada jalur aktif).
        $status = PpdbSetting::registrationStatus();
        if (! $status['open']) {
            return redirect()->route('ppdb')->with('error', $status['message']);
        }

        $validated = $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:20',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date|before:today',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'asal_sekolah' => 'nullable|string|max:255',
            'nama_ortu' => 'required|string|max:255',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'email_ortu' => 'nullable|email|max:255',
            'dokumen_upload' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('dokumen_upload')) {
            $validated['dokumen_upload'] = $request->file('dokumen_upload')->store('ppdb-documents', 'public');
        }

        $registration = PpdbRegistration::create($validated);

        // Notifikasi email ke admin
        $adminEmail = SchoolSetting::where('key', 'email')->value('value');
        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new NewPpdbRegistrationMail($registration));
            } catch (\Throwable $e) {
                // Pendaftaran sudah tersimpan; kegagalan email tidak boleh menggagalkan proses.
                Log::error('Gagal mengirim email notifikasi PPDB: ' . $e->getMessage());
            }
        }

        // Notifikasi email ke orang tua (pendaftar)
        if ($registration->email_ortu) {
            try {
                Mail::to($registration->email_ortu)->send(new PpdbRegistrationSuccessMail($registration));
            } catch (\Throwable $e) {
                Log::error('Gagal mengirim email sukses pendaftaran ke ortu: ' . $e->getMessage());
            }
        }

        return redirect()->route('ppdb')->with([
            'success' => 'Pendaftaran berhasil dikirim! Nomor pendaftaran Anda: ' . $registration->no_pendaftaran . '. Simpan nomor ini untuk mengecek status pendaftaran.',
            'no_pendaftaran' => $registration->no_pendaftaran,
        ]);
    }

    /**
     * Show the check status form.
     */
    public function showCheckStatus()
    {
        return Inertia::render('Public/PpdbCheckStatus', [
            'schoolSettings' => SchoolSetting::allAsArray(),
        ]);
    }

    /**
     * Look up registration status by no_pendaftaran.
     */
    public function checkStatus(Request $request)
    {
        $request->validate([
            'no_pendaftaran' => 'required|string|max:50',
        ]);

        $registration = PpdbRegistration::where('no_pendaftaran', $request->no_pendaftaran)->first();

        return Inertia::render('Public/PpdbCheckStatus', [
            'schoolSettings' => SchoolSetting::allAsArray(),
            'searchQuery' => $request->no_pendaftaran,
            'result' => $registration ? [
                'no_pendaftaran' => $registration->no_pendaftaran,
                'nama_siswa' => $registration->nama_siswa,
                'nama_ortu' => $registration->nama_ortu,
                'status' => $registration->status,
                'notes' => $registration->notes,
                'created_at' => $registration->created_at->toISOString(),
                'updated_at' => $registration->updated_at->toISOString(),
            ] : null,
        ]);
    }

    /**
     * #3 Bukti pendaftaran — halaman ramah-cetak (Ctrl+P / Save as PDF).
     * Diakses via nomor pendaftaran sehingga orang tua bisa mencetak ulang.
     */
    public function bukti(string $no_pendaftaran)
    {
        $registration = PpdbRegistration::where('no_pendaftaran', $no_pendaftaran)->firstOrFail();

        return Inertia::render('Public/PpdbBukti', [
            'registration' => $registration,
            'schoolSettings' => SchoolSetting::allAsArray(),
        ]);
    }
}
