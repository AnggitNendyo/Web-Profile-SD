<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    schoolSettings: { type: Object, required: true },
    searchQuery: { type: String, default: '' },
    result: { type: Object, default: null },
});

const form = useForm({
    no_pendaftaran: props.searchQuery || '',
});

const hasSearched = ref(!!props.searchQuery);

// Tahun berjalan untuk contoh format nomor pendaftaran.
const currentYear = new Date().getFullYear();

const submitSearch = () => {
    hasSearched.value = true;
    form.post('/ppdb/cek-status', {
        preserveScroll: true,
    });
};

const statusConfig = {
    pending: {
        label: 'Menunggu Verifikasi',
        icon: '⏳',
        bg: 'bg-amber-50',
        border: 'border-amber-200',
        badge: 'bg-amber-100 text-amber-700 border-amber-300',
        iconBg: 'bg-amber-100 text-amber-600',
        description: 'Pendaftaran Anda sedang dalam antrian verifikasi oleh panitia PPDB.',
    },
    verified: {
        label: 'Berkas Terverifikasi',
        icon: '✅',
        bg: 'bg-emerald-50',
        border: 'border-emerald-200',
        badge: 'bg-emerald-100 text-emerald-700 border-emerald-300',
        iconBg: 'bg-emerald-100 text-emerald-600',
        description: 'Berkas pendaftaran Anda sudah diperiksa dan dinyatakan lengkap/sesuai. Menunggu proses seleksi.',
    },
    rejected: {
        label: 'Ditolak',
        icon: '❌',
        bg: 'bg-red-50',
        border: 'border-red-200',
        badge: 'bg-red-100 text-red-700 border-red-300',
        iconBg: 'bg-red-100 text-red-600',
        description: 'Maaf, pendaftaran Anda tidak dapat diterima. Lihat catatan dari panitia di bawah untuk informasi lebih lanjut.',
    },
    accepted: {
        label: 'Diterima sebagai Siswa',
        icon: '🎉',
        bg: 'bg-blue-50',
        border: 'border-blue-200',
        badge: 'bg-blue-100 text-blue-700 border-blue-300',
        iconBg: 'bg-blue-100 text-blue-600',
        description: 'Selamat! Putra/putri Anda diterima sebagai siswa baru. Silakan ikuti langkah selanjutnya sesuai arahan panitia.',
    },
};

