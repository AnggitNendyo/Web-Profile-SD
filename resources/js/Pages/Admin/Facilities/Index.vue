<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { checkFileSize, IMAGE_MAX_MB } from '@/lib/uploadLimits';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataTable from '@/Components/Admin/DataTable.vue';
import ConfirmModal from '@/Components/Admin/ConfirmModal.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    facilities: {
        type: Object,
        required: true
    }
});

const columns = [
    { key: 'photo', label: 'Foto' },
    { key: 'title', label: 'Nama Fasilitas' },
    { key: 'description', label: 'Deskripsi' },
    { key: 'order_index', label: 'Urutan' }
];

const showModal = ref(false);
const editingId = ref(null);
const showDeleteModal = ref(false);
const itemToDelete = ref(null);
const fileInput = ref(null);
const imagePreview = ref(null);

const form = useForm({
    title: '',
    description: '',
    icon: '',
    photo: null,
    order_index: 0,
    _method: 'POST',
});

const openModal = (facility = null) => {
    form.reset();
    form.clearErrors();
    if (facility) {
        editingId.value = facility.id;
        form.title = facility.title;
        form.description = facility.description;
        form.icon = facility.icon || '';
        form.order_index = facility.order_index || 0;
        form._method = 'PUT';
        imagePreview.value = facility.photo ? `/storage/${facility.photo}` : null;
    } else {
        editingId.value = null;
        form._method = 'POST';
        imagePreview.value = null;
    }
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    form.reset();
    form.clearErrors();
    imagePreview.value = null;
    if (fileInput.value) fileInput.value.value = '';
};

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
        reader.onload = (ev) => {
            imagePreview.value = ev.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const submit = () => {
    // Selalu POST; _method meng-spoof PUT saat edit agar upload file berfungsi.
    if (editingId.value) {
        form.post(route('admin.fasilitas.update', editingId.value), {
            onSuccess: () => closeModal()
        });
    } else {
        form.post(route('admin.fasilitas.store'), {
            onSuccess: () => closeModal()
        });
    }
};

const confirmDelete = (facility) => {
    itemToDelete.value = facility;
    showDeleteModal.value = true;
};

const deleteFacility = () => {
    router.delete(route('admin.fasilitas.destroy', itemToDelete.value.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
            itemToDelete.value = null;
        }
    });
};
</script>

<template>
    <Head title="Kelola Fasilitas" />

    <AdminLayout>
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Fasilitas Unggulan</h2>
                <p class="text-slate-600">Kelola daftar fasilitas yang ditampilkan di halaman profil publik.</p>
            </div>
            <PrimaryButton @click="openModal()" class="gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Fasilitas
            </PrimaryButton>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <DataTable 
                :data="facilities" 
                :columns="columns"
            >
                <template #cell-photo="{ item }">
                    <img
                        v-if="item.photo"
                        :src="`/storage/${item.photo}`"
                        :alt="item.title"
                        class="w-16 h-12 object-cover rounded-lg border border-slate-200"
                    />
                    <div v-else class="w-16 h-12 rounded-lg bg-slate-100 flex items-center justify-center text-slate-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z" />
                        </svg>
                    </div>
                </template>
                <template #cell-description="{ item }">
                    <div class="max-w-md truncate">{{ item.description }}</div>
                </template>
                <template #actions="{ item }">
                    <div class="flex items-center gap-2">
                        <button @click="openModal(item)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                        </button>
                        <button @click="confirmDelete(item)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                        </button>
                    </div>
                </template>
            </DataTable>
        </div>

        <!-- Modal Form -->
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="closeModal">
                    <div class="absolute inset-0 bg-slate-900/75 backdrop-blur-sm"></div>
                </div>

                <div class="relative inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                    <h3 class="text-xl font-bold text-slate-900 mb-6">
                        {{ editingId ? 'Edit Fasilitas' : 'Tambah Fasilitas' }}
                    </h3>

                    <form @submit.prevent="submit" class="space-y-5">
                        <!-- Foto -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Foto Fasilitas</label>
                            <div class="flex items-center gap-4">
                                <div class="w-28 h-20 rounded-lg border border-slate-200 overflow-hidden shrink-0 bg-slate-50 flex items-center justify-center">
                                    <img v-if="imagePreview" :src="imagePreview" class="w-full h-full object-cover" />
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-slate-300">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z" />
                                    </svg>
                                </div>
                                <div>
                                    <button type="button" @click="fileInput.click()" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                        Pilih Foto
                                    </button>
                                    <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="handleImageChange">
                                    <p class="text-xs text-slate-500 mt-1">JPG/PNG/WEBP, maks 2MB.</p>
                                </div>
                            </div>
                            <div v-if="form.errors.photo" class="text-red-500 text-sm mt-1">{{ form.errors.photo }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Fasilitas</label>
                            <TextInput
                                v-model="form.title"
                                type="text"
                                class="w-full"
                                required
                            />
                            <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                            <textarea
                                v-model="form.description"
                                rows="3"
                                class="w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm"
                                required
                            ></textarea>
                            <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">{{ form.errors.description }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Icon SVG (Opsional)</label>
                            <textarea
                                v-model="form.icon"
                                rows="2"
                                placeholder="<path d=... />"
                                class="w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm font-mono text-sm"
                            ></textarea>
                            <p class="text-xs text-slate-500 mt-1">Copy atribut 'd' dari tag &lt;path&gt; SVG Heroicons (ukuran 24x24).</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Urutan (Opsional)</label>
                            <TextInput
                                v-model="form.order_index"
                                type="number"
                                class="w-full"
                            />
                        </div>

                        <div class="flex justify-end gap-3 mt-8">
                            <button type="button" @click="closeModal" class="px-4 py-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl font-medium transition-colors">
                                Batal
                            </button>
                            <PrimaryButton :disabled="form.processing">
                                Simpan
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <ConfirmModal 
            :show="showDeleteModal"
            title="Hapus Fasilitas"
            message="Apakah Anda yakin ingin menghapus fasilitas ini? Tindakan ini tidak dapat dibatalkan."
            @close="showDeleteModal = false"
            @confirm="deleteFacility"
        />
    </AdminLayout>
</template>
