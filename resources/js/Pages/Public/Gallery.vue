<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import GalleryGrid from '@/Components/Public/GalleryGrid.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    galleries: {
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
const type = ref(props.filters.type || '');

const applyFilter = (key, value) => {
    if (key === 'category') category.value = value === category.value ? '' : value;
    if (key === 'type') type.value = value === type.value ? '' : value;
    
    router.get('/galeri', {
        category: category.value,
        type: type.value,
    }, { preserveState: true, replace: true });
};
</script>

<template>
    <PublicLayout title="Galeri Kegiatan - SD Negeri">
        <section class="bg-indigo-900 pt-32 pb-20 relative overflow-hidden">
            <div class="absolute inset-0 z-0">
                <!-- Decorative background elements -->
                <div class="absolute top-1/2 left-1/4 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl"></div>
            </div>
            <div class="container mx-auto px-4 md:px-6 relative z-10 text-center">
                <h1 class="font-display text-4xl md:text-5xl font-semibold text-white mb-6">Galeri Kegiatan</h1>
                <p class="text-indigo-100 text-lg max-w-2xl mx-auto">
                    Kumpulan momen berharga, aktivitas, dan prestasi yang terekam dalam lensa kami.
                </p>
            </div>
        </section>

        <section class="py-12 bg-slate-50 min-h-[50vh]">
            <div class="container mx-auto px-4 md:px-6">
                
                <!-- Filters -->
                <div class="flex flex-col md:flex-row gap-4 justify-between items-center mb-10 bg-white p-4 rounded-2xl shadow-sm border border-slate-100 relative z-20 -mt-20">
                    <!-- Type Filter -->
                    <div class="flex bg-slate-100 p-1 rounded-xl">
                        <button 
                            @click="applyFilter('type', '')"
                            :class="['px-5 py-2 rounded-lg text-sm font-medium transition-all', type === '' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700']"
                        >
                            Semua Media
                        </button>
                        <button 
                            @click="applyFilter('type', 'foto')"
                            :class="['px-5 py-2 rounded-lg text-sm font-medium transition-all', type === 'foto' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700']"
                        >
                            Foto
                        </button>
                        <button 
                            @click="applyFilter('type', 'video')"
                            :class="['px-5 py-2 rounded-lg text-sm font-medium transition-all', type === 'video' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700']"
                        >
                            Video
                        </button>
                    </div>
                    
                    <!-- Category Filter -->
                    <div class="flex flex-wrap gap-2 justify-center">
                        <button 
                            @click="applyFilter('category', '')"
                            :class="['px-4 py-2 rounded-full text-sm font-medium transition-colors border', category === '' ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-indigo-300 hover:text-indigo-600']"
                        >
                            Semua Kategori
                        </button>
                        <button 
                            v-for="cat in categories" 
                            :key="cat"
                            @click="applyFilter('category', cat)"
                            :class="['px-4 py-2 rounded-full text-sm font-medium transition-colors border', category === cat ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-indigo-300 hover:text-indigo-600']"
                        >
                            {{ cat }}
                        </button>
                    </div>
                </div>

                <!-- Gallery Content -->
                <div v-if="galleries.data.length > 0">
                    <GalleryGrid :galleries="galleries.data" class="mb-12" />
                    
                    <!-- Pagination -->
                    <div v-if="galleries.links.length > 3" class="flex justify-center gap-2 mt-8">
                        <template v-for="(link, i) in galleries.links" :key="i">
                            <div v-if="link.url === null" class="w-10 h-10 flex items-center justify-center rounded-lg text-slate-400 bg-slate-100 cursor-not-allowed" v-html="link.label"></div>
                            <Link v-else :href="link.url" :class="['w-10 h-10 flex items-center justify-center rounded-lg transition-colors font-medium', link.active ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-600 hover:bg-indigo-50 border border-slate-200']" v-html="link.label" />
                        </template>
                    </div>
                </div>
                
                <div v-else class="text-center py-20 bg-white rounded-2xl border border-slate-100 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-slate-300 mx-auto mb-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    <h3 class="text-xl font-bold text-slate-700 mb-2">Belum ada galeri</h3>
                    <p class="text-slate-500">Tidak ada foto atau video yang sesuai dengan filter Anda.</p>
                </div>

            </div>
        </section>
    </PublicLayout>
</template>
