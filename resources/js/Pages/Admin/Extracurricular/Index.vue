<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataTable from '@/Components/Admin/DataTable.vue';
import ConfirmModal from '@/Components/Admin/ConfirmModal.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    extracurriculars: {
        type: Object,
        required: true,
    }
});

const columns = [
    { key: 'photo', label: 'Foto' },
    { key: 'name', label: 'Nama' },
    { key: 'category', label: 'Kategori' },
    { key: 'coach', label: 'Pembina' },
    { key: 'schedule', label: 'Jadwal' },
    { key: 'sort_order', label: 'Urutan' },
];

const showDeleteModal = ref(false);
const itemToDelete = ref(null);
const form = useForm({});

const confirmDelete = (item) => {
    itemToDelete.value = item;
    showDeleteModal.value = true;
};

const deleteItem = () => {
    form.delete(`/admin/ekstrakurikuler/${itemToDelete.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteModal.value = false;
            itemToDelete.value = null;
        }
    });
};
</script>

<template>
    <AdminLayout title="Manajemen Ekstrakurikuler">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <p class="text-slate-500 text-sm">Kelola kegiatan ekstrakurikuler sekolah.</p>
            </div>
            <Link href="/admin/ekstrakurikuler/create" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Data
            </Link>
        </div>

        <DataTable :columns="columns" :data="extracurriculars">
            <!-- Custom Cell: Photo -->
            <template #cell-photo="{ item }">
                <div class="w-14 h-14 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 shadow-sm">
                    <img :src="item.photo ? `/storage/${item.photo}` : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(item.name) + '&background=random'" class="w-full h-full object-cover" />
                </div>
            </template>

            <!-- Custom Cell: Name -->
            <template #cell-name="{ item }">
                <div class="font-bold text-slate-800">{{ item.name }}</div>
            </template>

            <!-- Custom Cell: Category -->
            <template #cell-category="{ item }">
                <span v-if="item.category" class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full border border-indigo-100">
                    {{ item.category }}
                </span>
                <span v-else class="text-slate-400 text-sm">-</span>
            </template>

            <!-- Custom Cell: Coach -->
            <template #cell-coach="{ item }">
                <span class="text-sm text-slate-600">{{ item.coach || '-' }}</span>
            </template>

            <!-- Custom Cell: Schedule -->
            <template #cell-schedule="{ item }">
                <span class="text-sm text-slate-600">
                    {{ [item.schedule_day, item.schedule_time].filter(Boolean).join(', ') || '-' }}
                </span>
            </template>

            <!-- Custom Cell: Order -->
            <template #cell-sort_order="{ item }">
                <div class="text-slate-600 font-medium text-center w-8">{{ item.sort_order }}</div>
            </template>

            <!-- Actions -->
            <template #actions="{ item }">
                <Link :href="`/admin/ekstrakurikuler/${item.id}/edit`" class="p-1.5 text-slate-400 hover:text-blue-600 transition-colors bg-slate-50 hover:bg-blue-50 rounded" title="Edit">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                    </svg>
                </Link>
                <button @click="confirmDelete(item)" class="p-1.5 text-slate-400 hover:text-red-600 transition-colors bg-slate-50 hover:bg-red-50 rounded" title="Hapus">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </button>
            </template>
        </DataTable>

        <ConfirmModal
            :show="showDeleteModal"
            title="Hapus Ekstrakurikuler"
            :message="`Apakah Anda yakin ingin menghapus '${itemToDelete?.name}'? Tindakan ini tidak dapat dibatalkan.`"
            @close="showDeleteModal = false"
            @confirm="deleteItem"
        />
    </AdminLayout>
</template>
