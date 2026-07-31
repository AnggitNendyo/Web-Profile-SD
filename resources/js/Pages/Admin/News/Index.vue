<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataTable from '@/Components/Admin/DataTable.vue';
import ConfirmModal from '@/Components/Admin/ConfirmModal.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { format, parseISO } from 'date-fns';
import { id } from 'date-fns/locale';

const props = defineProps({
    news: {
        type: Object,
        required: true,
    }
});

const columns = [
    { key: 'thumbnail', label: 'Gambar' },
    { key: 'title', label: 'Judul Berita' },
    { key: 'category', label: 'Kategori' },
    { key: 'status', label: 'Status' },
    { key: 'published_at', label: 'Tgl Publikasi' },
];

const showDeleteModal = ref(false);
const itemToDelete = ref(null);
const form = useForm({});

const confirmDelete = (item) => {
    itemToDelete.value = item;
    showDeleteModal.value = true;
};

const deleteItem = () => {
    form.delete(`/admin/berita/${itemToDelete.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteModal.value = false;
            itemToDelete.value = null;
        }
    });
};

const toggleStatus = (item) => {
    form.patch(`/admin/berita/${item.id}/toggle-status`, {
        preserveScroll: true,
    });
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return format(parseISO(dateString), 'dd MMM yyyy, HH:mm', { locale: id });
};
</script>

<template>
    <AdminLayout title="Manajemen Berita">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <p class="text-slate-500 text-sm">Kelola berita, artikel, dan pengumuman sekolah.</p>
            </div>
            <Link href="/admin/berita/create" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tulis Berita
            </Link>
        </div>

        <DataTable :columns="columns" :data="news">
            <!-- Custom Cell: Thumbnail -->
            <template #cell-thumbnail="{ item }">
                <div class="w-16 h-12 bg-slate-100 rounded overflow-hidden">
                    <img v-if="item.thumbnail" :src="`/storage/${item.thumbnail}`" class="w-full h-full object-cover" />
                    <div v-else class="w-full h-full flex items-center justify-center text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                    </div>
                </div>
            </template>

            <!-- Custom Cell: Title -->
            <template #cell-title="{ item }">
                <div class="font-medium text-slate-800 line-clamp-2 max-w-xs" :title="item.title">{{ item.title }}</div>
                <div class="text-xs text-slate-500 mt-1">Oleh: {{ item.author?.name || 'Sistem' }}</div>
            </template>

            <!-- Custom Cell: Status -->
            <template #cell-status="{ item }">
                <button 
                    @click="toggleStatus(item)"
                    :disabled="form.processing"
                    :class="[
                        'px-2.5 py-1 text-xs font-semibold rounded-full transition-colors flex items-center gap-1.5 border focus:outline-none focus:ring-2 focus:ring-offset-1 disabled:opacity-50',
                        item.published_at && new Date(item.published_at) <= new Date() 
                            ? 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100 focus:ring-green-500' 
                            : 'bg-yellow-50 text-yellow-700 border-yellow-200 hover:bg-yellow-100 focus:ring-yellow-500'
                    ]"
                    :title="item.published_at && new Date(item.published_at) <= new Date() ? 'Ubah ke Draft' : 'Ubah ke Publikasi'"
                >
                    <div :class="[
                        'w-2 h-2 rounded-full',
                        item.published_at && new Date(item.published_at) <= new Date() ? 'bg-green-500' : 'bg-yellow-500'
                    ]"></div>
                    <span v-if="item.published_at && new Date(item.published_at) <= new Date()">Publikasi</span>
                    <span v-else>Draft</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3 opacity-60 ml-0.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                </button>
            </template>

            <!-- Custom Cell: Published At -->
            <template #cell-published_at="{ item }">
                <span class="text-sm">{{ formatDate(item.published_at) }}</span>
            </template>

            <!-- Actions -->
            <template #actions="{ item }">
                <a :href="`/berita/${item.slug}`" target="_blank" class="p-1.5 text-slate-400 hover:text-indigo-600 transition-colors bg-slate-50 hover:bg-indigo-50 rounded" title="Lihat">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </a>
                <Link :href="`/admin/berita/${item.id}/edit`" class="p-1.5 text-slate-400 hover:text-blue-600 transition-colors bg-slate-50 hover:bg-blue-50 rounded" title="Edit">
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
            title="Hapus Berita"
            :message="`Apakah Anda yakin ingin menghapus berita '${itemToDelete?.title}'? Tindakan ini tidak dapat dibatalkan.`"
            @close="showDeleteModal = false"
            @confirm="deleteItem"
        />
    </AdminLayout>
</template>
