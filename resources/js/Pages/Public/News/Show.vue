<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import NewsCard from '@/Components/Public/NewsCard.vue';
import { Link, Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
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

// URL absolut artikel untuk dibagikan (dibangun saat runtime di browser).
const shareUrl = computed(() => `${window.location.origin}/berita/${props.news.slug}`);

const openShare = (network) => {
    const url = encodeURIComponent(shareUrl.value);
    const text = encodeURIComponent(props.news.title);
    const targets = {
        facebook: `https://www.facebook.com/sharer/sharer.php?u=${url}`,
        twitter: `https://twitter.com/intent/tweet?url=${url}&text=${text}`,
        whatsapp: `https://wa.me/?text=${text}%20${url}`,
    };
    window.open(targets[network], '_blank', 'noopener,noreferrer,width=600,height=500');
};

const copied = ref(false);
const copyLink = async () => {
    try {
        await navigator.clipboard.writeText(shareUrl.value);
        copied.value = true;
        setTimeout(() => (copied.value = false), 2000);
    } catch (e) {
        // Fallback bila clipboard API tidak tersedia.
        window.prompt('Salin tautan berikut:', shareUrl.value);
    }
};
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
                        <!-- Featured video YouTube -->
                        <div v-if="news.youtube_embed_url" class="mb-10 rounded-xl overflow-hidden shadow-md aspect-video bg-black">
                            <iframe
                                :src="`${news.youtube_embed_url}?rel=0`"
                                class="w-full h-full"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen
                            ></iframe>
                        </div>

                        <div class="prose prose-lg prose-indigo max-w-none prose-img:rounded-xl prose-img:shadow-md" v-html="news.content"></div>
                        
                        <!-- Share Options -->
                        <div class="mt-12 pt-8 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <span class="text-slate-500 font-medium">Bagikan Artikel Ini:</span>
                            <div class="flex gap-3">
                                <button @click="openShare('facebook')" title="Bagikan ke Facebook" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-blue-600 hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.326-.043-1.557-.14-2.857-.14C11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4v-8.5z"/></svg>
                                </button>
                                <button @click="openShare('twitter')" title="Bagikan ke Twitter/X" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-sky-500 hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/></svg>
                                </button>
                                <button @click="openShare('whatsapp')" title="Bagikan ke WhatsApp" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-green-500 hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                </button>
                                <button @click="copyLink" :title="copied ? 'Tersalin!' : 'Salin tautan'" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-indigo-600 hover:text-white transition-colors">
                                    <svg v-if="!copied" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
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
