<script setup>
import { computed, ref } from 'vue';
import TeacherModal from './TeacherModal.vue';

const showModal = ref(false);

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
    <div 
        @click="showModal = true"
        class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl border border-slate-100 transition-all duration-300 group text-center relative pt-8 pb-6 px-6 cursor-pointer flex flex-col h-full"
    >
        <!-- Decorative Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
        <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-100/50 rounded-bl-[100px] -mr-4 -mt-4 transition-transform duration-500 group-hover:scale-110 pointer-events-none"></div>
        
        <div class="relative w-28 h-28 mx-auto rounded-full overflow-hidden mb-5 border-4 border-white shadow-md group-hover:shadow-lg group-hover:-translate-y-1 transition-all duration-300 bg-white shrink-0">
            <img :src="photoUrl" :alt="teacher.name" class="w-full h-full object-cover" loading="lazy" />
        </div>
        
        <div class="relative z-10 flex flex-col flex-1">
            <h3 class="text-xl font-bold text-slate-800 mb-1 group-hover:text-indigo-600 transition-colors line-clamp-2">{{ teacher.name }}</h3>
            <p class="text-indigo-500 font-medium text-sm mb-4 line-clamp-1">{{ teacher.position }}</p>
            
            <div class="flex flex-col gap-2 mt-auto">
                <div v-if="teacher.subject" class="bg-slate-50 rounded-lg py-1.5 px-3">
                    <span class="block text-xs text-slate-400 mb-0.5">Mata Pelajaran</span>
                    <span class="font-medium text-slate-700 line-clamp-1" :title="teacher.subject">{{ teacher.subject }}</span>
                </div>
                
                <div v-if="teacher.nip" class="bg-slate-50 rounded-lg py-1.5 px-3">
                    <span class="block text-xs text-slate-400 mb-0.5">NIP</span>
                    <span class="font-medium text-slate-700 line-clamp-1" :title="teacher.nip">{{ teacher.nip }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Teacher Detail Modal -->
    <TeacherModal :show="showModal" :teacher="teacher" @close="showModal = false" />
</template>
