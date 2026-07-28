<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import NewsCard from '@/Components/Public/NewsCard.vue';
import { Link, Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import { format, parseISO } from 'date-fns';
import { id } from 'date-fns/locale';

const props = defineProps({
    news: {
        type: Object,
        required: true,
    },
    related: {
        type: Array,
        required: true,
    }
});

const formattedDate = computed(() => {
    if (!props.news.published_at) return '';
    return format(parseISO(props.news.published_at), 'EEEE, dd MMMM yyyy - HH:mm', { locale: id });
});

const thumbnailUrl = computed(() => {
    return props.news.thumbnail 
        ? `/storage/${props.news.thumbnail}`
        : 'https://images.unsplash.com/photo-1546410531-bb4caa6b424d?q=80&w=2071&auto=format&fit=crop';
});
</script>

<template>
    <PublicLayout :title="news.title">
        <Head>
            <meta name="description" :content="news.content.replace(/<[^>]+>/g, '').substring(0, 160)" />
            <meta property="og:title" :content="news.title" />
            <meta property="og:image" :content="thumbnailUrl" />
        </Head>

        <!-- Article Header -->
        <section class="relative pt-32 pb-20 overflow-hidden">
            <div class="absolute inset-0 z-0">
                <img :src="thumbnailUrl" :alt="news.title" class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/80 to-slate-900/40"></div>
            </div>
            
            <div class="container mx-auto px-4 md:px-6 relative z-10">
                <div class="max-w-4xl mx-auto mt-10">
                    <Link href="/berita" class="inline-flex items-center gap-2 text-indigo-300 hover:text-white transition-colors mb-6 font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Kembali ke Daftar Berita
                    </Link>
                    
                    <div class="flex items-center gap-3 mb-6">
                        <span v-if="news.category" class="px-3 py-1 bg-indigo-600 text-white text-sm font-semibold rounded-full">
                            {{ news.category }}
                        </span>
                        <span class="text-slate-300 text-sm flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ formattedDate }}
                        </span>
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-8">
                        {{ news.title }}
                    </h1>
                    
                    <div v-if="news.author" class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-slate-800 border-2 border-indigo-500 overflow-hidden">
                            <img :src="'https://ui-avatars.com/api/?name=' + encodeURIComponent(news.author.name) + '&background=random'" alt="Author" class="w-full h-full object-cover" />
                        </div>
                        <div>
                            <p class="text-white font-medium">{{ news.author.name }}</p>
                            <p class="text-indigo-300 text-sm">Penulis</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Article Content -->
        <section class="py-16 bg-white relative">
            <div class="container mx-auto px-4 md:px-6">
                <div class="max-w-3xl mx-auto -mt-24 relative z-20">
                    <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 border border-slate-100">
                        <div class="prose prose-lg prose-indigo max-w-none prose-img:rounded-xl prose-img:shadow-md" v-html="news.content"></div>
                        
                        <!-- Share Options (Static visual only for now) -->
                        <div class="mt-12 pt-8 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-slate-500 font-medium">Bagikan Artikel Ini:</span>
                            <div class="flex gap-3">
                                <button class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-indigo-600 hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.326-.043-1.557-.14-2.857-.14C11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4v-8.5z"/></svg>
                                </button>
                                <button class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-sky-500 hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/></svg>
                                </button>
                                <button class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-green-500 hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M12.023 21.096A9.096 9.096 0 1121.119 12a9.096 9.096 0 01-9.096 9.096zm0-16.692a7.596 7.596 0 100 15.192 7.596 7.596 0 000-15.192zM15.422 9.043c-.22-.108-1.312-.647-1.516-.72-.203-.072-.352-.108-.501.108-.148.216-.575.72-.705.865-.13.145-.262.163-.483.054-.221-.108-.937-.346-1.785-1.107-.659-.592-1.104-1.323-1.233-1.539-.13-.216-.014-.333.096-.441.098-.097.221-.253.332-.379.11-.126.148-.216.221-.36.073-.145.037-.271-.018-.38-.055-.108-.501-1.21-.686-1.657-.18-.435-.362-.376-.501-.383-.13-.007-.279-.009-.427-.009a.82.82 0 00-.593.277c-.204.217-.78 .762-.78 1.859 0 1.098.8 2.158.91 2.304.111.146 1.571 2.398 3.805 3.363.532.229.948.366 1.272.469.533.169 1.02.145 1.405.088.432-.064 1.312-.536 1.498-1.055.185-.519.185-.964.13-1.055-.055-.09-.204-.144-.425-.252z" clip-rule="evenodd"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Related News -->
        <section v-if="related.length > 0" class="py-16 bg-slate-50 border-t border-slate-200">
            <div class="container mx-auto px-4 md:px-6">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-2xl font-bold text-slate-900 mb-8">Berita Terkait</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <NewsCard 
                            v-for="item in related" 
                            :key="item.id" 
                            :news="item"
                        />
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<style>
/* Base typography for content area */
.prose p {
    @apply mb-5 leading-loose text-lg;
}
.prose h2, .prose h3 {
    @apply mt-10 mb-4 font-bold text-slate-900;
}
.prose h2 { @apply text-2xl; }
.prose h3 { @apply text-xl; }
.prose ul, .prose ol {
    @apply pl-6 mb-5 space-y-2 text-lg;
}
.prose blockquote {
    @apply border-l-4 border-indigo-500 pl-4 py-1 italic bg-indigo-50 rounded-r-lg my-6;
}
</style>
