<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PpdbStatusChangedMail;
use App\Models\PpdbRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class PpdbRegistrationController extends Controller
{
    public function index()
    {
        $query = PpdbRegistration::latest();

        if ($status = request('status')) {
            $query->where('status', $status);
        }

        return Inertia::render('Admin/Ppdb/Index', [
            'ppdbs' => $query->paginate(10)->withQueryString(),
            'filters' => request()->only(['status']),
            'stats' => [
                'pending' => PpdbRegistration::where('status', 'pending')->count(),
                'verified' => PpdbRegistration::where('status', 'verified')->count(),
                'rejected' => PpdbRegistration::where('status', 'rejected')->count(),
                'accepted' => PpdbRegistration::where('status', 'accepted')->count(),
            ],
        ]);
    }

    public function show(PpdbRegistration $ppdb)
    {
        return Inertia::render('Admin/Ppdb/Show', [
            'ppdb' => $ppdb,
        ]);
    }

    public function update(Request $request, PpdbRegistration $ppdb)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,verified,rejected,accepted',
            'notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $ppdb->status;

        $ppdb->update($validated);

        if ($validated['status'] === 'accepted' && $oldStatus !== 'accepted') {
            $studentData = [
                'name' => $ppdb->nama_siswa,
                'gender' => $ppdb->jenis_kelamin ?? 'Laki-laki',
                'place_of_birth' => $ppdb->tempat_lahir,
                'date_of_birth' => $ppdb->tanggal_lahir,
                'address' => $ppdb->alamat,
                'parent_name' => $ppdb->nama_ortu,
                'parent_phone' => $ppdb->no_hp,
                'enrollment_year' => date('Y'),
                'status' => 'Aktif',
            ];
            
            if (!empty($ppdb->nisn)) {
                \App\Models\Student::firstOrCreate(['nisn' => $ppdb->nisn], $studentData);
            } else {
                \App\Models\Student::firstOrCreate([
                    'name' => $ppdb->nama_siswa, 
                    'date_of_birth' => $ppdb->tanggal_lahir
                ], $studentData);
            }
        }

        // Send email notification to parent if status changed and email is available
        if ($oldStatus !== $validated['status'] && $ppdb->email_ortu) {
            try {
                Mail::to($ppdb->email_ortu)->send(new PpdbStatusChangedMail($ppdb));
            } catch (\Throwable $e) {
                // Status sudah diperbarui; kegagalan email tidak boleh menggagalkan aksi admin.
                Log::error('Gagal mengirim email perubahan status PPDB: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.ppdb.index')->with('success', 'Status pendaftaran berhasil diperbarui.');
    }

    public function destroy(PpdbRegistration $ppdb)
    {
        $ppdb->delete();

        return redirect()->route('admin.ppdb.index')->with('success', 'Data pendaftaran berhasil dihapus.');
    }

    /**
     * #4 Export data pendaftar ke CSV (streaming, tanpa dependensi).
     * Menghormati filter status yang sedang aktif di dashboard.
     */
    public function export(Request $request)
    {
        $query = PpdbRegistration::latest();

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $statusLabels = [
            'pending' => 'Menunggu Verifikasi',
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak',
            'accepted' => 'Diterima',
        ];

        $columns = [
            'No. Pendaftaran', 'Nama Siswa', 'NISN', 'NIK', 'Tempat Lahir', 'Tanggal Lahir',
            'Jenis Kelamin', 'Asal Sekolah', 'Nama Orang Tua/Wali', 'Nama Ayah', 'Nama Ibu',
            'Alamat', 'No. HP', 'Email', 'Status', 'Catatan', 'Waktu Daftar',
        ];

        $filename = 'pendaftar-ppdb-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->streamDownload(function () use ($query, $columns, $statusLabels) {
            $handle = fopen('php://output', 'w');

            // BOM agar Excel mengenali UTF-8 (nama dengan karakter khusus tidak rusak).
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $columns);

            $query->chunk(200, function ($rows) use ($handle, $statusLabels) {
                foreach ($rows as $r) {
                    fputcsv($handle, [
                        $r->no_pendaftaran,
                        $r->nama_siswa,
                        $r->nisn,
                        $r->nik,
                        $r->tempat_lahir,
                        $r->tanggal_lahir?->format('Y-m-d'),
                        $r->jenis_kelamin,
                        $r->asal_sekolah,
                        $r->nama_ortu,
                        $r->nama_ayah,
                        $r->nama_ibu,
                        $r->alamat,
                        $r->no_hp,
                        $r->email_ortu,
                        $statusLabels[$r->status] ?? $r->status,
                        $r->notes,
                        $r->created_at?->format('Y-m-d H:i'),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, $headers);
    }
}
