<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    title: {
        type: String,
        required: true,
    },
    subtitle: {
        type: String,
        default: '',
    },
    ctaText: {
        type: String,
        default: 'Pendaftaran Siswa Baru',
    },
    ctaLink: {
        type: String,
        default: '/ppdb',
    },
    bgImage: {
        type: String,
        default: 'https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=2070&auto=format&fit=crop', // school kids placeholder
    }
});
</script>

<template>
    <section class="relative h-[85vh] min-h-[600px] flex items-center justify-center overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 z-0">
            <img :src="bgImage" alt="Hero Background" class="w-full h-full object-cover object-center scale-105 transform motion-safe:animate-[pulse_10s_ease-in-out_infinite_alternate]" />
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/70 to-slate-900/40"></div>
        </div>

        <!-- Content -->
        <div class="container mx-auto px-4 md:px-6 relative z-10 text-white">
            <div class="max-w-3xl" data-aos="fade-up" data-aos-duration="1000">
                <span class="inline-flex items-center gap-2 py-1.5 pl-2.5 pr-4 rounded-full bg-white/10 border border-white/20 text-indigo-50 text-sm font-semibold mb-6 backdrop-blur-md shadow-lg">
                    <span class="inline-block h-4 w-0.5 rounded-full bg-red-400" aria-hidden="true"></span>
                    Selamat Datang di
                </span>

                <h1 class="font-display text-5xl md:text-6xl lg:text-7xl font-semibold leading-[1.05] mb-6 tracking-tight drop-shadow-lg">
                    <span class="relative inline-block">
                        {{ title }}
                        <!-- Coretan kapur di bawah judul, tergambar sekali saat load -->
                        <svg
                            class="chalk-underline absolute -bottom-3 left-0 w-full h-4 text-indigo-300"
                            viewBox="0 0 300 16" preserveAspectRatio="none" fill="none" aria-hidden="true"
                        >
                            <path d="M3 11 C 60 4, 120 4, 160 8 S 260 13, 297 6"
                                stroke="currentColor" stroke-width="4" stroke-linecap="round" />
                        </svg>
                    </span>
                </h1>
                
                <p class="text-xl md:text-2xl text-slate-200 mb-10 max-w-2xl font-light leading-relaxed drop-shadow-md">
                    {{ subtitle }}
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <Link :href="ctaLink" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-full transition-all duration-300 shadow-[0_0_20px_rgba(79,70,229,0.4)] hover:shadow-[0_0_25px_rgba(79,70,229,0.6)] hover:-translate-y-1">
                        {{ ctaText }}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                        </svg>
                    </Link>
                    
                    <Link href="/profil" class="inline-flex items-center justify-center px-8 py-4 bg-white/10 hover:bg-white/20 text-white border border-white/30 font-semibold rounded-full backdrop-blur-md transition-all duration-300 hover:-translate-y-1">
                        Tentang Sekolah Kami
                    </Link>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator (di atas kartu statistik yang overlap ~64px, z-30 agar tidak tertutup) -->
        <div class="absolute bottom-24 left-1/2 -translate-x-1/2 z-30 animate-bounce">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" class="w-8 h-8 opacity-70">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
        </div>
        
        <!-- Decorative Elements -->
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl z-0"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl z-0"></div>
    </section>
</template>

<style scoped>
/* Coretan kapur: garis "digambar" sekali saat load memakai stroke-dashoffset. */
.chalk-underline path {
    stroke-dasharray: 320;
    stroke-dashoffset: 320;
    animation: chalk-draw 1.1s cubic-bezier(0.65, 0, 0.35, 1) 0.6s forwards;
}

@keyframes chalk-draw {
    to {
        stroke-dashoffset: 0;
    }
}

@media (prefers-reduced-motion: reduce) {
    .chalk-underline path {
        animation: none;
        stroke-dashoffset: 0;
    }
}
</style>
