<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref, reactive } from 'vue';
import { checkFileSize, IMAGE_MAX_MB } from '@/lib/uploadLimits';

const props = defineProps({
    settings: {
        type: Object,
        required: true,
    }
});

const form = useForm({
    school_name: props.settings.school_name || '',
    npsn: props.settings.npsn || '',
    accreditation: props.settings.accreditation || '',
    established_year: props.settings.established_year || '',
    total_students: props.settings.total_students || '',
    total_achievements: props.settings.total_achievements || '',
    motto: props.settings.motto || '',
    principal_name: props.settings.principal_name || '',
    principal_greeting: props.settings.principal_greeting || '',
    principal_photo: null,
    address: props.settings.address || '',
    phone: props.settings.phone || '',
    email: props.settings.email || '',
    map_embed_url: props.settings.map_embed_url || '',
    facebook_url: props.settings.facebook_url || '',
    instagram_url: props.settings.instagram_url || '',
    youtube_url: props.settings.youtube_url || '',
    logo: null,
    hero_image: null,
    banner_profile: null,
    banner_ppdb: null,
    banner_news: null,
    banner_downloads: null,
    banner_calendar: null,
});

const logoInput = ref(null);
const logoPreview = ref(props.settings.logo_path ? `/storage/${props.settings.logo_path}` : null);
const principalPhotoInput = ref(null);
const principalPhotoPreview = ref(props.settings.principal_photo ? `/storage/${props.settings.principal_photo}` : null);

const handleLogoChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        const error = checkFileSize(file, IMAGE_MAX_MB);
        if (error) {
            form.errors.logo = error;
            e.target.value = '';
            return;
        }
        form.errors.logo = null;
        form.logo = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            logoPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

// Background/banner images — a single config drives the upload grid.
const backgroundFields = [
    { key: 'hero_image', label: 'Hero Beranda', hint: 'Gambar besar di bagian atas halaman Beranda. Disarankan 1920x1080 px (landscape).' },
    { key: 'banner_profile', label: 'Banner Profil', hint: 'Latar banner halaman Profil.' },
    { key: 'banner_ppdb', label: 'Banner PPDB', hint: 'Latar banner halaman PPDB.' },
    { key: 'banner_news', label: 'Banner Berita', hint: 'Latar banner halaman Berita & Pengumuman.' },
    { key: 'banner_downloads', label: 'Banner Unduhan', hint: 'Latar banner halaman Unduhan.' },
    { key: 'banner_calendar', label: 'Banner Kalender', hint: 'Latar banner halaman Kalender Akademik.' },
];

const bgPreviews = reactive(
    backgroundFields.reduce((acc, f) => {
        acc[f.key] = props.settings[f.key] ? `/storage/${props.settings[f.key]}` : null;
        return acc;
    }, {})
);

