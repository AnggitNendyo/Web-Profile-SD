<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    settings: {
        type: Object,
        required: true,
    },
    page: {
        type: Object,
        required: true,
    },
    visiMisi: {
        type: Object,
        required: true,
    },
    facilities: {
        type: Array,
        required: true,
    },
    dynamicStats: {
        type: Object,
        required: true,
    }
});

const activeSection = ref('profil');
let observer = null;

onMounted(() => {
    // Scroll Spy Logic
    const sections = ['profil', 'visi-misi', 'identitas', 'fasilitas'];
    const options = {
        root: null,
        rootMargin: '-50% 0px -50% 0px', // Center of viewport
        threshold: 0
    };

    observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                activeSection.value = entry.target.id;
            }
        });
    }, options);

    sections.forEach(id => {
        const el = document.getElementById(id);
        if (el) observer.observe(el);
    });
});

onUnmounted(() => {
    if (observer) {
        observer.disconnect();
    }
});

// Use dynamic stats from backend
const stats = [
    { label: 'Guru & Staf', value: props.dynamicStats.teachers_count + '+', icon: 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z' },
    { label: 'Siswa Aktif', value: props.dynamicStats.students_count + '+', icon: 'M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5' },
    { label: 'Ekstrakurikuler', value: props.dynamicStats.extracurriculars_count, icon: 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.536a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z' },
    { label: 'Tahun Berdiri', value: props.settings.established_year || '1990', icon: 'M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.315 48.315 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z' },
];
</script>

<template>
    <PublicLayout :title="'Profil - ' + (settings.school_name || 'SD Negeri')">
        <!-- Header -->
        <section class="bg-indigo-900 pt-32 pb-20 relative overflow-hidden">
            <div class="absolute inset-0 z-0 opacity-20">
                <img :src="$page.props.schoolSettings?.banner_profile ? `/storage/${$page.props.schoolSettings.banner_profile}` : 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=2070&auto=format&fit=crop'" alt="School" class="w-full h-full object-cover mix-blend-overlay" />
            </div>
            <div class="container mx-auto px-4 md:px-6 relative z-10 text-center">
                <span class="text-indigo-300 font-semibold tracking-wider uppercase text-sm mb-2 block">Tentang Kami</span>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Profil Sekolah</h1>
                <p class="text-indigo-100 text-lg max-w-2xl mx-auto">
                    Mengenal lebih dekat sejarah, visi, misi, dan komitmen kami dalam dunia pendidikan.
                </p>
            </div>
        </section>

        <!-- Quick Stats -->
        <section class="relative z-20 -mt-12 mb-8 px-4 md:px-6">
            <div class="container mx-auto">
                <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-8">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 divide-x divide-slate-100">
                        <div v-for="(stat, index) in stats" :key="index" class="text-center px-4" data-aos="fade-up" :data-aos-delay="index * 100">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" :d="stat.icon" />
                                </svg>
                            </div>
                            <h4 class="text-3xl font-extrabold text-slate-900 mb-1">{{ stat.value }}</h4>
                            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">{{ stat.label }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Content -->
        <section class="py-12 md:py-20 bg-slate-50 relative">
            <div class="container mx-auto px-4 md:px-6">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 max-w-7xl mx-auto">
                    <!-- Sidebar Nav (Optional/Desktop) -->
                    <div class="lg:col-span-3 hidden lg:block">
                        <div class="sticky top-28 bg-white rounded-2xl shadow-sm border border-slate-100 p-5 overflow-hidden relative">
                            <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>
                            <h3 class="font-bold text-slate-900 mb-4 px-2 uppercase text-xs tracking-widest">Navigasi Profil</h3>
                            <ul class="space-y-1.5 text-sm">
                                <li>
                                    <a href="#profil" 
                                       :class="activeSection === 'profil' ? 'text-indigo-700 font-semibold bg-indigo-50' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50 font-medium'" 
                                       class="block px-3 py-2.5 rounded-xl transition-colors">Profil Sekolah</a>
                                </li>
                                <li>
                                    <a href="#visi-misi" 
                                       :class="activeSection === 'visi-misi' ? 'text-indigo-700 font-semibold bg-indigo-50' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50 font-medium'" 
                                       class="block px-3 py-2.5 rounded-xl transition-colors">Visi & Misi</a>
                                </li>
                                <li>
                                    <a href="#identitas" 
                                       :class="activeSection === 'identitas' ? 'text-indigo-700 font-semibold bg-indigo-50' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50 font-medium'" 
                                       class="block px-3 py-2.5 rounded-xl transition-colors">Identitas Sekolah</a>
                                </li>
                                <li>
                                    <a href="#fasilitas" 
                                       :class="activeSection === 'fasilitas' ? 'text-indigo-700 font-semibold bg-indigo-50' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50 font-medium'" 
                                       class="block px-3 py-2.5 rounded-xl transition-colors">Fasilitas Unggulan</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="lg:col-span-9 space-y-16">
                        <!-- Sejarah / Profil -->
                        <div id="profil" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 md:p-10" data-aos="fade-up">
                            <h2 class="text-3xl font-bold text-slate-900 mb-6 flex items-center gap-4">
                                <span class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.315 48.315 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                                    </svg>
                                </span>
                                {{ page?.title || 'Profil Sekolah' }}
                            </h2>
                            <div class="prose prose-lg prose-indigo max-w-none text-slate-600" v-html="page?.content || '<p>Konten belum tersedia.</p>'"></div>
                        </div>

                        <!-- Visi Misi -->
                        <div id="visi-misi" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 md:p-10" data-aos="fade-up">
                            <h2 class="text-3xl font-bold text-slate-900 mb-6 flex items-center gap-4">
                                <span class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                                    </svg>
                                </span>
                                {{ visiMisi?.title || 'Visi & Misi' }}
                            </h2>
                            <div class="prose prose-lg prose-indigo max-w-none text-slate-600" v-html="visiMisi?.content || '<p>Konten belum tersedia.</p>'"></div>
                        </div>

                        <!-- Identitas Sekolah (dari settings) -->
                        <div id="identitas" class="bg-indigo-900 rounded-2xl shadow-xl p-8 md:p-10 text-white relative overflow-hidden" data-aos="fade-up">
                            <!-- SVG pattern -->
                            <svg class="absolute inset-0 w-full h-full text-indigo-800/50" fill="currentColor" viewBox="0 0 100 100">
                                <pattern id="boxes" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                    <rect x="0" y="0" width="4" height="4"></rect>
                                </pattern>
                                <rect x="0" y="0" width="100%" height="100%" fill="url(#boxes)"></rect>
                            </svg>
                            
                            <div class="relative z-10">
                                <h2 class="text-3xl font-bold mb-8">Identitas Sekolah</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                                    <div class="border-b border-indigo-700/50 pb-4">
                                        <span class="block text-indigo-300 text-sm mb-1">Nama Sekolah</span>
                                        <span class="font-semibold text-lg">{{ settings.school_name }}</span>
                                    </div>
                                    <div class="border-b border-indigo-700/50 pb-4">
                                        <span class="block text-indigo-300 text-sm mb-1">NPSN</span>
                                        <span class="font-semibold text-lg">{{ settings.npsn || '-' }}</span>
                                    </div>
                                    <div class="border-b border-indigo-700/50 pb-4">
                                        <span class="block text-indigo-300 text-sm mb-1">Status Akreditasi</span>
                                        <span class="font-semibold text-lg inline-flex items-center gap-2">
                                            <span class="w-8 h-8 rounded-full bg-yellow-400 text-yellow-900 flex items-center justify-center font-bold text-sm">{{ settings.accreditation || '-' }}</span>
                                            Unggul
                                        </span>
                                    </div>
                                    <div class="border-b border-indigo-700/50 pb-4">
                                        <span class="block text-indigo-300 text-sm mb-1">Tahun Berdiri</span>
                                        <span class="font-semibold text-lg">{{ settings.established_year || '-' }}</span>
                                    </div>
                                    <div class="border-b border-indigo-700/50 pb-4 md:col-span-2">
                                        <span class="block text-indigo-300 text-sm mb-1">Alamat Lengkap</span>
                                        <span class="font-semibold text-lg">{{ settings.address }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fasilitas Unggulan -->
                        <div id="fasilitas" class="pt-8" data-aos="fade-up">
                            <h2 class="text-3xl font-bold text-slate-900 mb-8 flex items-center gap-4">
                                <span class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75" />
                                    </svg>
                                </span>
                                Fasilitas Unggulan
                            </h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div v-for="(fac, index) in facilities" :key="index" class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:border-indigo-100 transition-all duration-300 group">
                                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" :d="fac.icon" />
                                        </svg>
                                    </div>
                                    <h4 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-indigo-600 transition-colors">{{ fac.title }}</h4>
                                    <p class="text-slate-600 leading-relaxed">{{ fac.desc }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<style>
/* Styling for prose content that comes from database */
.prose h2 {
    @apply text-2xl font-bold text-slate-800 mt-8 mb-4 border-b border-slate-200 pb-2;
}
.prose p {
    @apply mb-4 leading-relaxed;
}
.prose ol {
    @apply list-decimal pl-5 space-y-2 mb-6;
}
.prose ul {
    @apply list-disc pl-5 space-y-2 mb-6;
}
.prose li {
    @apply leading-relaxed;
}
.prose strong {
    @apply font-semibold text-indigo-700;
}
</style>
