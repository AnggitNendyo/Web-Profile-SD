<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import TeacherCard from '@/Components/Public/TeacherCard.vue';
import { computed } from 'vue';

const props = defineProps({
    teachers: {
        type: Array,
        required: true,
    }
});

// Pisahkan Kepala Sekolah dari guru lain untuk highlight
const principal = computed(() => {
    return props.teachers.find(t => t.position.toLowerCase().includes('kepala sekolah') && !t.position.toLowerCase().includes('wakil'));
});

const otherStaff = computed(() => {
    if (!principal.value) return props.teachers;
    return props.teachers.filter(t => t.id !== principal.value.id);
});
</script>

<template>
    <PublicLayout title="Guru & Staf - SD Negeri">
        <!-- Header -->
        <section class="bg-slate-900 pt-32 pb-20 relative overflow-hidden">
            <div class="absolute inset-0 z-0">
                <!-- Decorative background elements -->
                <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl"></div>
            </div>
            <div class="container mx-auto px-4 md:px-6 relative z-10 text-center">
                <span class="text-indigo-400 font-semibold tracking-wider uppercase text-sm mb-3 block">Tenaga Pendidik & Kependidikan</span>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Guru & Staf</h1>
                <p class="text-slate-300 text-lg max-w-2xl mx-auto">
                    Mengenal para pendidik berdedikasi tinggi yang berkomitmen membimbing dan mencerdaskan generasi penerus bangsa.
                </p>
            </div>
        </section>

        <section class="py-16 md:py-24 bg-slate-50 relative -mt-8">
            <div class="container mx-auto px-4 md:px-6">
                <!-- Highlight Kepala Sekolah -->
                <div v-if="principal" class="max-w-4xl mx-auto mb-20 bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100" data-aos="fade-up">
                    <div class="flex flex-col md:flex-row">
                        <div class="w-full md:w-2/5 relative bg-indigo-50">
                            <img :src="principal.photo ? `/storage/${principal.photo}` : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(principal.name) + '&background=random&size=512'" :alt="principal.name" class="w-full h-full object-cover aspect-square md:aspect-auto" />
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-slate-900/80 to-transparent p-6 text-white md:hidden">
                                <h3 class="text-2xl font-bold">{{ principal.name }}</h3>
                                <p class="text-indigo-300">{{ principal.position }}</p>
                            </div>
                        </div>
                        <div class="w-full md:w-3/5 p-8 md:p-12 flex flex-col justify-center">
                            <div class="hidden md:block mb-6">
                                <span class="inline-block px-3 py-1 bg-indigo-100 text-indigo-700 text-sm font-semibold rounded-full mb-3">{{ principal.position }}</span>
                                <h3 class="text-3xl font-bold text-slate-900">{{ principal.name }}</h3>
                            </div>
                            
                            <div class="space-y-4 mb-8">
                                <div v-if="principal.nip" class="flex items-center gap-3 text-slate-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-indigo-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                                    </svg>
                                    <span><strong>NIP:</strong> {{ principal.nip }}</span>
                                </div>
                            </div>
                            
                            <blockquote class="text-slate-500 italic border-l-4 border-indigo-500 pl-4 leading-relaxed">
                                "Pendidikan adalah senjata paling mematikan di dunia, karena dengan pendidikan, Anda dapat mengubah dunia. Kami hadir untuk membekali mereka dengan senjata tersebut."
                            </blockquote>
                        </div>
                    </div>
                </div>

                <!-- Grid Guru Lainnya -->
                <div class="mb-12 text-center" data-aos="fade-up">
                    <h2 class="text-3xl font-bold text-slate-900 mb-4">Tenaga Pendidik</h2>
                    <div class="w-24 h-1.5 bg-indigo-600 rounded-full mx-auto"></div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    <TeacherCard 
                        v-for="(teacher, index) in otherStaff" 
                        :key="teacher.id" 
                        :teacher="teacher"
                        data-aos="fade-up"
                        :data-aos-delay="(index % 4) * 100"
                    />
                </div>
                
                <div v-if="otherStaff.length === 0" class="text-center py-12 text-slate-500 bg-white rounded-xl shadow-sm">
                    Belum ada data guru.
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
