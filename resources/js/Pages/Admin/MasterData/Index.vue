<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    grouped: { type: Object, required: true },
    types:   { type: Object, required: true },
});

// ─── Active Tab ───────────────────────────────────────────────────────────────
const typeKeys     = Object.keys(props.types);
const activeType   = ref(typeKeys[0]);
const activeLabel  = computed(() => props.types[activeType.value]);
const activeItems  = computed(() => props.grouped[activeType.value] || []);

// ─── Add Form ────────────────────────────────────────────────────────────────
const showAddForm = ref(false);

const addForm = useForm({
    type:       activeType.value,
    name:       '',
    sort_order: '',
});

const submitAdd = () => {
    addForm.type = activeType.value;
    addForm.post('/admin/master-data', {
        preserveScroll: true,
        onSuccess: () => {
            addForm.reset('name', 'sort_order');
            showAddForm.value = false;
        },
    });
};

// ─── Edit Inline ─────────────────────────────────────────────────────────────
const editingId   = ref(null);
const editForm    = useForm({ name: '', sort_order: '', is_active: true });

const startEdit = (item) => {
    editingId.value       = item.id;
    editForm.name         = item.name;
    editForm.sort_order   = item.sort_order;
    editForm.is_active    = item.is_active;
};

const cancelEdit = () => {
    editingId.value = null;
    editForm.reset();
};

