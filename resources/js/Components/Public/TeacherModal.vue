<script setup>
import { computed } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    teacher: {
        type: Object,
        required: true,
    }
});

const emit = defineEmits(['close']);

const photoUrl = computed(() => {
    return props.teacher?.photo 
        ? `/storage/${props.teacher.photo}`
        : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(props.teacher?.name || '') + '&background=random&size=256';
});
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="ease-out duration-300" enter-from-class="opacity-0" enter-to-class="opacity-100"
            leave-active-class="ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0"
        >
            <div v-if="show && teacher" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="emit('close')"></div>

                <!-- Modal Content -->
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl relative z-10 overflow-y-auto overflow-x-hidden custom-scrollbar max-h-[90vh] transform transition-all flex flex-col">
                    <!-- Header with vibrant gradient cover -->
                    <div class="relative h-40 shrink-0 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
                        <!-- Decorative overlay pattern -->
                        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-white via-transparent to-transparent"></div>
                        
                        <button @click="emit('close')" class="absolute top-4 right-4 p-2 bg-black/20 hover:bg-black/40 backdrop-blur-md rounded-full text-white transition-all duration-300 z-20">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Profile Info -->
                    <div class="px-6 sm:px-10 pb-10 pt-0">
                        <div class="flex flex-col sm:flex-row gap-6 items-center sm:items-start -mt-20 relative z-10">
                            <div class="w-36 h-36 rounded-full overflow-hidden border-[6px] border-white shadow-xl bg-white shrink-0 relative group">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors z-10"></div>
                                <img :src="photoUrl" :alt="teacher.name" class="w-full h-full object-cover" />
                            </div>
                            <div class="text-center sm:text-left mt-2 sm:mt-24 w-full">
                                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-1">{{ teacher.name }}</h3>
                                <div class="inline-block px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm font-semibold mb-2">
                                    {{ teacher.position }}
                                </div>
                            </div>
                        </div>

                        <!-- Stats Cards -->
                        <div class="mt-8 grid grid-cols-2 gap-4">
                            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 text-center hover:bg-slate-100 transition-colors">
                                <span class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Mata Pelajaran</span>
                                <span class="font-semibold text-slate-800 sm:text-lg">{{ teacher.subject || '-' }}</span>
                            </div>
                            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 text-center hover:bg-slate-100 transition-colors">
                                <span class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">NIP</span>
                                <span class="font-semibold text-slate-800 sm:text-lg">{{ teacher.nip || '-' }}</span>
                            </div>
                        </div>

                        <!-- Education Timeline -->
                        <div class="mt-8">
                            <div class="flex items-center gap-2 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                                </svg>
                                <h4 class="text-lg font-bold text-slate-800">Riwayat Pendidikan</h4>
                            </div>
                            
                            <div v-if="Array.isArray(teacher.education) && teacher.education.length > 0" class="space-y-4">
                                <div v-for="(edu, index) in teacher.education" :key="index" class="group relative pl-6 sm:pl-8 py-2">
                                    <!-- Timeline line -->
                                    <div class="absolute left-0 top-0 bottom-0 w-px bg-indigo-100 group-last:bg-gradient-to-b group-last:from-indigo-100 group-last:to-transparent"></div>
                                    <!-- Timeline dot -->
                                    <div class="absolute left-[-4px] top-3.5 w-2 h-2 rounded-full bg-indigo-500 ring-4 ring-indigo-50 transition-transform group-hover:scale-150 group-hover:bg-purple-500"></div>
                                    
                                    <div class="bg-white border border-slate-100 shadow-sm rounded-2xl p-4 sm:p-5 transition-all duration-300 hover:shadow-md hover:border-indigo-100">
                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-1">
                                            <h5 class="font-bold text-slate-800 text-base group-hover:text-indigo-600 transition-colors">{{ edu.degree }} {{ edu.program }}</h5>
                                            <span v-if="edu.year" class="inline-flex w-fit items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700">
                                                Lulus {{ edu.year }}
                                            </span>
                                        </div>
                                        <p class="text-sm font-medium text-slate-500 flex items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10.496 2.132a1 1 0 00-.992 0l-7 4A1 1 0 003 8v7a1 1 0 100 2h14a1 1 0 100-2V8a1 1 0 00.496-1.868l-7-4zM6 9a1 1 0 00-1 1v3a1 1 0 102 0v-3a1 1 0 00-1-1zm3 1a1 1 0 012 0v3a1 1 0 11-2 0v-3zm5-1a1 1 0 00-1 1v3a1 1 0 102 0v-3a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            {{ edu.university }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-sm font-medium text-slate-500 bg-slate-50 p-4 rounded-xl border border-slate-100">
                                Belum ada riwayat pendidikan yang ditambahkan.
                            </div>
                        </div>

                        <!-- Bio -->
                        <div class="mt-8">
                            <div class="flex items-center gap-2 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                <h4 class="text-lg font-bold text-slate-800">Profil Singkat</h4>
                            </div>
                            <div v-if="teacher.bio" class="relative">
                                <div class="absolute -top-2 -left-2 text-indigo-100 opacity-50">
                                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M10 8c-3.3 0-6 2.7-6 6v10h10V14H6c0-2.2 1.8-4 4-4V8zm18 0c-3.3 0-6 2.7-6 6v10h10V14h-8c0-2.2 1.8-4 4-4V8z"/></svg>
                                </div>
                                <div class="bg-gradient-to-br from-indigo-50/50 to-purple-50/50 rounded-2xl p-5 sm:p-6 border border-indigo-100/50 relative z-10">
                                    <p class="text-slate-700 leading-relaxed text-sm sm:text-base whitespace-pre-line font-medium">{{ teacher.bio }}</p>
                                </div>
                            </div>
                            <div v-else class="text-sm font-medium text-slate-500 bg-slate-50 p-4 rounded-xl border border-slate-100">
                                Belum ada profil singkat.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