const handleBackgroundChange = (key, e) => {
    const file = e.target.files[0];
    if (file) {
        const error = checkFileSize(file, IMAGE_MAX_MB);
        if (error) {
            form.errors[key] = error;
            e.target.value = '';
            return;
        }
        form.errors[key] = null;
        form[key] = file;
        const reader = new FileReader();
        reader.onload = (ev) => {
            bgPreviews[key] = ev.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const handlePrincipalPhotoChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        const error = checkFileSize(file, IMAGE_MAX_MB);
        if (error) {
            form.errors.principal_photo = error;
            e.target.value = '';
            return;
        }
        form.errors.principal_photo = null;
        form.principal_photo = file;
        const reader = new FileReader();
        reader.onload = (ev) => {
            principalPhotoPreview.value = ev.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const submit = () => {
    // Note: To send files via PUT in Laravel via Inertia, we actually use POST with _method=PUT
    form.transform((data) => ({
        ...data,
        _method: 'PUT'
    })).post('/admin/pengaturan', {
        preserveScroll: true,
    });
};
</script>

<template>
    <AdminLayout title="Pengaturan Sekolah">
        <div class="mb-6">
            <p class="text-slate-500 text-sm">Kelola identitas, kontak, dan informasi dasar sekolah yang akan ditampilkan di website.</p>
        </div>

        <form @submit.prevent="submit" class="space-y-6 max-w-5xl">
            <!-- Alert Success -->
            <div v-if="$page.props.flash?.success" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 shrink-0 mt-0.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-sm font-medium">{{ $page.props.flash.success }}</div>
            </div>

            <!-- Block 1: Identitas -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h2 class="font-bold text-slate-800">Identitas Sekolah</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Logo Upload -->
                        <div class="md:col-span-2 flex flex-col sm:flex-row items-center sm:items-start gap-6 pb-6 border-b border-slate-100">
                            <div class="w-24 h-24 rounded-2xl border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden bg-slate-50">
                                <img v-if="logoPreview" :src="logoPreview" class="w-full h-full object-contain p-2" />
                                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-slate-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                            </div>
                            <div class="text-center sm:text-left">
                                <h3 class="font-semibold text-slate-700 mb-1">Logo Sekolah</h3>
                                <p class="text-sm text-slate-500 mb-3">Format JPG/PNG dengan background transparan. Disarankan resolusi 256x256 px.</p>
                                <button type="button" @click="$refs.logoInput.click()" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                    Upload Logo Baru
                                </button>
                                <input ref="logoInput" type="file" accept="image/png, image/jpeg, image/jpg" class="hidden" @change="handleLogoChange">
                                <p v-if="form.errors.logo" class="mt-2 text-sm text-red-600">{{ form.errors.logo }}</p>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Sekolah <span class="text-red-500">*</span></label>
                            <input v-model="form.school_name" type="text" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-lg font-semibold">
                            <p v-if="form.errors.school_name" class="mt-1 text-sm text-red-600">{{ form.errors.school_name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">NPSN</label>
                            <input v-model="form.npsn" type="text" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Akreditasi</label>
                            <input v-model="form.accreditation" type="text" placeholder="Contoh: A" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Tahun Berdiri</label>
                            <input v-model="form.established_year" type="text" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Block: Statistik & Beranda -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h2 class="font-bold text-slate-800">Statistik & Beranda</h2>
                    <p class="text-xs text-slate-500 mt-1">Angka yang tampil pada bagian statistik beranda. Jumlah Guru & Ekstrakurikuler dihitung otomatis dari data.</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Total Siswa</label>
                            <input v-model="form.total_students" type="number" min="0" placeholder="Contoh: 320" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm">
                            <p v-if="form.errors.total_students" class="mt-1 text-sm text-red-600">{{ form.errors.total_students }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Jumlah Prestasi</label>
                            <input v-model="form.total_achievements" type="number" min="0" placeholder="Contoh: 50" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm">
                            <p v-if="form.errors.total_achievements" class="mt-1 text-sm text-red-600">{{ form.errors.total_achievements }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Motto Sekolah</label>
                            <input v-model="form.motto" type="text" placeholder="Contoh: Cerdas, Berkarakter, Berprestasi" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm">
                            <p v-if="form.errors.motto" class="mt-1 text-sm text-red-600">{{ form.errors.motto }}</p>
                        </div>

                        <div class="md:col-span-2 border-t border-slate-100 pt-6 mt-2">
                            <h3 class="font-semibold text-slate-700 mb-4">Sambutan Kepala Sekolah</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-32 h-40 rounded-xl border-2 border-dashed border-slate-300 overflow-hidden bg-slate-50 flex items-center justify-center">
                                        <img v-if="principalPhotoPreview" :src="principalPhotoPreview" class="w-full h-full object-cover" />
                                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-slate-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                        </svg>
                                    </div>
                                    <label class="inline-block px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors cursor-pointer">
                                        {{ principalPhotoPreview ? 'Ganti Foto' : 'Upload Foto' }}
                                        <input ref="principalPhotoInput" type="file" accept="image/*" class="hidden" @change="handlePrincipalPhotoChange">
                                    </label>
                                    <p class="text-xs text-slate-500 text-center">Foto Kepala Sekolah. Rasio 3:4 disarankan.</p>
                                    <p v-if="form.errors.principal_photo" class="text-sm text-red-600">{{ form.errors.principal_photo }}</p>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Kepala Sekolah</label>
                                        <input v-model="form.principal_name" type="text" placeholder="Nama & gelar" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm">
                                        <p v-if="form.errors.principal_name" class="mt-1 text-sm text-red-600">{{ form.errors.principal_name }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Teks Sambutan</label>
                                        <textarea v-model="form.principal_greeting" rows="5" placeholder="Tulis kata sambutan dari kepala sekolah..." class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm"></textarea>
                                        <p v-if="form.errors.principal_greeting" class="mt-1 text-sm text-red-600">{{ form.errors.principal_greeting }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Block 2: Kontak -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h2 class="font-bold text-slate-800">Kontak & Lokasi</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat Lengkap</label>
                            <textarea v-model="form.address" rows="3" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">No. Telepon / WhatsApp</label>
                            <input v-model="form.phone" type="text" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat Email</label>
                            <input v-model="form.email" type="email" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Google Maps Embed URL</label>
                            <input v-model="form.map_embed_url" type="text" placeholder="https://www.google.com/maps/embed?..." class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm">
                            <p class="text-xs text-slate-500 mt-1">Masukkan URL (src) dari Google Maps iframe untuk halaman kontak.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Block 3: Sosial Media -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h2 class="font-bold text-slate-800">Media Sosial</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Facebook URL</label>
                            <input v-model="form.facebook_url" type="url" placeholder="https://facebook.com/..." class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Instagram URL</label>
                            <input v-model="form.instagram_url" type="url" placeholder="https://instagram.com/..." class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">YouTube URL</label>
                            <input v-model="form.youtube_url" type="url" placeholder="https://youtube.com/..." class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Block 4: Tampilan & Background -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h2 class="font-bold text-slate-800">Tampilan Website</h2>
                    <p class="text-xs text-slate-500 mt-1">Atur gambar latar (background) hero beranda dan banner tiap halaman. Kosongkan untuk memakai tampilan bawaan. Format JPG/PNG/WebP, maks 4 MB.</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div v-for="field in backgroundFields" :key="field.key" class="border border-slate-200 rounded-xl p-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">{{ field.label }}</label>
                            <div class="aspect-video w-full rounded-lg border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden bg-slate-50 mb-3">
                                <img v-if="bgPreviews[field.key]" :src="bgPreviews[field.key]" class="w-full h-full object-cover" />
                                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-slate-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                            </div>
                            <p class="text-xs text-slate-500 mb-3">{{ field.hint }}</p>
                            <label class="inline-block px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors cursor-pointer">
                                {{ bgPreviews[field.key] ? 'Ganti Gambar' : 'Upload Gambar' }}
                                <input type="file" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden" @change="(e) => handleBackgroundChange(field.key, e)">
                            </label>
                            <p v-if="form.errors[field.key]" class="mt-2 text-sm text-red-600">{{ form.errors[field.key] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 pb-12">
                <button 
                    type="submit" 
                    :disabled="form.processing"
                    class="inline-flex justify-center items-center gap-2 rounded-lg bg-indigo-600 py-3 px-8 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition-colors"
                >
                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ form.processing ? 'Menyimpan...' : 'Simpan Pengaturan' }}
                </button>
            </div>
        </form>
    </AdminLayout>
</template>
