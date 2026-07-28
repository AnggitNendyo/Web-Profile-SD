<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import HeroSection from '@/Components/Public/HeroSection.vue';
import StatCounter from '@/Components/Public/StatCounter.vue';
import NewsCard from '@/Components/Public/NewsCard.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    settings: {
        type: Object,
        required: true,
    },
    latestNews: {
        type: Array,
        required: true,
    }
});

const statsData = computed(() => [
    { label: 'Total Siswa', value: parseInt(props.settings.total_students) || 300, icon: 'users', suffix: '+' },
    { label: 'Guru & Staf', value: 24, icon: 'academic-cap', suffix: '' },
    { label: 'Ekstrakurikuler', value: 12, icon: 'star', suffix: '' },
    { label: 'Prestasi', value: 50, icon: 'newspaper', suffix: '+' },
]);
</script>

<template>
    <PublicLayout :title="'Beranda - ' + (settings.school_name || 'SD Negeri')">
        <!-- Hero Section -->
        <HeroSection 
            :title="settings.school_name || 'SD Negeri 1 Nusantara'"
            :subtitle="settings.motto || 'Membangun Generasi Cerdas, Berkarakter, dan Berprestasi'"
            ctaText="Informasi PPDB"
            ctaLink="/ppdb"
            :bgImage="settings.hero_image ? `/storage/${settings.hero_image}` : 'https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=2070&auto=format&fit=crop'"
        />

        <!-- Stats Section -->
        <StatCounter :stats="statsData" />

        <!-- Sambutan Kepala Sekolah -->
        <section class="py-20 bg-white">
            <div class="container mx-auto px-4 md:px-6">
                <div class="flex flex-col md:flex-row items-center gap-12 max-w-5xl mx-auto">
                    <div class="w-full md:w-1/3 relative" data-aos="fade-right">
                        <div class="aspect-[3/4] rounded-2xl overflow-hidden shadow-2xl relative z-10 border-8 border-white">
                            <img src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?q=80&w=2070&auto=format&fit=crop" alt="Kepala Sekolah" class="w-full h-full object-cover" />
                        </div>
                        <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-indigo-100 rounded-full z-0 -rotate-12">
                            <!-- SVG Dots pattern -->
                            <svg class="absolute inset-0 w-full h-full text-indigo-300/50" fill="currentColor" viewBox="0 0 100 100">
                                <pattern id="dots" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                    <circle cx="2" cy="2" r="2"></circle>
                                </pattern>
                                <rect x="0" y="0" width="100%" height="100%" fill="url(#dots)"></rect>
                            </svg>
                        </div>
                    </div>
                    
                    <div class="w-full md:w-2/3" data-aos="fade-left">
                        <h2 class="text-3xl font-bold text-slate-900 mb-2">Sambutan Kepala Sekolah</h2>
                        <div class="w-16 h-1.5 bg-indigo-600 rounded-full mb-6"></div>
                        
                        <blockquote class="text-xl text-slate-600 italic leading-relaxed mb-6">
                            "Selamat datang di website resmi {{ settings.school_name }}. Kami berkomitmen untuk menyelenggarakan pendidikan dasar yang berkualitas, berlandaskan iman dan taqwa, serta mengedepankan pembentukan karakter peserta didik."
                        </blockquote>
                        
                        <div class="mb-8">
                            <p class="font-bold text-lg text-slate-900">{{ settings.principal_name || 'Kepala Sekolah' }}</p>
                            <p class="text-indigo-600 font-medium">Kepala Sekolah {{ settings.school_name }}</p>
                        </div>
                        
                        <Link href="/profil" class="inline-flex items-center gap-2 text-indigo-600 font-semibold hover:text-indigo-800 transition-colors group">
                            Baca Profil Lengkap
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 transition-transform group-hover:translate-x-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Berita Terbaru -->
        <section class="py-24 bg-slate-50 relative overflow-hidden">
            <!-- Decorative Element -->
            <div class="absolute top-0 right-0 w-1/3 h-full bg-slate-100 skew-x-12 translate-x-32 z-0"></div>
            
            <div class="container mx-auto px-4 md:px-6 relative z-10">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-12" data-aos="fade-up">
                    <div class="max-w-2xl">
                        <span class="text-indigo-600 font-semibold tracking-wider uppercase text-sm mb-2 block">Informasi Terkini</span>
                        <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Berita & Pengumuman</h2>
                        <p class="text-slate-600 text-lg">Ikuti perkembangan terbaru, kegiatan, dan prestasi dari {{ settings.school_name }}.</p>
                    </div>
                    
                    <Link href="/berita" class="hidden md:inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-700 font-semibold rounded-full hover:bg-slate-50 hover:text-indigo-600 transition-all shadow-sm">
                        Lihat Semua Berita
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </Link>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <NewsCard 
                        v-for="(news, index) in latestNews" 
                        :key="news.id" 
                        :news="news"
                        data-aos="fade-up"
                        :data-aos-delay="index * 150"
                    />
                </div>
                
                <div class="mt-10 text-center md:hidden">
                    <Link href="/berita" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-700 font-semibold rounded-full hover:bg-slate-50 hover:text-indigo-600 transition-all shadow-sm">
                        Lihat Semua Berita
                    </Link>
                </div>
            </div>
        </section>

        <!-- CTA PPDB -->
        <section class="py-20 relative overflow-hidden bg-indigo-900">
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?q=80&w=2022&auto=format&fit=crop" class="w-full h-full object-cover opacity-20" alt="School Background" />
                <div class="absolute inset-0 bg-indigo-900/80 mix-blend-multiply"></div>
            </div>
            
            <div class="container mx-auto px-4 md:px-6 relative z-10 text-center" data-aos="zoom-in">
                <h2 class="text-3xl md:text-5xl font-bold text-white mb-6">Penerimaan Peserta Didik Baru (PPDB)</h2>
                <p class="text-indigo-100 text-lg md:text-xl max-w-3xl mx-auto mb-10 leading-relaxed">
                    Mari bergabung bersama kami. Dapatkan pendidikan terbaik untuk masa depan gemilang putra-putri Anda. Pendaftaran telah dibuka untuk Tahun Ajaran 2026/2027.
                </p>
                <Link href="/ppdb" class="inline-block px-10 py-5 bg-white text-indigo-700 font-bold text-lg rounded-full hover:bg-indigo-50 hover:scale-105 transition-all duration-300 shadow-xl shadow-indigo-900/50">
                    Daftar Sekarang
                </Link>
            </div>
        </section>
    </PublicLayout>
</template>
