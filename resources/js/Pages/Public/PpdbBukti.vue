<script setup>
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    registration: {
        type: Object,
        required: true,
    },
    schoolSettings: {
        type: Object,
        required: true,
    },
});

const statusLabels = {
    pending: 'Menunggu Verifikasi',
    verified: 'Terverifikasi',
    rejected: 'Ditolak',
    accepted: 'Diterima',
};

const formatDate = (dateString, withTime = false) => {
    if (!dateString) return '-';
    const d = new Date(dateString);
    const opts = withTime
        ? { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' }
        : { day: 'numeric', month: 'long', year: 'numeric' };
    return d.toLocaleDateString('id-ID', opts);
};

const printPage = () => window.print();
</script>

<template>
    <Head title="Bukti Pendaftaran PPDB" />

    <div class="min-h-screen bg-slate-100 py-8 px-4 print:bg-white print:py-0 print:px-0">
        <!-- Action bar (tidak ikut tercetak) -->
        <div class="max-w-2xl mx-auto mb-6 flex items-center justify-between print:hidden">
            <a href="/ppdb" class="text-sm font-medium text-slate-500 hover:text-indigo-600 flex items-center gap-1 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali
            </a>
            <button @click="printPage" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096M17.66 18L17.28 6.75a48.036 48.036 0 00-10.56 0M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175M17.66 18h1.09A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175M9 6.75V3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V6.75" />
                </svg>
                Cetak / Simpan PDF
            </button>
        </div>

        <!-- Kartu bukti -->
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg print:shadow-none print:rounded-none overflow-hidden print:max-w-full">
            <!-- Header -->
            <div class="border-b-4 border-indigo-600 px-8 py-6 flex items-center gap-4">
                <div class="w-16 h-16 bg-indigo-600 text-white rounded-lg flex items-center justify-center font-bold text-2xl shrink-0">
                    SD
                </div>
                <div class="flex-grow">
                    <h1 class="text-xl font-bold text-slate-900 leading-tight">{{ schoolSettings.school_name || 'SD Negeri 1 Nusantara' }}</h1>
                    <p class="text-sm text-slate-500">{{ schoolSettings.address || 'Bukti Pendaftaran Peserta Didik Baru' }}</p>
                </div>
            </div>

            <div class="px-8 py-6">
                <div class="text-center mb-6">
                    <h2 class="text-lg font-bold text-slate-800 uppercase tracking-wide">Bukti Pendaftaran PPDB</h2>
                    <p class="text-sm text-slate-500">Tahun Ajaran {{ new Date(registration.created_at).getFullYear() }}/{{ new Date(registration.created_at).getFullYear() + 1 }}</p>
                </div>

                <!-- Nomor pendaftaran -->
                <div class="bg-indigo-50 border border-indigo-200 rounded-xl px-6 py-4 text-center mb-6">
                    <p class="text-xs text-indigo-500 font-medium uppercase tracking-wide mb-1">Nomor Pendaftaran</p>
                    <p class="text-2xl font-mono font-bold text-indigo-800 tracking-wider">{{ registration.no_pendaftaran }}</p>
                </div>

                <!-- Data siswa -->
                <table class="w-full text-sm mb-6">
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="py-2.5 text-slate-500 w-2/5 align-top">Nama Calon Siswa</td>
                            <td class="py-2.5 font-semibold text-slate-900">{{ registration.nama_siswa }}</td>
                        </tr>
                        <tr v-if="registration.nisn">
                            <td class="py-2.5 text-slate-500 align-top">NISN</td>
                            <td class="py-2.5 text-slate-800 font-mono">{{ registration.nisn }}</td>
                        </tr>
                        <tr v-if="registration.nik">
                            <td class="py-2.5 text-slate-500 align-top">NIK</td>
                            <td class="py-2.5 text-slate-800 font-mono">{{ registration.nik }}</td>
                        </tr>
                        <tr v-if="registration.tempat_lahir || registration.tanggal_lahir">
                            <td class="py-2.5 text-slate-500 align-top">Tempat, Tanggal Lahir</td>
                            <td class="py-2.5 text-slate-800">
                                {{ registration.tempat_lahir }}<span v-if="registration.tanggal_lahir">, {{ formatDate(registration.tanggal_lahir) }}</span>
                            </td>
                        </tr>
                        <tr v-if="registration.jenis_kelamin">
                            <td class="py-2.5 text-slate-500 align-top">Jenis Kelamin</td>
                            <td class="py-2.5 text-slate-800">{{ registration.jenis_kelamin }}</td>
                        </tr>
                        <tr v-if="registration.asal_sekolah">
                            <td class="py-2.5 text-slate-500 align-top">Asal TK / PAUD</td>
                            <td class="py-2.5 text-slate-800">{{ registration.asal_sekolah }}</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 text-slate-500 align-top">Nama Orang Tua / Wali</td>
                            <td class="py-2.5 text-slate-800">{{ registration.nama_ortu }}</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 text-slate-500 align-top">No. WhatsApp / HP</td>
                            <td class="py-2.5 text-slate-800">{{ registration.no_hp }}</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 text-slate-500 align-top">Alamat</td>
                            <td class="py-2.5 text-slate-800">{{ registration.alamat }}</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 text-slate-500 align-top">Waktu Pendaftaran</td>
                            <td class="py-2.5 text-slate-800">{{ formatDate(registration.created_at, true) }}</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 text-slate-500 align-top">Status</td>
                            <td class="py-2.5">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ statusLabels[registration.status] || registration.status }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Catatan -->
                <div class="bg-amber-50 border border-amber-200 rounded-lg px-4 py-3 text-xs text-amber-800 mb-8">
                    <p class="font-semibold mb-1">Penting:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        <li>Simpan bukti ini dan nomor pendaftaran Anda dengan baik.</li>
                        <li>Gunakan nomor pendaftaran untuk mengecek status melalui halaman "Cek Status Pendaftaran".</li>
                        <li>Bukti ini merupakan tahap pra-pendaftaran, bukan tanda diterima.</li>
                    </ul>
                </div>

                <!-- Tanda tangan -->
                <div class="flex justify-end">
                    <div class="text-center text-sm">
                        <p class="text-slate-500 mb-16">Panitia PPDB</p>
                        <p class="font-semibold text-slate-800 border-t border-slate-300 pt-1 px-4">( ______________________ )</p>
                    </div>
                </div>
            </div>

            <!-- Footer strip -->
            <div class="bg-slate-50 border-t border-slate-100 px-8 py-3 text-center text-xs text-slate-400 print:hidden">
                Dokumen digital — dicetak dari situs {{ schoolSettings.school_name || 'sekolah' }}.
            </div>
        </div>
    </div>
</template>

<style>
@media print {
    @page {
        margin: 1.5cm;
    }
}
</style>
