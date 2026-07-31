<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import NewsCard from '@/Components/Public/NewsCard.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    news: {
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

const search = ref(props.filters.search || '');
const category = ref(props.filters.category || '');

const submitSearch = debounce(() => {
    router.get('/berita', {
        search: search.value,
        category: category.value,
    }, { preserveState: true, replace: true });
}, 300);

const selectCategory = (cat) => {
    category.value = cat === category.value ? '' : cat;
    submitSearch();
};

watch(search, submitSearch);
</script>

<template>
    <PublicLayout title="Berita & Pengumuman - SD Negeri">
        <section class="bg-indigo-900 pt-32 pb-20 relative overflow-hidden">
            <div v-if="$page.props.schoolSettings?.banner_news" class="absolute inset-0 z-0">
                <img :src="`/storage/${$page.props.schoolSettings.banner_news}`" alt="Banner" class="w-full h-full object-cover opacity-20 mix-blend-overlay" />
            </div>
            <div v-else class="absolute inset-0 z-0 opacity-10">
                <div class="absolute w-full h-full bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCI+CjxjaXJjbGUgY3g9IjIiIGN5PSIyIiByPSIyIiBmaWxsPSIjZmZmZmZmIi8+Cjwvc3ZnPg==')]"></div>
            </div>
            <div class="container mx-auto px-4 md:px-6 relative z-10 text-center">
                <h1 class="font-display text-4xl md:text-5xl font-semibold text-white mb-6">Berita & Pengumuman</h1>
                <p class="text-indigo-100 text-lg max-w-2xl mx-auto">
                    Informasi terkini seputar kegiatan sekolah, prestasi siswa, dan pengumuman penting lainnya.
                </p>
            </div>
        </section>

        <section class="py-12 bg-slate-50 min-h-[50vh]">
            <div class="container mx-auto px-4 md:px-6">
                
                <!-- Filters -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 md:p-6 mb-10 flex flex-col md:flex-row gap-6 justify-between items-center z-20 relative -mt-20">
                    <div class="w-full md:w-1/3">
                        <div class="relative">
                            <input 
                                v-model="search" 
                                type="text" 
                                placeholder="Cari berita..." 
                                class="w-full pl-10 pr-4 py-3 bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 rounded-xl transition-all"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-slate-400 absolute left-3 top-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                    </div>
                    
                    <div class="w-full md:w-auto flex flex-wrap gap-2 justify-center md:justify-end">
                        <button 
                            @click="selectCategory('')"
                            :class="['px-4 py-2 rounded-full text-sm font-medium transition-colors', category === '' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200']"
                        >
                            Semua
                        </button>
                        <button 
                            v-for="cat in categories" 
                            :key="cat"
                            @click="selectCategory(cat)"
                            :class="['px-4 py-2 rounded-full text-sm font-medium transition-colors', category === cat ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200']"
                        >
                            {{ cat }}
                        </button>
                    </div>
                </div>

                <!-- Content Grid -->
                <div v-if="news.data.length > 0">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                        <NewsCard 
                            v-for="(item, index) in news.data" 
                            :key="item.id" 
                            :news="item"
                            data-aos="fade-up"
                            :data-aos-delay="(index % 3) * 100"
                        />
                    </div>
                    
                    <!-- Pagination -->
                    <div v-if="news.links.length > 3" class="flex justify-center gap-2">
                        <template v-for="(link, i) in news.links" :key="i">
                            <div v-if="link.url === null" class="w-10 h-10 flex items-center justify-center rounded-lg text-slate-400 bg-slate-100 cursor-not-allowed" v-html="link.label"></div>
                            <Link v-else :href="link.url" :class="['w-10 h-10 flex items-center justify-center rounded-lg transition-colors font-medium', link.active ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-600 hover:bg-indigo-50 border border-slate-200']" v-html="link.label" />
                        </template>
                    </div>
                </div>
                
                <div v-else class="text-center py-20 bg-white rounded-2xl border border-slate-100 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-slate-300 mx-auto mb-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <h3 class="text-xl font-bold text-slate-700 mb-2">Tidak ada berita</h3>
                    <p class="text-slate-500">Pencarian untuk "{{ search }}" tidak menemukan hasil. Coba kata kunci lain.</p>
                    <button @click="search = ''; category = ''; submitSearch()" class="mt-6 px-6 py-2 bg-indigo-50 text-indigo-600 font-medium rounded-lg hover:bg-indigo-100 transition-colors">
                        Reset Filter
                    </button>
                </div>

            </div>
        </section>
    </PublicLayout>
</template>
