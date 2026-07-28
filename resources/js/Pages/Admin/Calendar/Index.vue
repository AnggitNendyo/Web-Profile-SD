<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataTable from '@/Components/Admin/DataTable.vue';
import ConfirmModal from '@/Components/Admin/ConfirmModal.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    events: {
        type: Object,
        required: true,
    },
    categories: {
        type: Array,
        required: true,
    },
});

const columns = [
    { key: 'title', label: 'Agenda' },
    { key: 'schedule', label: 'Tanggal' },
    { key: 'category', label: 'Kategori' },
];

const categoryColors = {
    Kegiatan: 'bg-indigo-50 text-indigo-700 border-indigo-100',
    Ujian: 'bg-amber-50 text-amber-700 border-amber-100',
    Libur: 'bg-rose-50 text-rose-700 border-rose-100',
    Pendaftaran: 'bg-emerald-50 text-emerald-700 border-emerald-100',
    Rapat: 'bg-sky-50 text-sky-700 border-sky-100',
};

const formatDate = (date) =>
    new Date(date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });

// --- Form modal (create + edit share one modal) ---
const showFormModal = ref(false);
const isEditing = ref(false);
const editingId = ref(null);

const form = useForm({
    title: '',
    description: '',
    start_date: '',
    end_date: '',
    category: '',
});

const openCreate = () => {
    isEditing.value = false;
    editingId.value = null;
    form.reset();
    form.clearErrors();
    showFormModal.value = true;
};

const openEdit = (item) => {
    isEditing.value = true;
    editingId.value = item.id;
    form.clearErrors();
    form.title = item.title;
    form.description = item.description || '';
    form.start_date = item.start_date ? item.start_date.substring(0, 10) : '';
    form.end_date = item.end_date ? item.end_date.substring(0, 10) : '';
    form.category = item.category || '';
    showFormModal.value = true;
};

const submit = () => {
    const options = {
        preserveScroll: true,
        onSuccess: () => {
            showFormModal.value = false;
            form.reset();
        },
    };

    if (isEditing.value) {
        form.put(`/admin/kalender/${editingId.value}`, options);
    } else {
        form.post('/admin/kalender', options);
    }
};

// --- Delete modal ---
const showDeleteModal = ref(false);
const itemToDelete = ref(null);
const deleteForm = useForm({});

const confirmDelete = (item) => {
    itemToDelete.value = item;
    showDeleteModal.value = true;
};

const deleteItem = () => {
    deleteForm.delete(`/admin/kalender/${itemToDelete.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteModal.value = false;
            itemToDelete.value = null;
        },
    });
};
</script>

<template>
    <AdminLayout title="Kalender Akademik">
        <div class="mb-6 flex justify-between items-center">
            <p class="text-slate-500 text-sm">Kelola agenda kegiatan, ujian, libur, dan jadwal penting sekolah.</p>
            <button
                @click="openCreate"
                class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Agenda
            </button>
        </div>

        <DataTable :columns="columns" :data="events">
            <template #cell-title="{ item }">
                <div class="font-bold text-slate-800">{{ item.title }}</div>
                <div v-if="item.description" class="text-xs text-slate-500 line-clamp-1 max-w-xs">{{ item.description }}</div>
            </template>

            <template #cell-schedule="{ item }">
                <div class="text-slate-700 font-medium whitespace-nowrap">{{ formatDate(item.start_date) }}</div>
                <div v-if="item.end_date && item.end_date !== item.start_date" class="text-xs text-slate-400 whitespace-nowrap">
                    s/d {{ formatDate(item.end_date) }}
                </div>
            </template>

            <template #cell-category="{ item }">
                <span
                    v-if="item.category"
                    :class="['px-2.5 py-1 text-xs font-semibold rounded-full border', categoryColors[item.category] || 'bg-slate-50 text-slate-600 border-slate-100']"
                >
                    {{ item.category }}
                </span>
                <span v-else class="text-slate-400 text-xs">—</span>
            </template>

            <template #actions="{ item }">
                <button @click="openEdit(item)" class="p-1.5 text-slate-400 hover:text-blue-600 transition-colors bg-slate-50 hover:bg-blue-50 rounded" title="Edit">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                    </svg>
                </button>
                <button @click="confirmDelete(item)" class="p-1.5 text-slate-400 hover:text-red-600 transition-colors bg-slate-50 hover:bg-red-50 rounded" title="Hapus">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </button>
            </template>
        </DataTable>

        <!-- Create/Edit Modal -->
        <Teleport to="body">
            <Transition
                enter-active-class="ease-out duration-300" enter-from-class="opacity-0" enter-to-class="opacity-100"
                leave-active-class="ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0"
            >
                <div v-show="showFormModal" class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
                    <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm" @click="showFormModal = false"></div>

                    <div class="relative bg-white rounded-xl shadow-xl w-full max-w-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                            <h2 class="font-bold text-slate-800">{{ isEditing ? 'Edit Agenda' : 'Tambah Agenda Baru' }}</h2>
                        </div>

                        <form @submit.prevent="submit" class="p-6 space-y-4">
                            <div>
                                <label for="title" class="block text-sm font-semibold text-slate-700 mb-1">Judul Agenda <span class="text-red-500">*</span></label>
                                <input id="title" v-model="form.title" type="text" required
                                    class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                                    placeholder="Contoh: Ujian Tengah Semester Ganjil">
                                <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">{{ form.errors.title }}</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="start_date" class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                                    <input id="start_date" v-model="form.start_date" type="date" required
                                        class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors">
                                    <p v-if="form.errors.start_date" class="mt-1 text-sm text-red-600">{{ form.errors.start_date }}</p>
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Selesai</label>
                                    <input id="end_date" v-model="form.end_date" type="date"
                                        class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors">
                                    <p class="text-xs text-slate-500 mt-1">Kosongkan jika satu hari.</p>
                                    <p v-if="form.errors.end_date" class="mt-1 text-sm text-red-600">{{ form.errors.end_date }}</p>
                                </div>
                            </div>

                            <div>
                                <label for="category" class="block text-sm font-semibold text-slate-700 mb-1">Kategori</label>
                                <select id="category" v-model="form.category"
                                    class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors">
                                    <option value="">— Pilih kategori —</option>
                                    <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
                                </select>
                                <p v-if="form.errors.category" class="mt-1 text-sm text-red-600">{{ form.errors.category }}</p>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi</label>
                                <textarea id="description" v-model="form.description" rows="3"
                                    class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                                    placeholder="Keterangan tambahan (opsional)"></textarea>
                                <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
                            </div>

                            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                                <button type="button" @click="showFormModal = false"
                                    class="px-4 py-2 bg-white text-slate-700 font-medium text-sm rounded-lg border border-slate-300 hover:bg-slate-50 transition-colors">
                                    Batal
                                </button>
                                <button type="submit" :disabled="form.processing"
                                    class="inline-flex justify-center items-center gap-2 rounded-lg bg-indigo-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition-colors">
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
            </Transition>
        </Teleport>

        <ConfirmModal
            :show="showDeleteModal"
            title="Hapus Agenda"
            :message="`Apakah Anda yakin ingin menghapus agenda '${itemToDelete?.title}'? Tindakan ini tidak dapat dibatalkan.`"
            @close="showDeleteModal = false"
            @confirm="deleteItem"
        />
    </AdminLayout>
</template>
