<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';
import { format, parseISO } from 'date-fns';
import { id } from 'date-fns/locale';

const props = defineProps({
    stats: {
        type: Object,
        required: true,
    },
    latestNews: {
        type: Array,
        required: true,
    },
    latestRegistrations: {
        type: Array,
        required: true,
    },
    latestMessages: {
        type: Array,
        default: () => [],
    },
});

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return format(parseISO(dateString), 'dd MMM yyyy, HH:mm', { locale: id });
};
</script>

<template>
    <AdminLayout title="Dashboard">
        <!-- Stats Widgets -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <!-- Widget 1 -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Total Berita</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ stats.totalNews }}</h3>
                    <p class="text-xs text-green-600 mt-2 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                            <path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd" />
                        </svg>
                        {{ stats.publishedNews }} Dipublikasi
                    </p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                    </svg>
                </div>
            </div>

            <!-- Widget 2 -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Guru & Staf</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ stats.totalTeachers }}</h3>
                    <p class="text-xs text-slate-400 mt-2">Pegawai Aktif</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                    </svg>
                </div>
            </div>

            <!-- Widget 3 -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Item Galeri</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ stats.totalGalleries }}</h3>
                    <p class="text-xs text-slate-400 mt-2">Foto & Video</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center text-purple-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
            </div>

            <!-- Widget 4: PPDB -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Pendaftar PPDB</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ stats.totalPpdb }}</h3>
                    <p class="text-xs text-orange-600 mt-2 flex items-center gap-1 font-medium">
                        {{ stats.pendingPpdb }} Menunggu Verifikasi
                    </p>
                </div>
                <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-orange-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
            </div>

            <!-- Widget 5: Messages -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Pesan Masuk</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ stats.totalMessages }}</h3>
                    <p v-if="stats.unreadMessages > 0" class="text-xs text-indigo-600 mt-2 flex items-center gap-1 font-medium">
                        <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
                        {{ stats.unreadMessages }} Belum Dibaca
                    </p>
                    <p v-else class="text-xs text-slate-400 mt-2">Semua sudah dibaca</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Latest News -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                    <h3 class="font-bold text-slate-800">Berita Terbaru</h3>
                    <Link href="/admin/berita" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Lihat Semua</Link>
                </div>
                <div class="flex-grow p-0">
                    <ul class="divide-y divide-slate-100">
                        <li v-for="item in latestNews" :key="item.id" class="p-4 hover:bg-slate-50 transition-colors flex items-start gap-4">
                            <div class="w-16 h-16 bg-slate-200 rounded-lg overflow-hidden shrink-0">
                                <img :src="item.thumbnail ? `/storage/${item.thumbnail}` : 'https://images.unsplash.com/photo-1546410531-bb4caa6b424d?q=80&w=200&auto=format&fit=crop'" class="w-full h-full object-cover" />
                            </div>
                            <div>
                                <h4 class="font-semibold text-slate-800 text-sm mb-1 line-clamp-1">{{ item.title }}</h4>
                                <div class="flex items-center gap-3 text-xs text-slate-500">
                                    <span>{{ formatDate(item.published_at || item.created_at) }}</span>
                                    <span v-if="item.category" class="px-2 py-0.5 bg-slate-100 rounded text-slate-600">{{ item.category }}</span>
                                </div>
                            </div>
                        </li>
                        <li v-if="latestNews.length === 0" class="p-8 text-center text-slate-500 text-sm">
                            Belum ada berita.
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Latest PPDB -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                    <h3 class="font-bold text-slate-800">Pendaftaran PPDB Masuk</h3>
                    <Link href="/admin/ppdb" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Lihat Semua</Link>
                </div>
                <div class="flex-grow p-0">
                    <ul class="divide-y divide-slate-100">
                        <li v-for="reg in latestRegistrations" :key="reg.id" class="p-4 hover:bg-slate-50 transition-colors flex items-center justify-between">
                            <div>
                                <h4 class="font-semibold text-slate-800 text-sm mb-1">{{ reg.nama_siswa }}</h4>
                                <p class="text-xs text-slate-500">{{ formatDate(reg.created_at) }}</p>
                            </div>
                            <div>
                                <span :class="[
                                    'px-2.5 py-1 text-xs font-semibold rounded-full',
                                    reg.status === 'pending' ? 'bg-orange-100 text-orange-700' : 
                                    reg.status === 'verified' ? 'bg-green-100 text-green-700' : 
                                    reg.status === 'accepted' ? 'bg-blue-100 text-blue-700' :
                                    'bg-red-100 text-red-700'
                                ]">
                                    {{ reg.status.charAt(0).toUpperCase() + reg.status.slice(1) }}
                                </span>
                            </div>
                        </li>
                        <li v-if="latestRegistrations.length === 0" class="p-8 text-center text-slate-500 text-sm">
                            Belum ada pendaftaran.
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Latest Contact Messages -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        Pesan Kontak
                        <span v-if="stats.unreadMessages > 0" class="w-5 h-5 bg-indigo-500 text-white text-xs rounded-full flex items-center justify-center font-bold animate-pulse">
                            {{ stats.unreadMessages }}
                        </span>
                    </h3>
                    <Link href="/admin/pesan" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Lihat Semua</Link>
                </div>
                <div class="flex-grow p-0">
                    <ul class="divide-y divide-slate-100">
                        <li v-for="msg in latestMessages" :key="msg.id" class="hover:bg-slate-50 transition-colors">
                            <Link :href="`/admin/pesan/${msg.id}`" class="p-4 flex items-start gap-3 w-full">
                                <div class="mt-1 shrink-0">
                                    <span v-if="!msg.is_read" class="w-2.5 h-2.5 bg-indigo-500 rounded-full block"></span>
                                    <span v-else class="w-2.5 h-2.5 bg-slate-200 rounded-full block"></span>
                                </div>
                                <div class="min-w-0 flex-grow">
                                    <div class="flex items-center justify-between gap-2">
                                        <h4 :class="['text-sm truncate', msg.is_read ? 'text-slate-600' : 'font-bold text-slate-800']">
                                            {{ msg.name }}
                                        </h4>
                                        <span class="text-xs text-slate-400 shrink-0">{{ formatDate(msg.created_at).split(',')[0] }}</span>
                                    </div>
                                    <p :class="['text-xs truncate', msg.is_read ? 'text-slate-500' : 'font-medium text-slate-700']">{{ msg.subject }}</p>
                                    <p class="text-xs text-slate-400 truncate mt-0.5">{{ msg.message }}</p>
                                </div>
                            </Link>
                        </li>
                        <li v-if="latestMessages.length === 0" class="p-8 text-center text-slate-500 text-sm">
                            Belum ada pesan masuk.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
