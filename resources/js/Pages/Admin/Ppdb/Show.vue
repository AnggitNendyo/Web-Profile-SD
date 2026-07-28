<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { format, parseISO } from 'date-fns';
import { id } from 'date-fns/locale';

const props = defineProps({
    ppdb: {
        type: Object,
        required: true,
    }
});

const form = useForm({
    status: props.ppdb.status,
    notes: props.ppdb.notes || '',
});

const submit = () => {
    form.put(`/admin/ppdb/${props.ppdb.id}`);
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return format(parseISO(dateString), 'dd MMMM yyyy, HH:mm', { locale: id });
};

const formatDateOnly = (dateString) => {
    if (!dateString) return '-';
    return format(parseISO(dateString), 'dd MMMM yyyy', { locale: id });
};

const statusClasses = {
    pending: 'bg-orange-100 text-orange-700 border-orange-200',
    verified: 'bg-green-100 text-green-700 border-green-200',
    rejected: 'bg-red-100 text-red-700 border-red-200',
    accepted: 'bg-blue-100 text-blue-700 border-blue-200',
};

const statusLabels = {
    pending: 'Menunggu Verifikasi',
    verified: 'Berkas Sesuai (Terverifikasi)',
    rejected: 'Ditolak',
    accepted: 'Diterima sebagai Siswa',
};
</script>

<template>
    <AdminLayout title="Detail Pendaftaran">
        <div class="mb-6 flex items-center justify-between">
            <Link href="/admin/ppdb" class="text-sm font-medium text-slate-500 hover:text-indigo-600 flex items-center gap-1 transition-colors w-fit">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </Link>
            
            <div :class="['px-3 py-1.5 rounded-full text-sm font-semibold border', statusClasses[ppdb.status]]">
                Status: {{ statusLabels[ppdb.status] }}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Data Section -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Data Siswa -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                        <h2 class="font-bold text-slate-800">Informasi Calon Siswa</h2>
                        <span class="font-mono text-sm font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded">{{ ppdb.no_pendaftaran }}</span>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-slate-500">Nama Lengkap</dt>
                                <dd class="mt-1 text-lg font-semibold text-slate-900">{{ ppdb.nama_siswa }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500">NISN</dt>
                                <dd class="mt-1 text-base text-slate-800 font-mono">{{ ppdb.nisn || '-' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500">NIK</dt>
                                <dd class="mt-1 text-base text-slate-800 font-mono">{{ ppdb.nik || '-' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500">Tempat, Tanggal Lahir</dt>
                                <dd class="mt-1 text-base text-slate-800">
                                    {{ ppdb.tempat_lahir || '-' }}<span v-if="ppdb.tanggal_lahir">, {{ formatDateOnly(ppdb.tanggal_lahir) }}</span>
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500">Jenis Kelamin</dt>
                                <dd class="mt-1 text-base text-slate-800">{{ ppdb.jenis_kelamin || '-' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500">Asal TK / PAUD</dt>
                                <dd class="mt-1 text-base text-slate-800">{{ ppdb.asal_sekolah || '-' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500">Waktu Pendaftaran</dt>
                                <dd class="mt-1 text-sm text-slate-800">{{ formatDate(ppdb.created_at) }}</dd>
                            </div>

                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-slate-500">Alamat Lengkap</dt>
                                <dd class="mt-1 text-base text-slate-800">{{ ppdb.alamat }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Data Ortu -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h2 class="font-bold text-slate-800">Informasi Orang Tua / Wali</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-slate-500">Nama Orang Tua / Wali</dt>
                                <dd class="mt-1 text-base font-semibold text-slate-900">{{ ppdb.nama_ortu }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500">Nama Ayah</dt>
                                <dd class="mt-1 text-base text-slate-800">{{ ppdb.nama_ayah || '-' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500">Nama Ibu</dt>
                                <dd class="mt-1 text-base text-slate-800">{{ ppdb.nama_ibu || '-' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500">Email</dt>
                                <dd class="mt-1 text-base text-slate-800 break-all">{{ ppdb.email_ortu || '-' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500">No. WhatsApp / HP</dt>
                                <dd class="mt-1 text-base text-slate-800 flex items-center gap-2">
                                    {{ ppdb.no_hp }}
                                    <a :href="'https://wa.me/' + ppdb.no_hp.replace(/^0/, '62')" target="_blank" class="p-1 bg-green-100 text-green-600 rounded hover:bg-green-200 transition-colors" title="Hubungi via WhatsApp">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                                        </svg>
                                    </a>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Sidebar (Verifikasi & Dokumen) -->
            <div class="space-y-6">
                <!-- Update Status Form -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h3 class="font-bold text-slate-800">Proses Verifikasi</h3>
                    </div>
                    
                    <form @submit.prevent="submit" class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Ubah Status</label>
                            <select v-model="form.status" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm font-medium">
                                <option value="pending">Menunggu Verifikasi</option>
                                <option value="verified">Terverifikasi (Berkas Sesuai)</option>
                                <option value="rejected">Ditolak (Berkas Tidak Sesuai)</option>
                                <option value="accepted">Diterima Lolos Seleksi</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan Panitia (Opsional)</label>
                            <textarea v-model="form.notes" rows="4" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm" placeholder="Tambahkan alasan penolakan atau catatan internal..."></textarea>
                        </div>
                        
                        <button 
                            type="submit" 
                            :disabled="form.processing || form.status === props.ppdb.status && form.notes === props.ppdb.notes"
                            class="w-full inline-flex justify-center items-center gap-2 rounded-lg bg-indigo-600 py-2.5 px-4 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition-colors"
                        >
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Status' }}
                        </button>
                    </form>
                </div>

                <!-- Dokumen -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h3 class="font-bold text-slate-800">Dokumen Upload</h3>
                    </div>
                    <div class="p-6">
                        <div v-if="ppdb.dokumen_upload" class="border rounded-xl p-4 flex items-center justify-between bg-slate-50">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div class="truncate">
                                    <p class="text-sm font-semibold text-slate-800 truncate">Berkas Persyaratan</p>
                                    <p class="text-xs text-slate-500">PDF / Gambar</p>
                                </div>
                            </div>
                            <a :href="`/storage/${ppdb.dokumen_upload}`" target="_blank" class="p-2 text-indigo-600 hover:bg-indigo-100 rounded-lg transition-colors shrink-0" title="Buka/Download">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                            </a>
                        </div>
                        <div v-else class="text-center py-6 text-sm text-slate-500 border border-dashed rounded-xl bg-slate-50">
                            Tidak ada dokumen yang diunggah.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
