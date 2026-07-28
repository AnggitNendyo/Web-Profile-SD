<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataTable from '@/Components/Admin/DataTable.vue';
import ConfirmModal from '@/Components/Admin/ConfirmModal.vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { format, parseISO } from 'date-fns';
import { id } from 'date-fns/locale';

const props = defineProps({
    messages: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
    stats: { type: Object, required: true },
});

const columns = [
    { key: 'is_read', label: '' },
    { key: 'name', label: 'Pengirim' },
    { key: 'subject', label: 'Subjek' },
    { key: 'created_at', label: 'Tanggal' },
];

const showDeleteModal = ref(false);
const itemToDelete = ref(null);
const form = useForm({});

const confirmDelete = (item) => {
    itemToDelete.value = item;
    showDeleteModal.value = true;
};

const deleteItem = () => {
    form.delete(`/admin/pesan/${itemToDelete.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteModal.value = false;
            itemToDelete.value = null;
        },
    });
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return format(parseISO(dateString), 'dd MMM yyyy, HH:mm', { locale: id });
};

const filterMessages = (filter) => {
    router.get('/admin/pesan', filter ? { filter } : {}, { preserveState: true, preserveScroll: true });
};
</script>

<template>
    <AdminLayout title="Pesan Masuk">
        <!-- Header -->
        <div class="mb-6">
            <p class="text-slate-500 text-sm">Kelola pesan yang masuk dari formulir kontak website.</p>
        </div>

        <!-- Stats Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <button
                @click="filterMessages(null)"
                :class="[
                    'bg-white rounded-xl shadow-sm border p-4 flex items-center justify-between text-left transition-all hover:shadow-md',
                    !filters.filter ? 'border-indigo-300 ring-2 ring-indigo-100' : 'border-slate-200'
                ]"
            >
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Pesan</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ stats.total }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>
            </button>
            <button
                @click="filterMessages('unread')"
                :class="[
                    'bg-white rounded-xl shadow-sm border p-4 flex items-center justify-between text-left transition-all hover:shadow-md',
                    filters.filter === 'unread' ? 'border-orange-300 ring-2 ring-orange-100' : 'border-slate-200'
                ]"
            >
                <div>
                    <p class="text-sm font-medium text-slate-500">Belum Dibaca</p>
                    <h3 class="text-2xl font-bold text-orange-600">{{ stats.unread }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-orange-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                </div>
            </button>
        </div>

        <!-- Table -->
        <DataTable :columns="columns" :data="messages">
            <!-- Read/Unread indicator -->
            <template #cell-is_read="{ item }">
                <div class="flex justify-center">
                    <span v-if="!item.is_read" class="w-2.5 h-2.5 bg-indigo-500 rounded-full" title="Belum dibaca"></span>
                    <span v-else class="w-2.5 h-2.5 bg-slate-200 rounded-full" title="Sudah dibaca"></span>
                </div>
            </template>

            <!-- Sender -->
            <template #cell-name="{ item }">
                <div :class="item.is_read ? '' : 'font-bold'">
                    <p class="text-slate-800">{{ item.name }}</p>
                    <p class="text-xs text-slate-500">{{ item.email }}</p>
                </div>
            </template>

            <!-- Subject -->
            <template #cell-subject="{ item }">
                <p :class="['text-sm', item.is_read ? 'text-slate-600' : 'font-semibold text-slate-800']">{{ item.subject }}</p>
                <p class="text-xs text-slate-400 line-clamp-1 mt-0.5">{{ item.message }}</p>
            </template>

            <!-- Date -->
            <template #cell-created_at="{ item }">
                <span class="text-sm text-slate-500">{{ formatDate(item.created_at) }}</span>
            </template>

            <!-- Actions -->
            <template #actions="{ item }">
                <Link :href="`/admin/pesan/${item.id}`" class="p-1.5 text-slate-400 hover:text-indigo-600 transition-colors bg-slate-50 hover:bg-indigo-50 rounded flex items-center gap-1" title="Lihat Detail">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-xs font-medium pr-1">Lihat</span>
                </Link>
                <button @click="confirmDelete(item)" class="p-1.5 text-slate-400 hover:text-red-600 transition-colors bg-slate-50 hover:bg-red-50 rounded" title="Hapus Pesan">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </button>
            </template>
        </DataTable>

        <ConfirmModal
            :show="showDeleteModal"
            title="Hapus Pesan"
            :message="`Apakah Anda yakin ingin menghapus pesan dari '${itemToDelete?.name}'? Tindakan ini tidak dapat dibatalkan.`"
            @close="showDeleteModal = false"
            @confirm="deleteItem"
        />
    </AdminLayout>
</template>
