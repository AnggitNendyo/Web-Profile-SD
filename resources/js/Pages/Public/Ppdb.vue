<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    settings: {
        type: Array,
        required: true,
    },
    schoolSettings: {
        type: Object,
        required: true,
    },
    registrationStatus: {
        type: Object,
        default: () => ({ open: true, state: 'open', message: '' }),
    },
});

const form = useForm({
    nama_siswa: '',
    nisn: '',
    nik: '',
    tempat_lahir: '',
    tanggal_lahir: '',
    jenis_kelamin: '',
    asal_sekolah: '',
    nama_ortu: '',
    nama_ayah: '',
    nama_ibu: '',
    alamat: '',
    no_hp: '',
    email_ortu: '',
    dokumen_upload: null,
});

const formRef = ref(null);
const fileInput = ref(null);
const fileName = ref('');

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.dokumen_upload = file;
        fileName.value = file.name;
    }
};

const submitForm = () => {
    form.post('/ppdb', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            fileName.value = '';
            if (fileInput.value) fileInput.value.value = '';
        },
    });
};
</script>

<template>
    <PublicLayout title="Info PPDB - SD Negeri">
        <section class="bg-indigo-900 pt-32 pb-20 relative overflow-hidden">
            <div class="absolute inset-0 z-0">
                <img :src="$page.props.schoolSettings?.banner_ppdb ? `/storage/${$page.props.schoolSettings.banner_ppdb}` : 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?q=80&w=2022&auto=format&fit=crop'" alt="School" class="w-full h-full object-cover opacity-20 mix-blend-overlay" />
            </div>
            <div class="container mx-auto px-4 md:px-6 relative z-10 text-center">
                <span class="inline-block py-1 px-3 rounded-full bg-yellow-400/20 text-yellow-300 border border-yellow-400/50 text-sm font-semibold mb-4 backdrop-blur-sm">Tahun Ajaran Baru</span>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Penerimaan Peserta Didik Baru</h1>
                <p class="text-indigo-100 text-lg max-w-2xl mx-auto">
                    Mari bergabung bersama kami. Dapatkan pendidikan terbaik untuk masa depan gemilang putra-putri Anda.
                </p>
            </div>
        </section>

        <section class="py-16 md:py-24 bg-slate-50 relative">
            <div class="container mx-auto px-4 md:px-6">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 max-w-6xl mx-auto">
                    <!-- Sidebar: Info PPDB -->
                    <div class="lg:col-span-4 space-y-8" data-aos="fade-right">

                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                            <div class="bg-slate-100 px-6 py-4 border-b border-slate-200">
                                <h3 class="font-bold text-slate-800">Jalur Pendaftaran Aktif</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div v-for="setting in settings" :key="setting.id" class="border border-slate-100 rounded-xl p-4 bg-slate-50">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-bold text-indigo-700">{{ setting.jalur }}</h4>
                                        <span class="text-xs font-semibold bg-indigo-100 text-indigo-600 px-2 py-1 rounded">
                                            Kuota: {{ setting.kuota }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-slate-600 space-y-1">
                                        <p><strong>TA:</strong> {{ setting.tahun_ajaran }}</p>
                                        <p><strong>Buka:</strong> {{ new Date(setting.jadwal_buka).toLocaleDateString('id-ID') }}</p>
                                        <p><strong>Tutup:</strong> {{ new Date(setting.jadwal_tutup).toLocaleDateString('id-ID') }}</p>
                                    </div>
                                </div>

                                <div v-if="settings.length === 0" class="text-sm text-slate-500 text-center py-4">
                                    Belum ada jalur pendaftaran yang dibuka saat ini.
                                </div>
                            </div>
                        </div>

                        <!-- Cek Status CTA Card -->
                        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
                            <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                            <div class="relative z-10">
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-4 backdrop-blur-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold mb-2">Sudah Mendaftar?</h3>
                                <p class="text-emerald-100 text-sm mb-4">Cek status pendaftaran Anda menggunakan nomor pendaftaran yang diberikan saat mendaftar.</p>
                                <Link href="/ppdb/cek-status" class="inline-flex items-center gap-2 bg-white text-emerald-700 font-semibold text-sm py-2.5 px-5 rounded-lg hover:bg-emerald-50 transition-colors shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                    </svg>
                                    Cek Status Pendaftaran
                                </Link>
                            </div>
                        </div>

                        <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-xl">
                            <h3 class="text-xl font-bold mb-4 border-b border-indigo-500 pb-2">Kontak Panitia PPDB</h3>
                            <ul class="space-y-4">
                                <li class="flex items-start gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 shrink-0 text-indigo-300">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                    </svg>
                                    <div>
                                        <p class="text-indigo-200 text-sm">WhatsApp / Telepon</p>
                                        <p class="font-semibold">{{ schoolSettings.phone }}</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 shrink-0 text-indigo-300">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                    </svg>
                                    <div>
                                        <p class="text-indigo-200 text-sm">Email</p>
                                        <p class="font-semibold">{{ schoolSettings.email }}</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Main Content: Formulir -->
                    <div class="lg:col-span-8" data-aos="fade-left">
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-8 md:p-10">
                            
                            <h2 class="text-2xl font-bold text-slate-900 mb-2">Formulir Pendaftaran Online</h2>
                            <p class="text-slate-500 mb-6 pb-6 border-b border-slate-100">Silakan lengkapi formulir di bawah ini dengan data yang sebenar-benarnya. Formulir ini merupakan tahap awal pra-pendaftaran.</p>

                            <!-- Error / closed message -->
                            <div v-if="$page.props.flash?.error" class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm">
                                {{ $page.props.flash.error }}
                            </div>

                            <!-- #2 Registration status banner -->
                            <div
                                v-if="!registrationStatus.open"
                                class="mb-8 rounded-xl border px-5 py-4 flex items-start gap-3"
                                :class="registrationStatus.state === 'not_started' ? 'bg-amber-50 border-amber-200 text-amber-800' : 'bg-slate-50 border-slate-200 text-slate-600'"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 shrink-0 mt-0.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                                <div>
                                    <p class="font-bold mb-0.5">Pendaftaran Ditutup</p>
                                    <p class="text-sm">{{ registrationStatus.message }}</p>
                                </div>
                            </div>

                            <!-- #2 Quota indicator when open -->
                            <div v-else-if="registrationStatus.sisa !== null && registrationStatus.sisa !== undefined" class="mb-8 rounded-xl bg-emerald-50 border border-emerald-200 px-5 py-3 flex items-center gap-3 text-emerald-800">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <p class="text-sm font-medium">
                                    Pendaftaran dibuka — sisa kuota <strong>{{ registrationStatus.sisa }}</strong> dari {{ registrationStatus.kuota }}.
                                </p>
                            </div>
                            
                            <!-- Success Message with Registration Number -->
                            <div v-if="$page.props.flash?.success" class="mb-8 bg-green-50 border border-green-200 text-green-700 px-6 py-5 rounded-xl">
                                <div class="flex items-start gap-3 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 shrink-0 text-green-500 mt-0.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <h4 class="font-bold">Pendaftaran Berhasil!</h4>
                                        <p class="text-sm">{{ $page.props.flash.success }}</p>
                                    </div>
                                </div>
                                
                                <!-- Show Registration Number prominently -->
                                <div v-if="$page.props.flash?.no_pendaftaran" class="bg-white border-2 border-green-300 rounded-xl p-4 mt-3">
                                    <p class="text-sm text-green-600 font-medium mb-1">Nomor Pendaftaran Anda:</p>
                                    <p class="text-2xl font-mono font-bold text-green-800 tracking-wider">{{ $page.props.flash.no_pendaftaran }}</p>
                                    <p class="text-xs text-green-600 mt-2 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                        </svg>
                                        Simpan nomor ini untuk mengecek status pendaftaran Anda.
                                    </p>
                                </div>

                                <div class="mt-4 flex flex-col sm:flex-row gap-3">
                                    <Link href="/ppdb/cek-status" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-green-600 text-white text-sm font-medium hover:bg-green-700 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                        </svg>
                                        Cek Status Pendaftaran
                                    </Link>
                                    <a
                                        v-if="$page.props.flash?.no_pendaftaran"
                                        :href="`/ppdb/bukti/${$page.props.flash.no_pendaftaran}`"
                                        target="_blank"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-white border border-green-300 text-green-700 text-sm font-medium hover:bg-green-50 transition-colors"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                                        </svg>
                                        Cetak Bukti Pendaftaran
                                    </a>
                                </div>
                            </div>

                            <form v-if="registrationStatus.open" @submit.prevent="submitForm" class="space-y-6" ref="formRef">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Data Siswa: heading -->
                                    <div class="col-span-1 md:col-span-2">
                                        <h3 class="text-sm font-bold text-indigo-700 uppercase tracking-wide flex items-center gap-2">
                                            <span class="w-6 h-px bg-indigo-300"></span>
                                            Data Calon Siswa
                                        </h3>
                                    </div>

                                    <!-- Nama Siswa -->
                                    <div class="col-span-1 md:col-span-2">
                                        <label for="nama_siswa" class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap Calon Siswa <span class="text-red-500">*</span></label>
                                        <input
                                            id="nama_siswa"
                                            v-model="form.nama_siswa"
                                            type="text"
                                            required
                                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                                            placeholder="Sesuai Akta Kelahiran"
                                        >
                                        <p v-if="form.errors.nama_siswa" class="mt-1 text-sm text-red-600">{{ form.errors.nama_siswa }}</p>
                                    </div>

                                    <!-- NISN -->
                                    <div>
                                        <label for="nisn" class="block text-sm font-semibold text-slate-700 mb-1">NISN <span class="text-slate-400 font-normal">(jika ada)</span></label>
                                        <input id="nisn" v-model="form.nisn" type="text" inputmode="numeric"
                                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                                            placeholder="Nomor Induk Siswa Nasional">
                                        <p v-if="form.errors.nisn" class="mt-1 text-sm text-red-600">{{ form.errors.nisn }}</p>
                                    </div>

                                    <!-- NIK -->
                                    <div>
                                        <label for="nik" class="block text-sm font-semibold text-slate-700 mb-1">NIK <span class="text-slate-400 font-normal">(sesuai KK)</span></label>
                                        <input id="nik" v-model="form.nik" type="text" inputmode="numeric"
                                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                                            placeholder="Nomor Induk Kependudukan">
                                        <p v-if="form.errors.nik" class="mt-1 text-sm text-red-600">{{ form.errors.nik }}</p>
                                    </div>

                                    <!-- Tempat Lahir -->
                                    <div>
                                        <label for="tempat_lahir" class="block text-sm font-semibold text-slate-700 mb-1">Tempat Lahir</label>
                                        <input id="tempat_lahir" v-model="form.tempat_lahir" type="text"
                                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                                            placeholder="Kota kelahiran">
                                        <p v-if="form.errors.tempat_lahir" class="mt-1 text-sm text-red-600">{{ form.errors.tempat_lahir }}</p>
                                    </div>

                                    <!-- Tanggal Lahir -->
                                    <div>
                                        <label for="tanggal_lahir" class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Lahir</label>
                                        <input id="tanggal_lahir" v-model="form.tanggal_lahir" type="date"
                                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors">
                                        <p v-if="form.errors.tanggal_lahir" class="mt-1 text-sm text-red-600">{{ form.errors.tanggal_lahir }}</p>
                                    </div>

                                    <!-- Jenis Kelamin -->
                                    <div>
                                        <label for="jenis_kelamin" class="block text-sm font-semibold text-slate-700 mb-1">Jenis Kelamin</label>
                                        <select id="jenis_kelamin" v-model="form.jenis_kelamin"
                                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors">
                                            <option value="">— Pilih —</option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                        <p v-if="form.errors.jenis_kelamin" class="mt-1 text-sm text-red-600">{{ form.errors.jenis_kelamin }}</p>
                                    </div>

                                    <!-- Asal Sekolah -->
                                    <div>
                                        <label for="asal_sekolah" class="block text-sm font-semibold text-slate-700 mb-1">Asal TK / PAUD</label>
                                        <input id="asal_sekolah" v-model="form.asal_sekolah" type="text"
                                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                                            placeholder="Nama TK/PAUD (jika ada)">
                                        <p v-if="form.errors.asal_sekolah" class="mt-1 text-sm text-red-600">{{ form.errors.asal_sekolah }}</p>
                                    </div>

                                    <!-- Data Ortu: heading -->
                                    <div class="col-span-1 md:col-span-2 mt-2">
                                        <h3 class="text-sm font-bold text-indigo-700 uppercase tracking-wide flex items-center gap-2">
                                            <span class="w-6 h-px bg-indigo-300"></span>
                                            Data Orang Tua / Wali
                                        </h3>
                                    </div>

                                    <!-- Nama Orang Tua / Wali -->
                                    <div class="col-span-1 md:col-span-2">
                                        <label for="nama_ortu" class="block text-sm font-semibold text-slate-700 mb-1">Nama Orang Tua / Wali <span class="text-red-500">*</span></label>
                                        <input
                                            id="nama_ortu"
                                            v-model="form.nama_ortu"
                                            type="text"
                                            required
                                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                                            placeholder="Nama wali yang dapat dihubungi"
                                        >
                                        <p v-if="form.errors.nama_ortu" class="mt-1 text-sm text-red-600">{{ form.errors.nama_ortu }}</p>
                                    </div>

                                    <!-- Nama Ayah -->
                                    <div>
                                        <label for="nama_ayah" class="block text-sm font-semibold text-slate-700 mb-1">Nama Ayah</label>
                                        <input id="nama_ayah" v-model="form.nama_ayah" type="text"
                                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors">
                                        <p v-if="form.errors.nama_ayah" class="mt-1 text-sm text-red-600">{{ form.errors.nama_ayah }}</p>
                                    </div>

                                    <!-- Nama Ibu -->
                                    <div>
                                        <label for="nama_ibu" class="block text-sm font-semibold text-slate-700 mb-1">Nama Ibu</label>
                                        <input id="nama_ibu" v-model="form.nama_ibu" type="text"
                                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors">
                                        <p v-if="form.errors.nama_ibu" class="mt-1 text-sm text-red-600">{{ form.errors.nama_ibu }}</p>
                                    </div>

                                    <!-- No HP -->
                                    <div class="col-span-1 md:col-span-2">
                                        <label for="no_hp" class="block text-sm font-semibold text-slate-700 mb-1">Nomor WhatsApp / HP <span class="text-red-500">*</span></label>
                                        <input
                                            id="no_hp"
                                            v-model="form.no_hp"
                                            type="tel"
                                            required
                                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                                            placeholder="Contoh: 08123456789"
                                        >
                                        <p v-if="form.errors.no_hp" class="mt-1 text-sm text-red-600">{{ form.errors.no_hp }}</p>
                                    </div>

                                    <!-- Email Orang Tua -->
                                    <div class="col-span-1 md:col-span-2">
                                        <label for="email_ortu" class="block text-sm font-semibold text-slate-700 mb-1">
                                            Email Orang Tua / Wali
                                            <span class="text-slate-400 font-normal">(opsional)</span>
                                        </label>
                                        <input 
                                            id="email_ortu" 
                                            v-model="form.email_ortu" 
                                            type="email" 
                                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                                            placeholder="email@contoh.com"
                                        >
                                        <p class="mt-1 text-xs text-slate-500 flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                            </svg>
                                            Anda akan menerima notifikasi email otomatis saat status pendaftaran berubah.
                                        </p>
                                        <p v-if="form.errors.email_ortu" class="mt-1 text-sm text-red-600">{{ form.errors.email_ortu }}</p>
                                    </div>
                                    
                                    <!-- Alamat -->
                                    <div class="col-span-1 md:col-span-2">
                                        <label for="alamat" class="block text-sm font-semibold text-slate-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                                        <textarea 
                                            id="alamat" 
                                            v-model="form.alamat" 
                                            rows="3" 
                                            required
                                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                                        ></textarea>
                                        <p v-if="form.errors.alamat" class="mt-1 text-sm text-red-600">{{ form.errors.alamat }}</p>
                                    </div>
                                    
                                    <!-- File Upload -->
                                    <div class="col-span-1 md:col-span-2">
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Upload Berkas Persyaratan</label>
                                        <p class="text-xs text-slate-500 mb-3">Jadikan satu file (PDF/JPG/PNG maksimal 2MB) yang berisi scan Akta Kelahiran dan Kartu Keluarga.</p>
                                        
                                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:bg-slate-50 transition-colors cursor-pointer" @click="$refs.fileInput.click()">
                                            <div class="space-y-1 text-center">
                                                <svg v-if="!fileName" class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mx-auto h-12 w-12 text-indigo-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                </svg>
                                                
                                                <div class="flex text-sm text-slate-600 justify-center">
                                                    <span class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                        <span>{{ fileName || 'Upload a file' }}</span>
                                                        <input ref="fileInput" id="file-upload" name="file-upload" type="file" class="sr-only" accept=".pdf,.jpg,.jpeg,.png" @change="handleFileChange">
                                                    </span>
                                                </div>
                                                <p v-if="!fileName" class="text-xs text-slate-500">PDF, PNG, JPG up to 2MB</p>
                                            </div>
                                        </div>
                                        <p v-if="form.errors.dokumen_upload" class="mt-1 text-sm text-red-600">{{ form.errors.dokumen_upload }}</p>
                                    </div>
                                </div>
                                
                                <div class="pt-6 border-t border-slate-100 flex items-center justify-end">
                                    <button 
                                        type="submit" 
                                        :disabled="form.processing"
                                        class="inline-flex justify-center rounded-lg border border-transparent bg-indigo-600 py-3 px-8 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition-colors"
                                    >
                                        <span v-if="form.processing" class="flex items-center gap-2">
                                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Memproses...
                                        </span>
                                        <span v-else>Kirim Pendaftaran</span>
                                    </button>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>
                
            </div>
        </section>
    </PublicLayout>
</template>
