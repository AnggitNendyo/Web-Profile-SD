<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataTable from '@/Components/Admin/DataTable.vue';
import ConfirmModal from '@/Components/Admin/ConfirmModal.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { format, parseISO } from 'date-fns';
import { id } from 'date-fns/locale';

const props = defineProps({
    ppdbs: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

// Export mengikuti filter status yang sedang aktif.
const exportUrl = computed(() => {
    const params = new URLSearchParams();
    if (props.filters.status) params.set('status', props.filters.status);
    const qs = params.toString();
    return '/admin/ppdb/export' + (qs ? `?${qs}` : '');
});

const columns = [
    { key: 'no_pendaftaran', label: 'No. Daftar' },
    { key: 'nama_siswa', label: 'Nama Siswa' },
    { key: 'no_hp', label: 'No HP Ortu' },
    { key: 'status', label: 'Status' },
    { key: 'created_at', label: 'Tgl Daftar' },
];

const showDeleteModal = ref(false);
const itemToDelete = ref(null);
const form = useForm({});

const confirmDelete = (item) => {
    itemToDelete.value = item;
    showDeleteModal.value = true;
};

const deleteItem = () => {
    form.delete(`/admin/ppdb/${itemToDelete.value.id}`, {
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
    <AdminLayout title="Data Pendaftar PPDB">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <p class="text-slate-500 text-sm">Kelola data calon siswa baru yang mendaftar secara online.</p>
            </div>
            <!-- #4 Export CSV (mengikuti filter status aktif) -->
            <a :href="exportUrl" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Export CSV
            </a>
        </div>

        <DataTable :columns="columns" :data="ppdbs">
            <!-- Custom Cell: No Pendaftaran -->
            <template #cell-no_pendaftaran="{ item }">
                <div class="font-mono text-sm font-semibold text-indigo-600">{{ item.no_pendaftaran }}</div>
            </template>

            <!-- Custom Cell: Nama Siswa -->
            <template #cell-nama_siswa="{ item }">
                <div class="font-medium text-slate-800">{{ item.nama_siswa }}</div>
                <div class="text-xs text-slate-500">Ortu: {{ item.nama_ortu }}</div>
            </template>

            <!-- Custom Cell: Status -->
            <template #cell-status="{ item }">
                <span v-if="item.status === 'pending'" class="px-2.5 py-1 bg-orange-100 text-orange-700 text-xs font-semibold rounded-full border border-orange-200">Menunggu</span>
                <span v-else-if="item.status === 'verified'" class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full border border-green-200">Terverifikasi</span>
                <span v-else-if="item.status === 'rejected'" class="px-2.5 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full border border-red-200">Ditolak</span>
                <span v-else-if="item.status === 'accepted'" class="px-2.5 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full border border-blue-200">Diterima</span>
            </template>

            <!-- Custom Cell: Created At -->
            <template #cell-created_at="{ item }">
                <span class="text-sm">{{ formatDate(item.created_at) }}</span>
            </template>

            <!-- Actions -->
            <template #actions="{ item }">
                <Link :href="`/admin/ppdb/${item.id}`" class="p-1.5 text-slate-400 hover:text-indigo-600 transition-colors bg-slate-50 hover:bg-indigo-50 rounded flex items-center gap-1" title="Detail & Verifikasi">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    <span class="text-xs font-medium pr-1">Detail</span>
                </Link>
                <button @click="confirmDelete(item)" class="p-1.5 text-slate-400 hover:text-red-600 transition-colors bg-slate-50 hover:bg-red-50 rounded" title="Hapus Data">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </button>
            </template>
        </DataTable>

        <ConfirmModal 
            :show="showDeleteModal"
            title="Hapus Data PPDB"
            :message="`Apakah Anda yakin ingin menghapus data pendaftaran '${itemToDelete?.nama_siswa}'? Tindakan ini tidak dapat dibatalkan.`"
            @close="showDeleteModal = false"
            @confirm="deleteItem"
        />
    </AdminLayout>
</template>
