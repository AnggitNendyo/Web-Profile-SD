<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { checkFileSize, IMAGE_MAX_MB } from '@/lib/uploadLimits';

const props = defineProps({
    teacher: {
        type: Object,
        default: null,
    }
});

const isEditing = computed(() => !!props.teacher);

const form = useForm({
    name: props.teacher?.name || '',
    nip: props.teacher?.nip || '',
    position: props.teacher?.position || '',
    subject: props.teacher?.subject || '',
    education: props.teacher?.education || [],
    bio: props.teacher?.bio || '',
    sort_order: props.teacher?.sort_order || 0,
    photo: null,
    _method: isEditing.value ? 'PUT' : 'POST',
});

const addEducation = () => {
    form.education.push({ degree: '', program: '', university: '', year: '' });
};

const removeEducation = (index) => {
    form.education.splice(index, 1);
};

const fileInput = ref(null);
const imagePreview = ref(props.teacher?.photo ? `/storage/${props.teacher.photo}` : null);

const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        const error = checkFileSize(file, IMAGE_MAX_MB);
        if (error) {
            form.errors.photo = error;
            e.target.value = '';
            return;
        }
        form.errors.photo = null;
        form.photo = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const submit = () => {
    if (!isEditing.value) {
        delete form._method;
    }

    if (isEditing.value) {
        form.post(`/admin/guru/${props.teacher.id}`);
    } else {
        form.post('/admin/guru');
    }
};
</script>

<template>
    <AdminLayout :title="isEditing ? 'Edit Data Guru' : 'Tambah Data Guru'">
        <div class="mb-6">
            <Link href="/admin/guru" class="text-sm font-medium text-slate-500 hover:text-indigo-600 flex items-center gap-1 transition-colors w-fit">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </Link>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 max-w-3xl mx-auto overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h2 class="font-bold text-slate-800">{{ isEditing ? 'Edit Informasi Guru' : 'Informasi Guru Baru' }}</h2>
            </div>
            
            <form @submit.prevent="submit" class="p-6 md:p-8 space-y-6">
                <!-- Foto Profile -->
                <div class="flex flex-col sm:flex-row gap-6 items-start sm:items-center">
                    <div class="w-32 h-32 rounded-full border-4 border-slate-100 shadow-sm overflow-hidden shrink-0 relative group bg-slate-50 flex items-center justify-center">
                        <img v-if="imagePreview" :src="imagePreview" class="w-full h-full object-cover" />
                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-slate-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        
                        <div class="absolute inset-0 bg-slate-900/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer" @click="$refs.fileInput.click()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                            </svg>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700 mb-1">Foto Profil</h4>
                        <p class="text-xs text-slate-500 mb-3">Format JPG, PNG. Ukuran maksimal 2MB. Rasio disarankan 1:1 (Persegi).</p>
                        <button type="button" @click="$refs.fileInput.click()" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                            Pilih Foto
                        </button>
                        <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="handleImageChange">
                        <p v-if="form.errors.photo" class="mt-2 text-sm text-red-600">{{ form.errors.photo }}</p>
                    </div>
                </div>

                <hr class="border-slate-100">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap & Gelar <span class="text-red-500">*</span></label>
                        <input 
                            id="name" 
                            v-model="form.name" 
                            type="text" 
                            required
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                        >
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                    </div>
                    
                    <!-- NIP -->
                    <div>
                        <label for="nip" class="block text-sm font-semibold text-slate-700 mb-1">NIP (Opsional)</label>
                        <input 
                            id="nip" 
                            v-model="form.nip" 
                            type="text" 
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                        >
                        <p v-if="form.errors.nip" class="mt-1 text-sm text-red-600">{{ form.errors.nip }}</p>
                    </div>
                    
                    <!-- Jabatan -->
                    <div>
                        <label for="position" class="block text-sm font-semibold text-slate-700 mb-1">Jabatan <span class="text-red-500">*</span></label>
                        <input 
                            id="position" 
                            v-model="form.position" 
                            type="text" 
                            required
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                            placeholder="Contoh: Guru Kelas 1, Kepala Sekolah"
                        >
                        <p v-if="form.errors.position" class="mt-1 text-sm text-red-600">{{ form.errors.position }}</p>
                    </div>
                    


                    <!-- Riwayat Pendidikan -->
                    <div class="md:col-span-2">
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-semibold text-slate-700">Riwayat Pendidikan</label>
                            <button type="button" @click="addEducation" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                + Tambah Riwayat
                            </button>
                        </div>
                        
                        <div v-if="form.education.length === 0" class="text-sm text-slate-500 italic mb-2">
                            Belum ada riwayat pendidikan yang ditambahkan.
                        </div>

                        <div v-for="(edu, index) in form.education" :key="index" class="p-4 bg-slate-50 rounded-lg border border-slate-200 mb-3 relative">
                            <button type="button" @click="removeEducation(index)" class="absolute top-2 right-2 text-red-500 hover:text-red-700" title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Gelar</label>
                                    <input v-model="edu.degree" type="text" placeholder="Contoh: S1" class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Program Studi</label>
                                    <input v-model="edu.program" type="text" placeholder="Contoh: Pendidikan Matematika" class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Universitas/Instansi</label>
                                    <input v-model="edu.university" type="text" placeholder="Contoh: Universitas Negeri Jakarta" class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tahun Lulus</label>
                                    <input v-model="edu.year" type="text" placeholder="Contoh: 2015" class="w-full text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Biografi / Deskripsi -->
                    <div class="md:col-span-2">
                        <label for="bio" class="block text-sm font-semibold text-slate-700 mb-1">Profil Singkat</label>
                        <textarea 
                            id="bio" 
                            v-model="form.bio" 
                            rows="4"
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                            placeholder="Tuliskan sedikit profil, pengalaman, atau motto guru..."
                        ></textarea>
                        <p v-if="form.errors.bio" class="mt-1 text-sm text-red-600">{{ form.errors.bio }}</p>
                    </div>
                    
                    <!-- Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-semibold text-slate-700 mb-1">Urutan Tampil (Order) <span class="text-red-500">*</span></label>
                        <input
                            id="sort_order"
                            v-model.number="form.sort_order"
                            type="number"
                            min="0"
                            required
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                        >
                        <p class="text-xs text-slate-500 mt-1">Angka lebih kecil tampil lebih dulu.</p>
                        <p v-if="form.errors.sort_order" class="mt-1 text-sm text-red-600">{{ form.errors.sort_order }}</p>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <Link href="/admin/guru" class="px-4 py-2 bg-white text-slate-700 font-medium text-sm rounded-lg border border-slate-300 hover:bg-slate-50 transition-colors">
                        Batal
                    </Link>
                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="inline-flex justify-center items-center gap-2 rounded-lg bg-indigo-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition-colors"
                    >
                        <svg v-if="form.processing" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Data' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
