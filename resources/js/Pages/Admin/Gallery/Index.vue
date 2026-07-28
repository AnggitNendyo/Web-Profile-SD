<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataTable from '@/Components/Admin/DataTable.vue';
import ConfirmModal from '@/Components/Admin/ConfirmModal.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { format, parseISO } from 'date-fns';
import { id } from 'date-fns/locale';

const props = defineProps({
    galleries: {
        type: Object,
        required: true,
    }
});

const columns = [
    { key: 'thumbnail', label: 'Preview' },
    { key: 'title', label: 'Judul' },
    { key: 'type', label: 'Tipe' },
    { key: 'category', label: 'Kategori' },
    { key: 'created_at', label: 'Tgl Upload' },
];

const showDeleteModal = ref(false);
const showAddModal = ref(false);
const itemToDelete = ref(null);

const form = useForm({
    title: '',
    type: 'foto',
    category: '',
    file: null,
});

const deleteForm = useForm({});

const fileInput = ref(null);
const filePreview = ref(null);

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.file = file;
        
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                filePreview.value = e.target.result;
            };
            reader.readAsDataURL(file);
        } else if (file.type.startsWith('video/')) {
            filePreview.value = 'video-placeholder'; // Simple placeholder
        }
    }
};

const openAddModal = () => {
    form.reset();
    filePreview.value = null;
    showAddModal.value = true;
};

const submitAdd = () => {
    form.post('/admin/galeri', {
        preserveScroll: true,
        onSuccess: () => {
            showAddModal.value = false;
            form.reset();
            filePreview.value = null;
        }
    });
};

const confirmDelete = (item) => {
    itemToDelete.value = item;
    showDeleteModal.value = true;
};

const deleteItem = () => {
    deleteForm.delete(`/admin/galeri/${itemToDelete.value.id}`, {
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
    <AdminLayout title="Manajemen Galeri">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <p class="text-slate-500 text-sm">Kelola foto dan video kegiatan sekolah.</p>
            </div>
            <button @click="openAddModal" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Galeri
            </button>
        </div>

        <DataTable :columns="columns" :data="galleries">
            <!-- Custom Cell: Thumbnail -->
            <template #cell-thumbnail="{ item }">
                <div class="w-20 h-14 bg-slate-100 rounded overflow-hidden relative">
                    <img v-if="item.type === 'foto'" :src="`/storage/${item.file_path}`" class="w-full h-full object-cover" />
                    <div v-else class="w-full h-full bg-slate-800 flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path fill-rule="evenodd" d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </template>

            <!-- Custom Cell: Title -->
            <template #cell-title="{ item }">
                <div class="font-medium text-slate-800">{{ item.title }}</div>
            </template>

            <!-- Custom Cell: Type -->
            <template #cell-type="{ item }">
                <span :class="[
                    'px-2.5 py-1 text-xs font-semibold rounded-full uppercase tracking-wider',
                    item.type === 'foto' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700'
                ]">
                    {{ item.type }}
                </span>
            </template>

            <!-- Custom Cell: Created At -->
            <template #cell-created_at="{ item }">
                <span class="text-sm">{{ formatDate(item.created_at) }}</span>
            </template>

            <!-- Actions -->
            <template #actions="{ item }">
                <a :href="`/storage/${item.file_path}`" target="_blank" class="p-1.5 text-slate-400 hover:text-indigo-600 transition-colors bg-slate-50 hover:bg-indigo-50 rounded" title="Lihat">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </a>
                <button @click="confirmDelete(item)" class="p-1.5 text-slate-400 hover:text-red-600 transition-colors bg-slate-50 hover:bg-red-50 rounded" title="Hapus">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </button>
            </template>
        </DataTable>

        <!-- Add Modal -->
        <Teleport to="body">
            <div v-if="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showAddModal = false"></div>
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg relative z-10 overflow-hidden flex flex-col max-h-[90vh]">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                        <h3 class="font-bold text-slate-800 text-lg">Tambah Item Galeri</h3>
                        <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <form @submit.prevent="submitAdd" class="overflow-y-auto p-6 space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Judul <span class="text-red-500">*</span></label>
                            <input v-model="form.title" type="text" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm">
                            <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">{{ form.errors.title }}</p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Tipe Media <span class="text-red-500">*</span></label>
                                <select v-model="form.type" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm">
                                    <option value="foto">Foto (Gambar)</option>
                                    <option value="video">Video (MP4/WebM)</option>
                                </select>
                                <p v-if="form.errors.type" class="mt-1 text-sm text-red-600">{{ form.errors.type }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori (Opsional)</label>
                                <input v-model="form.category" type="text" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm" placeholder="Contoh: Ekstrakurikuler">
                                <p v-if="form.errors.category" class="mt-1 text-sm text-red-600">{{ form.errors.category }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">File Media <span class="text-red-500">*</span></label>
                            
                            <!-- Custom File Upload Area -->
                            <div 
                                @click="$refs.fileInput.click()"
                                class="border-2 border-dashed border-slate-300 rounded-xl p-6 flex flex-col items-center justify-center cursor-pointer hover:bg-slate-50 transition-colors group relative overflow-hidden"
                            >
                                <div v-if="!filePreview" class="text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-slate-400 mx-auto mb-2 group-hover:text-indigo-500 transition-colors">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                                    </svg>
                                    <p class="text-sm font-medium text-indigo-600">Pilih file untuk diupload</p>
                                    <p class="text-xs text-slate-500 mt-1">Maks. 10MB (JPG, PNG, MP4)</p>
                                </div>
                                <div v-else-if="filePreview === 'video-placeholder'" class="text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-16 h-16 text-indigo-500 mx-auto mb-2">
                                        <path fill-rule="evenodd" d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd" />
                                    </svg>
                                    <p class="text-sm font-medium text-slate-800">{{ form.file.name }}</p>
                                </div>
                                <img v-else :src="filePreview" class="absolute inset-0 w-full h-full object-cover" />
                                
                                <div v-if="filePreview" class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="px-3 py-1 bg-white text-slate-800 text-xs font-semibold rounded">Ganti File</span>
                                </div>
                            </div>
                            
                            <input ref="fileInput" type="file" :accept="form.type === 'foto' ? 'image/*' : 'video/mp4,video/webm'" class="hidden" @change="handleFileChange">
                            <p v-if="form.errors.file" class="mt-1 text-sm text-red-600">{{ form.errors.file }}</p>
                        </div>
                        
                        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                            <button type="button" @click="showAddModal = false" class="px-4 py-2 bg-white text-slate-700 font-medium text-sm rounded-lg border border-slate-300 hover:bg-slate-50 transition-colors">Batal</button>
                            <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50 flex items-center gap-2">
                                <svg v-if="form.processing" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Teleport>

        <ConfirmModal 
            :show="showDeleteModal"
            title="Hapus Galeri"
            :message="`Apakah Anda yakin ingin menghapus '${itemToDelete?.title}'? File media juga akan dihapus.`"
            @close="showDeleteModal = false"
            @confirm="deleteItem"
        />
    </AdminLayout>
</template>
