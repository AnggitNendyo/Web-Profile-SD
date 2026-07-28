<script setup>
import { computed } from 'vue';

const props = defineProps({
    teacher: {
        type: Object,
        required: true,
    }
});

const photoUrl = computed(() => {
    return props.teacher.photo 
        ? `/storage/${props.teacher.photo}`
        : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(props.teacher.name) + '&background=random&size=256';
});
</script>

<template>
    <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl border border-slate-100 transition-all duration-300 group text-center relative pt-8 pb-6 px-6">
        <!-- Decorative Background -->
        <div class="absolute top-0 left-0 right-0 h-24 bg-indigo-50 z-0"></div>
        
        <!-- Photo -->
        <div class="relative z-10 w-32 h-32 mx-auto mb-4">
            <div class="w-full h-full rounded-full overflow-hidden border-4 border-white shadow-md bg-white">
                <img :src="photoUrl" :alt="teacher.name" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
            </div>
            
            <div class="absolute -bottom-2 -right-2 bg-white rounded-full p-1 shadow-sm">
                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                        <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM2.25 10.5a8.25 8.25 0 1114.59 5.28l4.69 4.69a.75.75 0 11-1.06 1.06l-4.69-4.69A8.25 8.25 0 012.25 10.5z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Info -->
        <div class="relative z-10">
            <h3 class="text-lg font-bold text-slate-900 mb-1 line-clamp-1" :title="teacher.name">
                {{ teacher.name }}
            </h3>
            
            <p class="text-indigo-600 font-medium text-sm mb-3">
                {{ teacher.position }}
            </p>
            
            <div class="flex flex-col gap-2 text-sm text-slate-500">
                <div v-if="teacher.subject" class="bg-slate-50 rounded-lg py-1.5 px-3">
                    <span class="block text-xs text-slate-400 mb-0.5">Mata Pelajaran</span>
                    <span class="font-medium text-slate-700">{{ teacher.subject }}</span>
                </div>
                
                <div v-if="teacher.nip" class="bg-slate-50 rounded-lg py-1.5 px-3">
                    <span class="block text-xs text-slate-400 mb-0.5">NIP</span>
                    <span class="font-medium text-slate-700">{{ teacher.nip }}</span>
                </div>
            </div>
        </div>
    </div>
</template>
