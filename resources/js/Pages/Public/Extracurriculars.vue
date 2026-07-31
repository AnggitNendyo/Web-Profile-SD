<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { computed } from 'vue';

const props = defineProps({
    extracurriculars: {
        type: Array,
        required: true,
    },
    settings: {
        type: Object,
        default: () => ({}),
    }
});

const hasSchedule = (item) => item.schedule_day || item.schedule_time;
const scheduleText = (item) => [item.schedule_day, item.schedule_time].filter(Boolean).join(', ');
</script>

<template>
    <PublicLayout title="Ekstrakurikuler - SD Negeri">
        <!-- Header -->
        <section class="bg-slate-900 pt-32 pb-20 relative overflow-hidden">
            <div class="absolute inset-0 z-0">
                <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl"></div>
            </div>
            <div class="container mx-auto px-4 md:px-6 relative z-10 text-center">
                <span class="text-indigo-400 font-semibold tracking-wider uppercase text-sm mb-3 block">Pengembangan Minat & Bakat</span>
                <h1 class="font-display text-4xl md:text-5xl font-semibold text-white mb-6">Ekstrakurikuler</h1>
                <p class="text-slate-300 text-lg max-w-2xl mx-auto">
                    Beragam kegiatan untuk mengasah bakat, membentuk karakter, dan menumbuhkan kreativitas peserta didik di luar jam pelajaran.
                </p>
            </div>
        </section>

        <section class="py-16 md:py-24 bg-slate-50 relative -mt-8">
            <div class="container mx-auto px-4 md:px-6">
                <div v-if="extracurriculars.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div
                        v-for="(item, index) in extracurriculars"
                        :key="item.id"
                        class="group bg-white rounded-2xl shadow-sm hover:shadow-xl border border-slate-100 overflow-hidden transition-all duration-300 hover:-translate-y-1 flex flex-col"
                        data-aos="fade-up"
                        :data-aos-delay="(index % 3) * 100"
                    >
                        <!-- Foto -->
                        <div class="relative h-52 overflow-hidden bg-slate-200">
                            <img
                                :src="item.photo ? `/storage/${item.photo}` : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(item.name) + '&background=6366f1&color=fff&size=512'"
                                :alt="item.name"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                            />
                            <div v-if="item.category" class="absolute top-4 left-4 bg-indigo-600/90 backdrop-blur text-white text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
                                {{ item.category }}
                            </div>
                        </div>

                        <!-- Konten -->
                        <div class="p-6 flex flex-col flex-grow">
                            <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-indigo-600 transition-colors">
                                {{ item.name }}
                            </h3>

                            <p v-if="item.description" class="text-slate-600 text-sm line-clamp-3 mb-4 flex-grow">
                                {{ item.description }}
                            </p>
                            <div v-else class="flex-grow"></div>

                            <div class="space-y-2 mt-auto pt-4 border-t border-slate-100">
                                <div v-if="item.coach" class="flex items-center gap-2 text-sm text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-indigo-500 shrink-0">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                    <span>Pembina: {{ item.coach }}</span>
                                </div>
                                <div v-if="hasSchedule(item)" class="flex items-center gap-2 text-sm text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-indigo-500 shrink-0">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ scheduleText(item) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center py-20 bg-white rounded-2xl border border-slate-100 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-slate-300 mx-auto mb-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.562.562 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                    </svg>
                    <h3 class="text-xl font-bold text-slate-700 mb-2">Belum ada ekstrakurikuler</h3>
                    <p class="text-slate-500">Data kegiatan ekstrakurikuler akan segera ditampilkan.</p>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