const currentStatus = computed(() => {
    if (!props.result) return null;
    return statusConfig[props.result.status] || statusConfig.pending;
});

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <PublicLayout title="Cek Status Pendaftaran PPDB - SD Negeri">
        <!-- Hero -->
        <section class="bg-indigo-900 pt-32 pb-20 relative overflow-hidden">
            <div class="absolute inset-0 z-0">
                <div class="w-full h-full bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-indigo-600 via-indigo-900 to-slate-900 opacity-90"></div>
            </div>
            <!-- Decorative floating elements -->
            <div class="absolute inset-0 z-0 overflow-hidden">
                <div class="absolute -top-10 -right-10 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute bottom-0 left-0 w-60 h-60 bg-blue-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
            </div>
            <div class="container mx-auto px-4 md:px-6 relative z-10 text-center">
                <span class="inline-block py-1 px-3 rounded-full bg-white/10 text-indigo-200 border border-white/20 text-sm font-semibold mb-4 backdrop-blur-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline -mt-0.5 mr-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    Lacak Pendaftaran
                </span>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Cek Status Pendaftaran</h1>
                <p class="text-indigo-100 text-lg max-w-2xl mx-auto">
                    Masukkan nomor pendaftaran Anda untuk mengetahui status terkini proses penerimaan peserta didik baru.
                </p>
            </div>
        </section>

        <section class="py-16 md:py-24 bg-slate-50 relative">
            <div class="container mx-auto px-4 md:px-6 max-w-3xl">

                <!-- Search Form -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-8 md:p-10 -mt-24 relative z-20" data-aos="fade-up">
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-indigo-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900">Masukkan Nomor Pendaftaran</h2>
                        <p class="text-slate-500 mt-2">Format: PPDB-{{ currentYear }}-XXXX</p>
                    </div>

                    <form @submit.prevent="submitSearch" class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-grow relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-slate-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                            </div>
                            <input
                                id="no_pendaftaran"
                                v-model="form.no_pendaftaran"
                                type="text"
                                required
                                class="w-full pl-12 pr-4 py-4 rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-lg font-mono uppercase"
                                :placeholder="`PPDB-${currentYear}-0001`"
                            >
                            <p v-if="form.errors.no_pendaftaran" class="mt-2 text-sm text-red-600">{{ form.errors.no_pendaftaran }}</p>
                        </div>
                        <button
                            type="submit"
                            :disabled="form.processing || !form.no_pendaftaran"
                            class="inline-flex justify-center items-center gap-2 rounded-xl bg-indigo-600 py-4 px-8 text-base font-semibold text-white shadow-md hover:bg-indigo-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition-all duration-300 whitespace-nowrap"
                        >
                            <svg v-if="form.processing" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ form.processing ? 'Mencari...' : 'Cek Status' }}
                        </button>
                    </form>
                </div>

                <!-- Result: Found -->
                <div v-if="hasSearched && result" class="mt-8 space-y-6" data-aos="fade-up" data-aos-delay="100">
                    <!-- Status Hero Card -->
                    <div :class="['rounded-2xl border-2 p-8 md:p-10 transition-all duration-500', currentStatus.bg, currentStatus.border]">
                        <div class="text-center mb-6">
                            <div :class="['w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 text-4xl', currentStatus.iconBg]">
                                {{ currentStatus.icon }}
                            </div>
                            <span :class="['inline-block px-4 py-2 rounded-full text-sm font-bold border', currentStatus.badge]">
                                {{ currentStatus.label }}
                            </span>
                            <p class="text-slate-600 mt-4 max-w-md mx-auto">{{ currentStatus.description }}</p>
                        </div>
                    </div>

                    <!-- Detail Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-indigo-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                                </svg>
                                Detail Pendaftaran
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-slate-500">Nomor Pendaftaran</dt>
                                    <dd class="mt-1 text-lg font-mono font-bold text-indigo-600">{{ result.no_pendaftaran }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-slate-500">Status</dt>
                                    <dd class="mt-1">
                                        <span :class="['px-3 py-1.5 rounded-full text-sm font-semibold border', currentStatus.badge]">
                                            {{ currentStatus.label }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-slate-500">Nama Calon Siswa</dt>
                                    <dd class="mt-1 text-base font-semibold text-slate-900">{{ result.nama_siswa }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-slate-500">Nama Orang Tua / Wali</dt>
                                    <dd class="mt-1 text-base text-slate-800">{{ result.nama_ortu }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-slate-500">Tanggal Pendaftaran</dt>
                                    <dd class="mt-1 text-sm text-slate-700">{{ formatDate(result.created_at) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-slate-500">Terakhir Diperbarui</dt>
                                    <dd class="mt-1 text-sm text-slate-700">{{ formatDate(result.updated_at) }}</dd>
                                </div>
                            </dl>

                            <!-- Notes from admin -->
                            <div v-if="result.notes" class="mt-6 pt-6 border-t border-slate-100">
                                <h4 class="text-sm font-semibold text-slate-700 mb-2 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 011.037-.443 48.282 48.282 0 005.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                                    </svg>
                                    Catatan dari Panitia
                                </h4>
                                <div class="bg-slate-50 rounded-xl p-4 text-sm text-slate-700 leading-relaxed border border-slate-100">
                                    {{ result.notes }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Result: Not Found -->
                <div v-if="hasSearched && !result && !form.processing" class="mt-8" data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-10 text-center">
                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-slate-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Data Tidak Ditemukan</h3>
                        <p class="text-slate-500 max-w-md mx-auto mb-6">
                            Nomor pendaftaran <strong class="font-mono text-indigo-600">{{ searchQuery }}</strong> tidak ditemukan dalam sistem kami. Pastikan nomor yang Anda masukkan sudah benar.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <button @click="form.no_pendaftaran = ''; hasSearched = false" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-slate-100 text-slate-700 font-medium hover:bg-slate-200 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" />
                                </svg>
                                Coba Lagi
                            </button>
                            <Link href="/ppdb" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors">
                                Daftar PPDB
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Info Section -->
                <div class="mt-12 bg-white rounded-2xl shadow-sm border border-slate-100 p-8" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-indigo-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        Informasi Penting
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center shrink-0 text-lg font-bold">1</div>
                            <div>
                                <h4 class="font-semibold text-slate-800 text-sm">Nomor Pendaftaran</h4>
                                <p class="text-sm text-slate-500 mt-0.5">Nomor pendaftaran diberikan setelah Anda berhasil mengisi formulir PPDB online.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center shrink-0 text-lg font-bold">2</div>
                            <div>
                                <h4 class="font-semibold text-slate-800 text-sm">Notifikasi Email</h4>
                                <p class="text-sm text-slate-500 mt-0.5">Jika Anda mengisi email saat mendaftar, Anda akan menerima notifikasi otomatis saat status berubah.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center shrink-0 text-lg font-bold">3</div>
                            <div>
                                <h4 class="font-semibold text-slate-800 text-sm">Proses Verifikasi</h4>
                                <p class="text-sm text-slate-500 mt-0.5">Panitia akan memeriksa berkas Anda dan memperbarui status dalam beberapa hari kerja.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center shrink-0 text-lg font-bold">4</div>
                            <div>
                                <h4 class="font-semibold text-slate-800 text-sm">Butuh Bantuan?</h4>
                                <p class="text-sm text-slate-500 mt-0.5">Hubungi panitia di <strong>{{ schoolSettings.phone }}</strong> atau email <strong>{{ schoolSettings.email }}</strong>.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Back to PPDB -->
                <div class="mt-8 text-center">
                    <Link href="/ppdb" class="text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center gap-1 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Kembali ke Halaman PPDB
                    </Link>
                </div>

            </div>
        </section>
    </PublicLayout>
</template>
