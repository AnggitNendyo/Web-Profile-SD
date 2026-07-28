<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataTable from '@/Components/Admin/DataTable.vue';
import ConfirmModal from '@/Components/Admin/ConfirmModal.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { format, parseISO } from 'date-fns';
import { id } from 'date-fns/locale';

const props = defineProps({
    downloads: {
        type: Object,
        required: true,
    }
});

const columns = [
    { key: 'title', label: 'Judul Dokumen' },
    { key: 'category', label: 'Kategori' },
    { key: 'created_at', label: 'Tgl Upload' },
];

const showDeleteModal = ref(false);
const showAddModal = ref(false);
const itemToDelete = ref(null);

const form = useForm({
    title: '',
    category: '',
    file: null,
});

const deleteForm = useForm({});

const fileInput = ref(null);

const handleFileChange = (e) => {
    form.file = e.target.files[0];
};

const openAddModal = () => {
    form.reset();
    showAddModal.value = true;
};

const submitForm = () => {
    form.post('/admin/unduhan', {
        preserveScroll: true,
        onSuccess: () => {
            showAddModal.value = false;
            form.reset();
            if (fileInput.value) fileInput.value.value = '';
        }
    });
};

const confirmDelete = (item) => {
    itemToDelete.value = item;
    showDeleteModal.value = true;
};

const deleteItem = () => {
    deleteForm.delete(`/admin/unduhan/${itemToDelete.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteModal.value = false;
            itemToDelete.value = null;
        }
    });
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return format(parseISO(dateString), 'dd MMM yyyy', { locale: id });
};
</script>

<template>
    <AdminLayout title="Manajemen Unduhan">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <p class="text-slate-500 text-sm">Kelola file dokumen yang dapat diunduh oleh publik.</p>
            </div>
            <button @click="openAddModal" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Upload File Baru
            </button>
        </div>

        <DataTable :columns="columns" :data="downloads">
            <!-- Custom Cell: Title -->
            <template #cell-title="{ item }">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <div>
                        <a :href="`/storage/${item.file_path}`" target="_blank" class="font-bold text-slate-800 hover:text-indigo-600 transition-colors">
                            {{ item.title }}
                        </a>
                    </div>
                </div>
            </template>

            <!-- Custom Cell: Category -->
            <template #cell-category="{ item }">
                <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded border border-slate-200">
                    {{ item.category || 'Umum' }}
                </span>
            </template>

            <!-- Custom Cell: Created At -->
            <template #cell-created_at="{ item }">
                <span class="text-sm">{{ formatDate(item.created_at) }}</span>
            </template>

            <!-- Actions -->
            <template #actions="{ item }">
                <a :href="`/storage/${item.file_path}`" target="_blank" class="p-1.5 text-slate-400 hover:text-indigo-600 transition-colors bg-slate-50 hover:bg-indigo-50 rounded inline-flex" title="Lihat/Download File">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                </a>
                <button @click="confirmDelete(item)" class="p-1.5 ml-1 text-slate-400 hover:text-red-600 transition-colors bg-slate-50 hover:bg-red-50 rounded inline-flex" title="Hapus File">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </button>
            </template>
        </DataTable>

        <!-- Form Modal -->
        <Teleport to="body">
            <div v-if="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showAddModal = false"></div>
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg relative z-10 overflow-hidden flex flex-col max-h-[90vh]">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                        <h3 class="font-bold text-slate-800 text-lg">Upload Dokumen Baru</h3>
                        <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <form @submit.prevent="submitForm" class="overflow-y-auto p-6 space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Judul Dokumen <span class="text-red-500">*</span></label>
                            <input v-model="form.title" type="text" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm" placeholder="Contoh: Jadwal Pelajaran Semester Genap">
                            <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">{{ form.errors.title }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori Dokumen <span class="text-red-500">*</span></label>
                            <select v-model="form.category" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm">
                                <option value="" disabled>Pilih Kategori...</option>
                                <option value="Akademik">Akademik</option>
                                <option value="Kesiswaan">Kesiswaan</option>
                                <option value="BOS">BOS (Bantuan Operasional Sekolah)</option>
                                <option value="Umum">Umum</option>
                            </select>
                            <p v-if="form.errors.category" class="mt-1 text-sm text-red-600">{{ form.errors.category }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih File <span class="text-red-500">*</span></label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-lg hover:border-indigo-500 transition-colors bg-slate-50">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-slate-600 justify-center">
                                        <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Upload a file</span>
                                            <input id="file-upload" ref="fileInput" name="file-upload" type="file" class="sr-only" required @change="handleFileChange">
                                        </label>
                                    </div>
                                    <p class="text-xs text-slate-500">PDF, DOC, DOCX, XLS, XLSX hingga 10MB</p>
                                </div>
                            </div>
                            <p v-if="form.file" class="mt-2 text-sm text-green-600 font-medium break-all">
                                File terpilih: {{ form.file.name }}
                            </p>
                            <p v-if="form.errors.file" class="mt-1 text-sm text-red-600">{{ form.errors.file }}</p>
                        </div>
                        
                        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                            <button type="button" @click="showAddModal = false" class="px-4 py-2 bg-white text-slate-700 font-medium text-sm rounded-lg border border-slate-300 hover:bg-slate-50 transition-colors">Batal</button>
                            <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50 flex items-center gap-2">
                                <svg v-if="form.processing" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ form.processing ? 'Mengunggah...' : 'Upload' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Teleport>

        <ConfirmModal 
            :show="showDeleteModal"
            title="Hapus Dokumen"
            :message="`Apakah Anda yakin ingin menghapus file '${itemToDelete?.title}'? Tindakan ini tidak dapat dibatalkan dan file akan dihapus dari server.`"
            @close="showDeleteModal = false"
            @confirm="deleteItem"
        />
    </AdminLayout>
</template>
