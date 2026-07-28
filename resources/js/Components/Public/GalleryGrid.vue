<script setup>
import { ref, computed } from 'vue';
import Lightbox from './Lightbox.vue';

const props = defineProps({
    galleries: {
        type: Array,
        required: true,
    }
});

const activeLightbox = ref(false);
const currentImage = ref('');
const currentTitle = ref('');
const currentType = ref('foto');

const openLightbox = (gallery) => {
    // Foto & video sama-sama dibuka inline di Lightbox.
    currentImage.value = `/storage/${gallery.file_path}`;
    currentTitle.value = gallery.title;
    currentType.value = gallery.type;
    activeLightbox.value = true;
};

const getThumbnail = (gallery) => {
    if (gallery.type === 'foto') {
        return `/storage/${gallery.file_path}`;
    }
    // For video, we might not have a thumbnail in this schema, so return a placeholder
    return 'https://images.unsplash.com/photo-1611162617474-5b21e879e113?q=80&w=2000&auto=format&fit=crop'; 
};
</script>

<template>
    <div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div 
                v-for="(item, index) in galleries" 
                :key="item.id" 
                class="group relative bg-slate-100 rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 aspect-square cursor-pointer"
                @click="openLightbox(item)"
                data-aos="zoom-in"
                :data-aos-delay="index * 50"
            >
                <img :src="getThumbnail(item)" :alt="item.title" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                
                <!-- Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-5">
                    <span class="inline-block px-2.5 py-1 bg-indigo-600 text-white text-[10px] font-bold uppercase tracking-wider rounded w-max mb-2">
                        {{ item.type === 'video' ? 'Video' : 'Foto' }}
                    </span>
                    <h3 class="text-white font-semibold text-lg line-clamp-2 leading-tight">
                        {{ item.title }}
                    </h3>
                    <p v-if="item.category" class="text-indigo-200 text-sm mt-1">
                        {{ item.category }}
                    </p>
                </div>
                
                <!-- Play Icon for Video -->
                <div v-if="item.type === 'video'" class="absolute inset-0 flex items-center justify-center opacity-80 group-hover:opacity-100 transition-opacity">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center border border-white/30 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 ml-1">
                            <path fill-rule="evenodd" d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <Lightbox :show="activeLightbox" :image="currentImage" :title="currentTitle" :type="currentType" @close="activeLightbox = false" />
    </div>
</template>
