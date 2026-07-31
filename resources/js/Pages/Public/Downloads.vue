<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    downloads: {
        type: Object,
        required: true,
    },
    categories: {
        type: Array,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    }
});

const category = ref(props.filters.category || '');

const applyFilter = (cat) => {
    category.value = cat === category.value ? '' : cat;
    router.get('/unduhan', {
        category: category.value,
    }, { preserveState: true, replace: true });
};

const getFileIcon = (path) => {
    if (!path) return 'document';
    const ext = path.split('.').pop().toLowerCase();
    
    if (['pdf'].includes(ext)) return 'pdf';
    if (['doc', 'docx'].includes(ext)) return 'word';
    if (['xls', 'xlsx'].includes(ext)) return 'excel';
    if (['ppt', 'pptx'].includes(ext)) return 'powerpoint';
    if (['zip', 'rar'].includes(ext)) return 'archive';
    return 'document';
};
</script>

<template>
    <PublicLayout title="Unduhan Dokumen - SD Negeri">
        <section class="bg-indigo-900 pt-32 pb-20 relative overflow-hidden">
            <div v-if="$page.props.schoolSettings?.banner_downloads" class="absolute inset-0 z-0">
                <img :src="`/storage/${$page.props.schoolSettings.banner_downloads}`" alt="Banner" class="w-full h-full object-cover opacity-20 mix-blend-overlay" />
            </div>
            <div v-else class="absolute inset-0 z-0">
                <div class="absolute w-full h-full bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCI+CjxjaXJjbGUgY3g9IjIiIGN5PSIyIiByPSIyIiBmaWxsPSIjZmZmZmZmIi8+Cjwvc3ZnPg==')] opacity-10"></div>
            </div>
            <div class="container mx-auto px-4 md:px-6 relative z-10 text-center">
                <h1 class="font-display text-4xl md:text-5xl font-semibold text-white mb-6">Unduhan Dokumen</h1>
                <p class="text-indigo-100 text-lg max-w-2xl mx-auto">
                    Pusat unduhan berbagai dokumen resmi, materi pembelajaran, dan informasi penting lainnya.
                </p>
            </div>
        </section>

        <section class="py-12 bg-slate-50 min-h-[50vh]">
            <div class="container mx-auto px-4 md:px-6">
                
                <div class="max-w-5xl mx-auto">
                    <!-- Filters -->
                    <div class="flex flex-wrap gap-2 justify-center mb-10">
                        <button 
                            @click="applyFilter('')"
                            :class="['px-5 py-2 rounded-full text-sm font-medium transition-colors border shadow-sm', category === '' ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-indigo-300 hover:text-indigo-600']"
                        >
                            Semua Dokumen
                        </button>
                        <button 
                            v-for="cat in categories" 
                            :key="cat"
                            @click="applyFilter(cat)"
                            :class="['px-5 py-2 rounded-full text-sm font-medium transition-colors border shadow-sm', category === cat ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-indigo-300 hover:text-indigo-600']"
                        >
                            {{ cat }}
                        </button>
                    </div>

                    <!-- List -->
                    <div v-if="downloads.data.length > 0" class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <ul class="divide-y divide-slate-100">
                            <li v-for="item in downloads.data" :key="item.id" class="p-4 sm:p-6 hover:bg-slate-50 transition-colors flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center shrink-0">
                                    <!-- PDF Icon -->
                                    <svg v-if="getFileIcon(item.file_path) === 'pdf'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                    <!-- Default Document Icon -->
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                
                                <div class="flex-grow">
                                    <h3 class="font-bold text-slate-800 text-lg mb-1">{{ item.title }}</h3>
                                    <div class="flex items-center gap-3 text-sm text-slate-500">
                                        <span v-if="item.category" class="bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded text-xs font-medium">{{ item.category }}</span>
                                        <span>Diunggah pada: {{ new Date(item.created_at).toLocaleDateString('id-ID') }}</span>
                                    </div>
                                </div>
                                
                                <a :href="`/storage/${item.file_path}`" target="_blank" download class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                    Unduh File
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <div v-else class="text-center py-20 bg-white rounded-2xl border border-slate-100 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-slate-300 mx-auto mb-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <h3 class="text-xl font-bold text-slate-700 mb-2">Tidak ada dokumen</h3>
                        <p class="text-slate-500">Dokumen untuk kategori ini belum tersedia.</p>
                    </div>
                    
                    <!-- Pagination -->
                    <div v-if="downloads.links && downloads.links.length > 3" class="flex justify-center gap-2 mt-8">
                        <template v-for="(link, i) in downloads.links" :key="i">
                            <div v-if="link.url === null" class="w-10 h-10 flex items-center justify-center rounded-lg text-slate-400 bg-slate-100 cursor-not-allowed" v-html="link.label"></div>
                            <Link v-else :href="link.url" :class="['w-10 h-10 flex items-center justify-center rounded-lg transition-colors font-medium', link.active ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-600 hover:bg-indigo-50 border border-slate-200']" v-html="link.label" />
                        </template>
                    </div>
                </div>
                
            </div>
        </section>
    </PublicLayout>
</template>