const submitEdit = (item) => {
    editForm.put(`/admin/master-data/${item.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            editingId.value = null;
            editForm.reset();
        },
    });
};

// ─── Delete ──────────────────────────────────────────────────────────────────
const confirmDelete = (item) => {
    if (!confirm(`Hapus "${item.name}"? Tindakan ini tidak dapat dibatalkan.`)) return;
    router.delete(`/admin/master-data/${item.id}`, { preserveScroll: true });
};

// ─── Tab change ──────────────────────────────────────────────────────────────
const switchTab = (type) => {
    activeType.value  = type;
    showAddForm.value = false;
    editingId.value   = null;
    addForm.reset('name', 'sort_order');
};

// ─── Icons per type ──────────────────────────────────────────────────────────
const typeIcons = {
    news_category:            'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15M9 11l3 3m0 0l3-3m-3 3V8',
    extracurricular_category: 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.562.562 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z',
    download_category:        'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4',
    calendar_category:        'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5',
};

const typeColors = {
    news_category:            'indigo',
    extracurricular_category: 'violet',
    download_category:        'sky',
    calendar_category:        'emerald',
};

const colorMap = {
    indigo:  { tab: 'bg-indigo-600 text-white',  badge: 'bg-indigo-100 text-indigo-700',  btn: 'bg-indigo-600 hover:bg-indigo-700' },
    violet:  { tab: 'bg-violet-600 text-white',  badge: 'bg-violet-100 text-violet-700',  btn: 'bg-violet-600 hover:bg-violet-700' },
    sky:     { tab: 'bg-sky-600 text-white',     badge: 'bg-sky-100 text-sky-700',        btn: 'bg-sky-600 hover:bg-sky-700' },
    emerald: { tab: 'bg-emerald-600 text-white', badge: 'bg-emerald-100 text-emerald-700',btn: 'bg-emerald-600 hover:bg-emerald-700' },
};

const activeColor  = computed(() => typeColors[activeType.value] || 'indigo');
const activeTheme  = computed(() => colorMap[activeColor.value]);
</script>

<template>
    <AdminLayout title="Master Data">
        <!-- Header -->
        <div class="mb-6">
            <p class="text-slate-500 text-sm">Kelola semua data referensi (kategori, jenis, tipe) yang digunakan di seluruh modul aplikasi.</p>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="border-b border-slate-200 overflow-x-auto">
                <div class="flex min-w-max">
                    <button
                        v-for="(label, type) in types"
                        :key="type"
                        @click="switchTab(type)"
                        :class="[
                            'flex items-center gap-2 px-5 py-4 text-sm font-semibold border-b-2 transition-all whitespace-nowrap',
                            activeType === type
                                ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50'
                                : 'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-50'
                        ]"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" :d="typeIcons[type]" />
                        </svg>
                        {{ label }}
                        <span class="ml-1 px-2 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                            {{ (grouped[type] || []).length }}
                        </span>
                    </button>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Section Header -->
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="font-bold text-slate-800 text-lg">{{ activeLabel }}</h2>
                        <p class="text-slate-500 text-sm mt-0.5">{{ activeItems.length }} item terdaftar</p>
                    </div>
                    <button
                        @click="showAddForm = !showAddForm"
                        :class="[
                            'inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white transition-all shadow-sm',
                            showAddForm ? 'bg-slate-500 hover:bg-slate-600' : 'bg-indigo-600 hover:bg-indigo-700'
                        ]"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" :d="showAddForm ? 'M6 18L18 6M6 6l12 12' : 'M12 4.5v15m7.5-7.5h-15'" />
                        </svg>
                        {{ showAddForm ? 'Batal' : 'Tambah Data' }}
                    </button>
                </div>

                <!-- Add Form -->
                <transition
                    enter-active-class="transition-all duration-200 ease-out"
                    enter-from-class="opacity-0 -translate-y-2"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition-all duration-150 ease-in"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 -translate-y-2"
                >
                    <form v-if="showAddForm" @submit.prevent="submitAdd" class="mb-5 p-4 bg-indigo-50 border border-indigo-200 rounded-xl">
                        <p class="text-sm font-semibold text-indigo-800 mb-3">Tambah item baru ke <span class="font-bold">{{ activeLabel }}</span></p>
                        <div class="flex gap-3 flex-wrap">
                            <div class="flex-grow min-w-48">
                                <input
                                    v-model="addForm.name"
                                    type="text"
                                    placeholder="Nama item..."
                                    required
                                    class="w-full rounded-lg border-indigo-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm"
                                >
                                <p v-if="addForm.errors.name" class="mt-1 text-xs text-red-600">{{ addForm.errors.name }}</p>
                            </div>
                            <div class="w-32">
                                <input
                                    v-model="addForm.sort_order"
                                    type="number"
                                    min="0"
                                    placeholder="Urutan"
                                    class="w-full rounded-lg border-indigo-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm"
                                >
                            </div>
                            <button
                                type="submit"
                                :disabled="addForm.processing"
                                class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors disabled:opacity-50"
                            >
                                {{ addForm.processing ? 'Menyimpan...' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </transition>

                <!-- Data Table -->
                <div v-if="activeItems.length > 0" class="rounded-xl border border-slate-200 overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide w-12">#</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Nama</th>
                                <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide w-28">Urutan</th>
                                <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide w-24">Status</th>
                                <th class="text-right px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="item in activeItems"
                                :key="item.id"
                                :class="['transition-colors', editingId === item.id ? 'bg-amber-50' : 'hover:bg-slate-50']"
                            >
                                <!-- View mode -->
                                <template v-if="editingId !== item.id">
                                    <td class="px-4 py-3 text-slate-400 text-xs">{{ item.id }}</td>
                                    <td class="px-4 py-3 font-medium text-slate-800">{{ item.name }}</td>
                                    <td class="px-4 py-3 text-center text-slate-500">{{ item.sort_order }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span :class="[
                                            'inline-block px-2 py-0.5 rounded-full text-xs font-semibold',
                                            item.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500'
                                        ]">
                                            {{ item.is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <button @click="startEdit(item)" class="p-1.5 rounded-lg text-slate-400 hover:bg-amber-100 hover:text-amber-600 transition-colors" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                </svg>
                                            </button>
                                            <button @click="confirmDelete(item)" class="p-1.5 rounded-lg text-slate-400 hover:bg-red-100 hover:text-red-600 transition-colors" title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </template>

                                <!-- Edit mode (inline) -->
                                <template v-else>
                                    <td class="px-4 py-2 text-slate-400 text-xs">{{ item.id }}</td>
                                    <td class="px-4 py-2">
                                        <input
                                            v-model="editForm.name"
                                            type="text"
                                            required
                                            class="w-full rounded-lg border-amber-300 focus:border-amber-500 focus:ring-amber-500 shadow-sm text-sm py-1.5"
                                        >
                                        <p v-if="editForm.errors.name" class="mt-1 text-xs text-red-600">{{ editForm.errors.name }}</p>
                                    </td>
                                    <td class="px-4 py-2">
                                        <input
                                            v-model="editForm.sort_order"
                                            type="number"
                                            min="0"
                                            class="w-full rounded-lg border-amber-300 focus:border-amber-500 focus:ring-amber-500 shadow-sm text-sm py-1.5 text-center"
                                        >
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <label class="inline-flex items-center gap-1 cursor-pointer">
                                            <input type="checkbox" v-model="editForm.is_active" class="rounded border-slate-300 text-indigo-600">
                                            <span class="text-xs text-slate-600">Aktif</span>
                                        </label>
                                    </td>
                                    <td class="px-4 py-2 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <button @click="submitEdit(item)" :disabled="editForm.processing" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-lg transition-colors disabled:opacity-50">
                                                Simpan
                                            </button>
                                            <button @click="cancelEdit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-lg transition-colors">
                                                Batal
                                            </button>
                                        </div>
                                    </td>
                                </template>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-16 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-slate-300 mx-auto mb-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 8.485-7.5 11.25-7.5 11.25S5.25 14.86 5.25 6.375a7.5 7.5 0 0115 0z" />
                    </svg>
                    <p class="text-slate-500 font-medium">Belum ada data untuk <strong>{{ activeLabel }}</strong>.</p>
                    <p class="text-slate-400 text-sm mt-1">Klik tombol "Tambah Data" di atas untuk mulai mengisi.</p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
