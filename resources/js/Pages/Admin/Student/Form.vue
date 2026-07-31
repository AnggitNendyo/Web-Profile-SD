<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { checkFileSize, IMAGE_MAX_MB } from '@/lib/uploadLimits';

const props = defineProps({
    student: {
        type: Object,
        default: null,
    }
});

const isEditing = computed(() => !!props.student);

const form = useForm({
    nisn: props.student?.nisn || '',
    nis: props.student?.nis || '',
    name: props.student?.name || '',
    gender: props.student?.gender || 'Laki-laki',
    place_of_birth: props.student?.place_of_birth || '',
    date_of_birth: props.student?.date_of_birth ? props.student.date_of_birth.split('T')[0] : '',
    religion: props.student?.religion || '',
    address: props.student?.address || '',
    parent_name: props.student?.parent_name || '',
    parent_phone: props.student?.parent_phone || '',
    enrollment_year: props.student?.enrollment_year || new Date().getFullYear(),
    status: props.student?.status || 'Aktif',
    photo: null,
    _method: isEditing.value ? 'PUT' : 'POST',
});

const fileInput = ref(null);
const imagePreview = ref(props.student?.photo ? `/storage/${props.student.photo}` : null);

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
        form.post(`/admin/siswa/${props.student.id}`);
    } else {
        form.post('/admin/siswa');
    }
};
</script>

<template>
    <AdminLayout :title="isEditing ? 'Edit Data Siswa' : 'Tambah Data Siswa'">
        <div class="mb-6">
            <Link href="/admin/siswa" class="text-sm font-medium text-slate-500 hover:text-indigo-600 flex items-center gap-1 transition-colors w-fit">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </Link>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 max-w-4xl mx-auto overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h2 class="font-bold text-slate-800">{{ isEditing ? 'Edit Informasi Siswa' : 'Informasi Siswa Baru' }}</h2>
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
                        <h4 class="text-sm font-semibold text-slate-700 mb-1">Foto Siswa</h4>
                        <p class="text-xs text-slate-500 mb-3">Format JPG, PNG. Ukuran maksimal 2MB. Rasio disarankan 3:4 atau 1:1.</p>
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
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input 
                            id="name" 
                            v-model="form.name" 
                            type="text" 
                            required
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                        >
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                    </div>
                    
                    <!-- NISN -->
                    <div>
                        <label for="nisn" class="block text-sm font-semibold text-slate-700 mb-1">NISN</label>
                        <input 
                            id="nisn" 
                            v-model="form.nisn" 
                            type="text" 
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                        >
                        <p v-if="form.errors.nisn" class="mt-1 text-sm text-red-600">{{ form.errors.nisn }}</p>
                    </div>

                    <!-- NIS -->
                    <div>
                        <label for="nis" class="block text-sm font-semibold text-slate-700 mb-1">NIS Lokal</label>
                        <input 
                            id="nis" 
                            v-model="form.nis" 
                            type="text" 
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                        >
                        <p v-if="form.errors.nis" class="mt-1 text-sm text-red-600">{{ form.errors.nis }}</p>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label for="gender" class="block text-sm font-semibold text-slate-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select 
                            id="gender" 
                            v-model="form.gender" 
                            required
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                        >
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                        <p v-if="form.errors.gender" class="mt-1 text-sm text-red-600">{{ form.errors.gender }}</p>
                    </div>

                    <!-- Agama -->
                    <div>
                        <label for="religion" class="block text-sm font-semibold text-slate-700 mb-1">Agama</label>
                        <select 
                            id="religion" 
                            v-model="form.religion" 
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                        >
                            <option value="">Pilih Agama</option>
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                        <p v-if="form.errors.religion" class="mt-1 text-sm text-red-600">{{ form.errors.religion }}</p>
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label for="place_of_birth" class="block text-sm font-semibold text-slate-700 mb-1">Tempat Lahir</label>
                        <input 
                            id="place_of_birth" 
                            v-model="form.place_of_birth" 
                            type="text" 
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                        >
                        <p v-if="form.errors.place_of_birth" class="mt-1 text-sm text-red-600">{{ form.errors.place_of_birth }}</p>
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="date_of_birth" class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Lahir</label>
                        <input 
                            id="date_of_birth" 
                            v-model="form.date_of_birth" 
                            type="date" 
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                        >
                        <p v-if="form.errors.date_of_birth" class="mt-1 text-sm text-red-600">{{ form.errors.date_of_birth }}</p>
                    </div>

                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-semibold text-slate-700 mb-1">Alamat Lengkap</label>
                        <textarea 
                            id="address" 
                            v-model="form.address" 
                            rows="3"
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                        ></textarea>
                        <p v-if="form.errors.address" class="mt-1 text-sm text-red-600">{{ form.errors.address }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <hr class="border-slate-100">
                    </div>

                    <!-- Nama Orang Tua -->
                    <div>
                        <label for="parent_name" class="block text-sm font-semibold text-slate-700 mb-1">Nama Orang Tua/Wali</label>
                        <input 
                            id="parent_name" 
                            v-model="form.parent_name" 
                            type="text" 
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                        >
                        <p v-if="form.errors.parent_name" class="mt-1 text-sm text-red-600">{{ form.errors.parent_name }}</p>
                    </div>

                    <!-- No HP Orang Tua -->
                    <div>
                        <label for="parent_phone" class="block text-sm font-semibold text-slate-700 mb-1">No HP Orang Tua/Wali</label>
                        <input 
                            id="parent_phone" 
                            v-model="form.parent_phone" 
                            type="text" 
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                        >
                        <p v-if="form.errors.parent_phone" class="mt-1 text-sm text-red-600">{{ form.errors.parent_phone }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <hr class="border-slate-100">
                    </div>

                    <!-- Tahun Masuk -->
                    <div>
                        <label for="enrollment_year" class="block text-sm font-semibold text-slate-700 mb-1">Tahun Masuk <span class="text-red-500">*</span></label>
                        <input 
                            id="enrollment_year" 
                            v-model="form.enrollment_year" 
                            type="number" 
                            required
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                        >
                        <p v-if="form.errors.enrollment_year" class="mt-1 text-sm text-red-600">{{ form.errors.enrollment_year }}</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-semibold text-slate-700 mb-1">Status Siswa <span class="text-red-500">*</span></label>
                        <select 
                            id="status" 
                            v-model="form.status" 
                            required
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                        >
                            <option value="Aktif">Aktif</option>
                            <option value="Lulus">Lulus</option>
                            <option value="Pindah">Pindah</option>
                            <option value="Dikeluarkan">Dikeluarkan</option>
                        </select>
                        <p v-if="form.errors.status" class="mt-1 text-sm text-red-600">{{ form.errors.status }}</p>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <Link href="/admin/siswa" class="px-4 py-2 bg-white text-slate-700 font-medium text-sm rounded-lg border border-slate-300 hover:bg-slate-50 transition-colors">
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
