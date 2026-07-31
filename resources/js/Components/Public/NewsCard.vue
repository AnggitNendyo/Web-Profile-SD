<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { format, parseISO } from 'date-fns';
import { id } from 'date-fns/locale';

const props = defineProps({
    news: {
        type: Object,
        required: true,
    }
});

const formattedDate = computed(() => {
    if (!props.news.published_at) return '';
    return format(parseISO(props.news.published_at), 'dd MMM yyyy', { locale: id });
});

const excerpt = computed(() => {
    const text = props.news.content.replace(/<[^>]+>/g, '');
    return text.length > 100 ? text.substring(0, 100) + '...' : text;
});

const thumbnailUrl = computed(() => {
    return props.news.thumbnail 
        ? `/storage/${props.news.thumbnail}`
        : 'https://images.unsplash.com/photo-1546410531-bb4caa6b424d?q=80&w=2071&auto=format&fit=crop';
});
</script>

<template>
    <Link :href="`/berita/${news.slug}`" class="group flex flex-col h-full bg-white rounded-2xl shadow-sm hover:shadow-xl border border-slate-100 overflow-hidden transition-all duration-300 hover:-translate-y-1">
        <!-- Thumbnail -->
        <div class="relative h-48 overflow-hidden bg-slate-200">
            <img :src="thumbnailUrl" :alt="news.title" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
            
            <div v-if="news.category" class="absolute top-4 left-4 bg-indigo-600/90 backdrop-blur text-white text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
                {{ news.category }}
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-6 flex flex-col flex-grow">
            <!-- Meta -->
            <div class="flex items-center gap-4 text-xs text-slate-500 mb-3">
                <div class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    {{ formattedDate }}
                </div>
                <div v-if="news.author" class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    {{ news.author.name }}
                </div>
            </div>
            
            <h3 class="font-display text-xl font-semibold text-slate-900 mb-3 line-clamp-2 group-hover:text-indigo-600 transition-colors">
                {{ news.title }}
            </h3>
            
            <p class="text-slate-600 text-sm line-clamp-3 mb-6 flex-grow">
                {{ excerpt }}
            </p>
            
            <div class="mt-auto flex items-center text-indigo-600 font-semibold text-sm group-hover:gap-2 transition-all">
                Baca Selengkapnya
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </div>
        </div>
    </Link>
</template>
