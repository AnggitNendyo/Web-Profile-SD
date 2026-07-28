<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataTable from '@/Components/Admin/DataTable.vue';
import { Link } from '@inertiajs/vue3';
import { format, parseISO } from 'date-fns';
import { id } from 'date-fns/locale';

const props = defineProps({
    pages: {
        type: Array,
        required: true,
    }
});

const columns = [
    { key: 'title', label: 'Judul Halaman' },
    { key: 'slug', label: 'Slug / URL' },
    { key: 'updated_at', label: 'Terakhir Diubah' },
];

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return format(parseISO(dateString), 'dd MMM yyyy, HH:mm', { locale: id });
};
</script>

<template>
    <AdminLayout title="Manajemen Halaman Statis">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <p class="text-slate-500 text-sm">Kelola konten halaman statis seperti Profil, Visi Misi, dll.</p>
                <p class="text-xs text-orange-500 mt-1 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Halaman ini dikunci oleh sistem dan tidak dapat dihapus, hanya dapat diubah kontennya.
                </p>
            </div>
        </div>

        <DataTable :columns="columns" :data="{ data: pages, links: [] }" :pagination="false">
            <!-- Custom Cell: Title -->
            <template #cell-title="{ item }">
                <div class="font-bold text-slate-800">{{ item.title }}</div>
            </template>

            <!-- Custom Cell: Slug -->
            <template #cell-slug="{ item }">
                <div class="font-mono text-sm text-indigo-600 bg-indigo-50 px-2 py-1 rounded inline-block">{{ item.slug }}</div>
            </template>

            <!-- Custom Cell: Updated At -->
            <template #cell-updated_at="{ item }">
                <span class="text-sm">{{ formatDate(item.updated_at) }}</span>
            </template>

            <!-- Actions -->
            <template #actions="{ item }">
                <Link :href="`/admin/halaman/${item.slug}/edit`" class="p-1.5 text-slate-400 hover:text-blue-600 transition-colors bg-slate-50 hover:bg-blue-50 rounded" title="Edit Konten">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                    </svg>
                </Link>
            </template>
        </DataTable>
    </AdminLayout>
</template>
