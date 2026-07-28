<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataTable from '@/Components/Admin/DataTable.vue';
import ConfirmModal from '@/Components/Admin/ConfirmModal.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { format, parseISO } from 'date-fns';
import { id } from 'date-fns/locale';

const props = defineProps({
    settings: {
        type: Object,
        required: true,
    }
});

const columns = [
    { key: 'tahun_ajaran', label: 'Tahun Ajaran' },
    { key: 'jalur', label: 'Jalur' },
    { key: 'jadwal', label: 'Jadwal Pendaftaran' },
    { key: 'kuota', label: 'Kuota' },
    { key: 'is_active', label: 'Status' },
];

const showDeleteModal = ref(false);
const showAddModal = ref(false);
const itemToDelete = ref(null);
const isEditing = ref(false);
const editId = ref(null);

const form = useForm({
    tahun_ajaran: '',
    jalur: '',
    jadwal_buka: '',
    jadwal_tutup: '',
    kuota: 0,
    is_active: true,
});

const deleteForm = useForm({});

const openAddModal = () => {
    isEditing.value = false;
    form.reset();
    showAddModal.value = true;
};

const openEditModal = (item) => {
    isEditing.value = true;
    editId.value = item.id;
    form.tahun_ajaran = item.tahun_ajaran;
    form.jalur = item.jalur;
    form.jadwal_buka = item.jadwal_buka.substring(0, 10); // get YYYY-MM-DD
    form.jadwal_tutup = item.jadwal_tutup.substring(0, 10);
    form.kuota = item.kuota;
    form.is_active = item.is_active;
    showAddModal.value = true;
};

const submitForm = () => {
    if (isEditing.value) {
        form.put(`/admin/ppdb-setting/${editId.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                showAddModal.value = false;
            }
        });
    } else {
        form.post('/admin/ppdb-setting', {
            preserveScroll: true,
            onSuccess: () => {
                showAddModal.value = false;
                form.reset();
            }
        });
    }
};

const confirmDelete = (item) => {
    itemToDelete.value = item;
    showDeleteModal.value = true;
};

const deleteItem = () => {
    deleteForm.delete(`/admin/ppdb-setting/${itemToDelete.value.id}`, {
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
    <AdminLayout title="Pengaturan Gelombang PPDB">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <p class="text-slate-500 text-sm">Kelola jalur dan jadwal penerimaan siswa baru.</p>
            </div>
            <button @click="openAddModal" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Jalur
            </button>
        </div>

        <DataTable :columns="columns" :data="settings">
            <!-- Custom Cell: Tahun Ajaran -->
            <template #cell-tahun_ajaran="{ item }">
                <div class="font-bold text-slate-800">{{ item.tahun_ajaran }}</div>
            </template>

            <!-- Custom Cell: Jalur -->
            <template #cell-jalur="{ item }">
                <div class="font-medium text-indigo-700">{{ item.jalur }}</div>
            </template>

            <!-- Custom Cell: Jadwal -->
            <template #cell-jadwal="{ item }">
                <div class="text-sm">
                    {{ formatDate(item.jadwal_buka) }} <span class="text-slate-400 mx-1">-</span> {{ formatDate(item.jadwal_tutup) }}
                </div>
            </template>

            <!-- Custom Cell: Kuota -->
            <template #cell-kuota="{ item }">
                <div class="text-center w-12 font-medium bg-slate-100 rounded text-slate-700 py-1">{{ item.kuota }}</div>
            </template>

            <!-- Custom Cell: Status -->
            <template #cell-is_active="{ item }">
                <span v-if="item.is_active" class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full border border-green-200 flex items-center gap-1 w-fit">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                </span>
                <span v-else class="px-2.5 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-full border border-slate-200">
                    Nonaktif
                </span>
            </template>

            <!-- Actions -->
            <template #actions="{ item }">
                <button @click="openEditModal(item)" class="p-1.5 text-slate-400 hover:text-blue-600 transition-colors bg-slate-50 hover:bg-blue-50 rounded" title="Edit">
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

        <!-- Form Modal -->
        <Teleport to="body">
            <div v-if="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showAddModal = false"></div>
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg relative z-10 overflow-hidden flex flex-col max-h-[90vh]">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                        <h3 class="font-bold text-slate-800 text-lg">{{ isEditing ? 'Edit Jalur PPDB' : 'Tambah Jalur PPDB' }}</h3>
                        <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <form @submit.prevent="submitForm" class="overflow-y-auto p-6 space-y-5">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Tahun Ajaran <span class="text-red-500">*</span></label>
                                <input v-model="form.tahun_ajaran" type="text" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm" placeholder="Contoh: 2024/2025">
                                <p v-if="form.errors.tahun_ajaran" class="mt-1 text-sm text-red-600">{{ form.errors.tahun_ajaran }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Jalur <span class="text-red-500">*</span></label>
                                <input v-model="form.jalur" type="text" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm" placeholder="Contoh: Zonasi, Prestasi">
                                <p v-if="form.errors.jalur" class="mt-1 text-sm text-red-600">{{ form.errors.jalur }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Jadwal Buka <span class="text-red-500">*</span></label>
                                <input v-model="form.jadwal_buka" type="date" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm">
                                <p v-if="form.errors.jadwal_buka" class="mt-1 text-sm text-red-600">{{ form.errors.jadwal_buka }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Jadwal Tutup <span class="text-red-500">*</span></label>
                                <input v-model="form.jadwal_tutup" type="date" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm">
                                <p v-if="form.errors.jadwal_tutup" class="mt-1 text-sm text-red-600">{{ form.errors.jadwal_tutup }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Kuota <span class="text-red-500">*</span></label>
                                <input v-model.number="form.kuota" type="number" min="1" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm">
                                <p v-if="form.errors.kuota" class="mt-1 text-sm text-red-600">{{ form.errors.kuota }}</p>
                            </div>
                            
                            <div class="flex items-center pt-6">
                                <label class="flex items-center cursor-pointer">
                                    <div class="relative">
                                        <input type="checkbox" v-model="form.is_active" class="sr-only">
                                        <div :class="['block w-10 h-6 rounded-full transition-colors', form.is_active ? 'bg-indigo-600' : 'bg-slate-300']"></div>
                                        <div :class="['dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform', form.is_active ? 'transform translate-x-4' : '']"></div>
                                    </div>
                                    <div class="ml-3 font-medium text-sm text-slate-700">Status Aktif</div>
                                </label>
                            </div>
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
            title="Hapus Jalur PPDB"
            :message="`Apakah Anda yakin ingin menghapus jalur '${itemToDelete?.jalur}'? Tindakan ini tidak dapat dibatalkan.`"
            @close="showDeleteModal = false"
            @confirm="deleteItem"
        />
    </AdminLayout>
</template>
