<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    extracurricular: {
        type: Object,
        default: null,
    },
    categories: {
        type: Array,
        default: () => [],
    },
});

const isEditing = computed(() => !!props.extracurricular);

const form = useForm({
    name: props.extracurricular?.name || '',
    description: props.extracurricular?.description || '',
    category: props.extracurricular?.category || '',
    coach: props.extracurricular?.coach || '',
    schedule_day: props.extracurricular?.schedule_day || '',
    schedule_time: props.extracurricular?.schedule_time || '',
    sort_order: props.extracurricular?.sort_order || 0,
    photo: null,
    _method: isEditing.value ? 'PUT' : 'POST',
});

const fileInput = ref(null);
const imagePreview = ref(props.extracurricular?.photo ? `/storage/${props.extracurricular.photo}` : null);

const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
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
        form.post('/admin/ekstrakurikuler');
    } else {
        form.post(`/admin/ekstrakurikuler/${props.extracurricular.id}`);
    }
};
</script>

<template>
    <AdminLayout :title="isEditing ? 'Edit Ekstrakurikuler' : 'Tambah Ekstrakurikuler'">
        <div class="mb-6">
            <Link href="/admin/ekstrakurikuler" class="text-sm font-medium text-slate-500 hover:text-indigo-600 flex items-center gap-1 transition-colors w-fit">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </Link>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 max-w-3xl mx-auto overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h2 class="font-bold text-slate-800">{{ isEditing ? 'Edit Informasi Ekstrakurikuler' : 'Ekstrakurikuler Baru' }}</h2>
            </div>

            <form @submit.prevent="submit" class="p-6 md:p-8 space-y-6">
                <!-- Foto -->
                <div class="flex flex-col sm:flex-row gap-6 items-start sm:items-center">
                    <div class="w-32 h-32 rounded-xl border-4 border-slate-100 shadow-sm overflow-hidden shrink-0 relative group bg-slate-50 flex items-center justify-center">
                        <img v-if="imagePreview" :src="imagePreview" class="w-full h-full object-cover" />
                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-slate-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z" />
                        </svg>

                        <div class="absolute inset-0 bg-slate-900/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer" @click="$refs.fileInput.click()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                            </svg>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-semibold text-slate-700 mb-1">Foto Kegiatan</h4>
                        <p class="text-xs text-slate-500 mb-3">Format JPG, PNG. Ukuran maksimal 2MB.</p>
                        <button type="button" @click="$refs.fileInput.click()" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                            Pilih Foto
                        </button>
                        <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="handleImageChange">
                        <p v-if="form.errors.photo" class="mt-2 text-sm text-red-600">{{ form.errors.photo }}</p>
                    </div>
                </div>

                <hr class="border-slate-100">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Nama Ekstrakurikuler <span class="text-red-500">*</span></label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            required
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                            placeholder="Contoh: Pramuka, Futsal, Tari"
                        >
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="category" class="block text-sm font-semibold text-slate-700 mb-1">Kategori</label>
                        <select
                            id="category"
                            v-model="form.category"
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                        >
                            <option value="">-- Pilih Kategori --</option>
                            <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
                        </select>
                        <p v-if="form.errors.category" class="mt-1 text-sm text-red-600">{{ form.errors.category }}</p>
                    </div>

                    <!-- Pembina -->
                    <div>
                        <label for="coach" class="block text-sm font-semibold text-slate-700 mb-1">Pembina / Pelatih</label>
                        <input
                            id="coach"
                            v-model="form.coach"
                            type="text"
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                            placeholder="Nama pembina"
                        >
                        <p v-if="form.errors.coach" class="mt-1 text-sm text-red-600">{{ form.errors.coach }}</p>
                    </div>

                    <!-- Hari -->
                    <div>
                        <label for="schedule_day" class="block text-sm font-semibold text-slate-700 mb-1">Hari Kegiatan</label>
                        <input
                            id="schedule_day"
                            v-model="form.schedule_day"
                            type="text"
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                            placeholder="Contoh: Jumat"
                        >
                        <p v-if="form.errors.schedule_day" class="mt-1 text-sm text-red-600">{{ form.errors.schedule_day }}</p>
                    </div>

                    <!-- Jam -->
                    <div>
                        <label for="schedule_time" class="block text-sm font-semibold text-slate-700 mb-1">Jam Kegiatan</label>
                        <input
                            id="schedule_time"
                            v-model="form.schedule_time"
                            type="text"
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                            placeholder="Contoh: 14.00 - 16.00"
                        >
                        <p v-if="form.errors.schedule_time" class="mt-1 text-sm text-red-600">{{ form.errors.schedule_time }}</p>
                    </div>

                    <!-- Deskripsi -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi</label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="4"
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                            placeholder="Deskripsi singkat kegiatan ekstrakurikuler..."
                        ></textarea>
                        <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
                    </div>

                    <!-- Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-semibold text-slate-700 mb-1">Urutan Tampil <span class="text-red-500">*</span></label>
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
                    <Link href="/admin/ekstrakurikuler" class="px-4 py-2 bg-white text-slate-700 font-medium text-sm rounded-lg border border-slate-300 hover:bg-slate-50 transition-colors">
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
