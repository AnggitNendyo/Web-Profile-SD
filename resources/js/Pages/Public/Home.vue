<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import HeroSection from '@/Components/Public/HeroSection.vue';
import StatCounter from '@/Components/Public/StatCounter.vue';
import NewsCard from '@/Components/Public/NewsCard.vue';
import TeacherCard from '@/Components/Public/TeacherCard.vue';
import SectionHeading from '@/Components/Public/SectionHeading.vue';
import { Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { format, parseISO } from 'date-fns';
import { id as idLocale } from 'date-fns/locale';

const props = defineProps({
    settings: {
        type: Object,
        required: true,
    },
    latestNews: {
        type: Array,
        required: true,
    },
    teachers: {
        type: Array,
        default: () => [],
    },
    extracurriculars: {
        type: Array,
        default: () => [],
    },
    facilities: {
        type: Array,
        default: () => [],
    },
    visiMisi: {
        type: Object,
        default: () => null,
    },
    ppdbStatus: {
        type: Object,
        default: () => ({ open: false, state: 'closed' }),
    },
    stats: {
        type: Object,
        default: () => ({}),
    }
});

const statsData = computed(() => [
    { label: 'Total Siswa', value: props.stats.students || 0, icon: 'users', suffix: '+' },
    { label: 'Guru & Staf', value: props.stats.teachers || 0, icon: 'academic-cap', suffix: '' },
    { label: 'Ekstrakurikuler', value: props.stats.extracurriculars || 0, icon: 'star', suffix: '' },
    { label: 'Prestasi', value: props.stats.achievements || 0, icon: 'newspaper', suffix: '+' },
]);

const principalPhoto = computed(() =>
    props.settings.principal_photo
        ? `/storage/${props.settings.principal_photo}`
        : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(props.settings.principal_name || 'Kepala Sekolah') + '&background=6366f1&color=fff&size=512'
);

const ppdbBannerClass = computed(() => {
    if (props.ppdbStatus.state === 'open') return 'bg-emerald-600';
    if (props.ppdbStatus.state === 'not_started') return 'bg-blue-600';
    return 'bg-slate-600';
});

const ppdbBannerIcon = computed(() => {
    if (props.ppdbStatus.state === 'open') return 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
    if (props.ppdbStatus.state === 'not_started') return 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z';
    return 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z';
});

const scheduleText = (item) => [item.schedule_day, item.schedule_time].filter(Boolean).join(' · ');

// Gambar latar CTA PPDB: pakai banner PPDB / hero yang bisa diatur admin, fallback ke Unsplash.
const ctaImage = computed(() => {
    const bg = props.settings.banner_ppdb || props.settings.hero_image;
    return bg
        ? `/storage/${bg}`
        : 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?q=80&w=2022&auto=format&fit=crop';
});

// Slider horizontal (dipakai Berita, Ekstrakurikuler, Fasilitas)
const newsSlider = ref(null);
const extraSlider = ref(null);
const facilitySlider = ref(null);

const formatSchedule = (buka, tutup) => {
    if (buka && tutup) {
        return `${format(parseISO(buka), 'd MMM', { locale: idLocale })} - ${format(parseISO(tutup), 'd MMM yyyy', { locale: idLocale })}`;
    } else if (buka) {
        return `Mulai ${format(parseISO(buka), 'd MMMM yyyy', { locale: idLocale })}`;
    } else if (tutup) {
        return `Hingga ${format(parseISO(tutup), 'd MMMM yyyy', { locale: idLocale })}`;
    }
    return 'Belum ditentukan';
};

const slide = (el, direction) => {
    if (!el) return;
    // Geser sejauh satu kartu (± lebar kartu pertama + gap).
    const card = el.querySelector(':scope > div');
    const amount = card ? card.offsetWidth + 24 : el.clientWidth * 0.8;
    el.scrollBy({ left: direction * amount, behavior: 'smooth' });
};

const slideNews = (direction) => slide(newsSlider.value, direction);
const slideExtra = (direction) => slide(extraSlider.value, direction);
const slideFacilities = (direction) => slide(facilitySlider.value, direction);
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

        <!-- CTA PPDB -->
        <section v-if="ppdbStatus.state !== 'closed'" :class="['py-20 relative overflow-hidden', ppdbBannerClass]">
            <div class="absolute inset-0 z-0">
                <img :src="ctaImage" class="w-full h-full object-cover opacity-20" alt="School Background" />
                <div class="absolute inset-0 bg-indigo-900/80 mix-blend-multiply"></div>
            </div>

            <div class="container mx-auto px-4 md:px-6 relative z-10 text-center" data-aos="zoom-in">
                <span :class="['inline-flex items-center gap-2 py-1.5 px-4 rounded-full border text-sm font-semibold mb-6', ppdbStatus.state === 'open' ? 'bg-emerald-500/20 border-emerald-400/40 text-emerald-100' : 'bg-white/20 border-white/40 text-white']">
                    <span v-if="ppdbStatus.state === 'open'" class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse" aria-hidden="true"></span>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" :d="ppdbBannerIcon" />
                    </svg>
                    {{ ppdbStatus.state === 'open' ? 'Pendaftaran Dibuka' : (ppdbStatus.state === 'not_started' ? 'Segera Dibuka' : 'Pendaftaran Ditutup') }}
                </span>
                <h2 class="font-display text-3xl md:text-5xl font-semibold text-white mb-6">Penerimaan Peserta Didik Baru (PPDB)</h2>
                <p class="text-indigo-100 text-lg md:text-xl max-w-3xl mx-auto mb-8 leading-relaxed">
                    {{ ppdbStatus.state === 'open' ? 'Mari bergabung bersama kami. Dapatkan pendidikan terbaik untuk masa depan gemilang putra-putri Anda.' : ppdbStatus.message }}
                </p>

                <!-- Info jadwal & kuota PPDB per jalur -->
                <div class="flex flex-col gap-4 mb-10 max-w-4xl mx-auto">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 justify-center">
                        <div v-for="track in ppdbStatus.tracks" :key="track.id" class="flex flex-col p-5 rounded-2xl bg-white/10 border border-white/15 backdrop-blur-sm text-white text-left">
                            <h3 class="font-bold text-lg text-emerald-300 mb-3">{{ track.jalur }}</h3>
                            <div class="flex flex-col gap-3">
                                <div class="flex items-start gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-indigo-200 shrink-0 mt-0.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                    <span class="font-medium text-sm leading-snug">{{ formatSchedule(track.jadwal_buka, track.jadwal_tutup) }}</span>
                                </div>
                                <div v-if="track.kuota" class="flex items-start gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-indigo-200 shrink-0 mt-0.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                    </svg>
                                    <span class="font-medium text-sm leading-snug">Kuota: {{ track.kuota }} kursi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="ppdbStatus.sisa !== null && ppdbStatus.sisa !== undefined" class="inline-flex mx-auto items-center gap-2 px-5 py-3 rounded-2xl bg-white/5 border border-white/10 text-white mt-4">
                        <span class="text-sm text-indigo-100">Total Sisa Kuota Keseluruhan: <strong class="text-white ml-1">{{ ppdbStatus.sisa }} kursi</strong></span>
                    </div>
                </div>

                <Link v-if="ppdbStatus.state === 'open'" href="/ppdb" class="inline-block px-10 py-4 bg-white text-indigo-700 font-bold text-lg rounded-full hover:bg-indigo-50 hover:scale-105 transition-all duration-300 shadow-xl shadow-indigo-900/50">
                    Daftar Sekarang
                </Link>
                <Link v-else href="/ppdb" class="inline-block px-10 py-4 bg-white/20 backdrop-blur text-white font-bold text-lg rounded-full hover:bg-white/30 transition-all duration-300 border border-white/30">
                    Lihat Detail PPDB
                </Link>
            </div>
        </section>

        <!-- Stats Section -->
        <StatCounter :stats="statsData" />

        <!-- Sambutan Kepala Sekolah (Dinamis) -->
        <section class="py-20 bg-white">
            <div class="container mx-auto px-4 md:px-6">
                <div class="flex flex-col md:flex-row items-center gap-12 max-w-5xl mx-auto">
                    <div class="w-full md:w-1/3 relative" data-aos="fade-right">
                        <div class="aspect-[3/4] rounded-2xl overflow-hidden shadow-2xl relative z-10 border-8 border-white">
                            <img :src="principalPhoto" :alt="settings.principal_name || 'Kepala Sekolah'" class="w-full h-full object-cover" />
                        </div>
                        <div class="absolute -bottom-6 -right-6 w-48 h-48 bg-indigo-100 rounded-full z-0"></div>
                        <div class="absolute -top-6 -left-6 w-24 h-24 bg-indigo-600/10 rounded-full z-0"></div>
                    </div>

                    <div class="w-full md:w-2/3" data-aos="fade-left">
                        <SectionHeading eyebrow="Dari Pimpinan" title="Sambutan Kepala Sekolah" class="mb-6" />

                        <blockquote class="text-lg text-slate-600 italic leading-relaxed mb-6 mt-6 border-l-4 border-indigo-200 pl-5">
                            "{{ settings.principal_greeting || 'Selamat datang di website resmi ' + (settings.school_name || 'sekolah kami') + '. Kami berkomitmen untuk menyelenggarakan pendidikan dasar yang berkualitas, berlandaskan iman dan taqwa, serta mengedepankan pembentukan karakter peserta didik.' }}"
                        </blockquote>

                        <div class="mb-8 flex items-center gap-4">
                            <div class="w-12 h-1 bg-indigo-600 rounded-full"></div>
                            <div>
                                <p class="font-bold text-lg text-slate-900">{{ settings.principal_name || 'Kepala Sekolah' }}</p>
                                <p class="text-indigo-600 font-medium text-sm">Kepala {{ settings.school_name }}</p>
                            </div>
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

        <!-- Identitas Sekolah & Visi Misi -->
        <section class="py-20 bg-slate-50">
            <div class="container mx-auto px-4 md:px-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-6xl mx-auto items-stretch">
                    <!-- Kartu Identitas -->
                    <div class="bg-indigo-900 rounded-2xl shadow-xl p-8 md:p-10 text-white relative overflow-hidden" data-aos="fade-right">
                        <svg class="absolute inset-0 w-full h-full text-indigo-800/50" fill="currentColor" viewBox="0 0 100 100">
                            <pattern id="home-boxes" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                <rect x="0" y="0" width="4" height="4"></rect>
                            </pattern>
                            <rect x="0" y="0" width="100%" height="100%" fill="url(#home-boxes)"></rect>
                        </svg>
                        <div class="relative z-10">
                            <SectionHeading eyebrow="Tentang Kami" title="Identitas Sekolah" light class="mb-8" />
                            <div class="space-y-5 mt-8">
                                <div class="border-b border-indigo-700/50 pb-4">
                                    <span class="block text-indigo-300 text-sm mb-1">Nama Sekolah</span>
                                    <span class="font-semibold text-lg">{{ settings.school_name || '-' }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="border-b border-indigo-700/50 pb-4">
                                        <span class="block text-indigo-300 text-sm mb-1">NPSN</span>
                                        <span class="font-semibold text-lg">{{ settings.npsn || '-' }}</span>
                                    </div>
                                    <div class="border-b border-indigo-700/50 pb-4">
                                        <span class="block text-indigo-300 text-sm mb-1">Tahun Berdiri</span>
                                        <span class="font-semibold text-lg">{{ settings.established_year || '-' }}</span>
                                    </div>
                                </div>
                                <div class="border-b border-indigo-700/50 pb-4">
                                    <span class="block text-indigo-300 text-sm mb-1">Status Akreditasi</span>
                                    <span class="font-semibold text-lg inline-flex items-center gap-2">
                                        <span class="w-8 h-8 rounded-full bg-yellow-400 text-yellow-900 flex items-center justify-center font-bold text-sm">{{ settings.accreditation || '-' }}</span>
                                        Terakreditasi
                                    </span>
                                </div>
                                <div class="pb-2">
                                    <span class="block text-indigo-300 text-sm mb-1">Alamat</span>
                                    <span class="font-semibold text-base leading-relaxed">{{ settings.address || '-' }}</span>
                                </div>
                            </div>
                            <Link href="/profil#identitas" class="mt-8 inline-flex items-center gap-2 text-white font-semibold hover:text-indigo-200 transition-colors group">
                                Selengkapnya
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 transition-transform group-hover:translate-x-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </Link>
                        </div>
                    </div>

                    <!-- Visi & Misi -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 md:p-10 flex flex-col" data-aos="fade-left">
                        <SectionHeading eyebrow="Arah &amp; Tujuan" :title="visiMisi?.title || 'Visi & Misi'" class="mb-6" />

                        <div
                            v-if="visiMisi?.content"
                            class="prose prose-indigo max-w-none text-slate-600 line-clamp-[12] overflow-hidden flex-grow mt-6"
                            v-html="visiMisi.content"
                        ></div>
                        <p v-else class="text-slate-500 flex-grow">
                            Visi dan misi sekolah belum tersedia. Silakan lengkapi melalui halaman pengaturan.
                        </p>

                        <Link href="/profil#visi-misi" class="mt-6 inline-flex items-center gap-2 text-indigo-600 font-semibold hover:text-indigo-800 transition-colors group">
                            Baca Visi & Misi Lengkap
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 transition-transform group-hover:translate-x-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Berita Terbaru -->
        <section class="py-24 bg-white relative overflow-hidden">
            <!-- Decorative Element -->
            <div class="absolute top-0 right-0 w-1/3 h-full bg-slate-50 skew-x-12 translate-x-32 z-0"></div>

            <div class="container mx-auto px-4 md:px-6 relative z-10">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-12" data-aos="fade-up">
                    <SectionHeading eyebrow="Informasi Terkini" title="Berita & Pengumuman" class="max-w-2xl">
                        Ikuti perkembangan terbaru, kegiatan, dan prestasi dari {{ settings.school_name }}.
                    </SectionHeading>

                    <div class="hidden md:flex items-center gap-3">
                        <Link href="/berita" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-700 font-semibold rounded-full hover:bg-slate-50 hover:text-indigo-600 transition-all shadow-sm">
                            Lihat Semua Berita
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </Link>
                        <button
                            type="button"
                            @click="slideNews(-1)"
                            class="w-11 h-11 rounded-full bg-white border border-slate-200 text-slate-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all shadow-sm"
                            aria-label="Sebelumnya"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                        <button
                            type="button"
                            @click="slideNews(1)"
                            class="w-11 h-11 rounded-full bg-white border border-slate-200 text-slate-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all shadow-sm"
                            aria-label="Berikutnya"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Slider -->
                <div
                    ref="newsSlider"
                    class="flex gap-8 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-4 -mx-4 px-4 md:mx-0 md:px-0 facility-scroll"
                >
                    <div
                        v-for="news in latestNews"
                        :key="news.id"
                        class="snap-start shrink-0 w-[85%] sm:w-[48%] lg:w-[31.5%]"
                    >
                        <NewsCard :news="news" />
                    </div>
                </div>

                <div class="mt-10 text-center md:hidden">
                    <Link href="/berita" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-700 font-semibold rounded-full hover:bg-slate-50 hover:text-indigo-600 transition-all shadow-sm">
                        Lihat Semua Berita
                    </Link>
                </div>
            </div>
        </section>

        <!-- Fasilitas Sekolah -->
        <section v-if="facilities.length > 0" class="py-20 bg-slate-50">
            <div class="container mx-auto px-4 md:px-6">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-12" data-aos="fade-up">
                    <SectionHeading eyebrow="Sarana & Prasarana" title="Fasilitas Sekolah" class="max-w-2xl">
                        Sarana penunjang yang lengkap untuk mendukung proses belajar mengajar yang nyaman dan berkualitas.
                    </SectionHeading>
                    <!-- Kontrol Slider (desktop) -->
                    <div class="hidden md:flex items-center gap-3 mt-4 md:mt-0">
                        <button
                            type="button"
                            @click="slideFacilities(-1)"
                            class="w-11 h-11 rounded-full bg-white border border-slate-200 text-slate-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all shadow-sm"
                            aria-label="Sebelumnya"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                        <button
                            type="button"
                            @click="slideFacilities(1)"
                            class="w-11 h-11 rounded-full bg-white border border-slate-200 text-slate-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all shadow-sm"
                            aria-label="Berikutnya"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Slider -->
                <div
                    ref="facilitySlider"
                    class="flex gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-4 -mx-4 px-4 md:mx-0 md:px-0 facility-scroll"
                >
                    <div
                        v-for="fac in facilities"
                        :key="fac.id"
                        class="group snap-start shrink-0 w-[85%] sm:w-[48%] lg:w-[31.5%] bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-xl hover:border-indigo-100 transition-all duration-300"
                    >
                        <!-- Foto / fallback ikon -->
                        <div class="relative h-52 overflow-hidden bg-indigo-50">
                            <img
                                v-if="fac.photo"
                                :src="`/storage/${fac.photo}`"
                                :alt="fac.title"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                            />
                            <div v-else class="w-full h-full flex items-center justify-center text-indigo-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor" class="w-16 h-16">
                                    <path stroke-linecap="round" stroke-linejoin="round" :d="fac.icon || 'M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75'" />
                                </svg>
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-slate-900 mb-2 group-hover:text-indigo-600 transition-colors">{{ fac.title }}</h3>
                            <p class="text-slate-500 text-sm leading-relaxed line-clamp-3">{{ fac.description }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 text-center md:hidden">
                    <Link href="/profil#fasilitas" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-700 font-semibold rounded-full hover:text-indigo-600 transition-all shadow-sm">
                        Lihat Semua Fasilitas
                    </Link>
                </div>
            </div>
        </section>

        <!-- Ekstrakurikuler Unggulan -->
        <section v-if="extracurriculars.length > 0" class="py-20 bg-gradient-to-br from-indigo-50 to-slate-50 relative overflow-hidden">
            <div class="absolute -top-16 -right-16 w-72 h-72 bg-indigo-200/30 rounded-full blur-3xl z-0"></div>
            <div class="absolute -bottom-16 -left-16 w-72 h-72 bg-purple-200/30 rounded-full blur-3xl z-0"></div>

            <div class="container mx-auto px-4 md:px-6 relative z-10">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-12" data-aos="fade-up">
                    <SectionHeading eyebrow="Pengembangan Bakat" title="Kegiatan Ekstrakurikuler" class="max-w-2xl">
                        Beragam kegiatan untuk mengasah bakat dan membentuk karakter peserta didik.
                    </SectionHeading>
                    <div class="hidden md:flex items-center gap-3 mt-4 md:mt-0">
                        <Link href="/ekstrakurikuler" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-700 font-semibold rounded-full hover:bg-slate-50 hover:text-indigo-600 transition-all shadow-sm">
                            Lihat Semua
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </Link>
                        <button
                            type="button"
                            @click="slideExtra(-1)"
                            class="w-11 h-11 rounded-full bg-white border border-slate-200 text-slate-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all shadow-sm"
                            aria-label="Sebelumnya"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                        <button
                            type="button"
                            @click="slideExtra(1)"
                            class="w-11 h-11 rounded-full bg-white border border-slate-200 text-slate-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all shadow-sm"
                            aria-label="Berikutnya"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Slider -->
                <div
                    ref="extraSlider"
                    class="flex gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-4 -mx-4 px-4 md:mx-0 md:px-0 facility-scroll"
                >
                    <div
                        v-for="item in extracurriculars"
                        :key="item.id"
                        class="group snap-start shrink-0 w-[85%] sm:w-[48%] lg:w-[31.5%] bg-white rounded-2xl shadow-sm hover:shadow-xl border border-slate-100 overflow-hidden transition-all duration-300 flex flex-col"
                    >
                        <div class="relative h-44 overflow-hidden bg-indigo-100">
                            <img
                                :src="item.photo ? `/storage/${item.photo}` : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(item.name) + '&background=6366f1&color=fff&size=512'"
                                :alt="item.name"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                            />
                            <div v-if="item.category" class="absolute top-3 left-3 bg-indigo-600/90 backdrop-blur text-white text-xs font-semibold px-3 py-1 rounded-full">
                                {{ item.category }}
                            </div>
                        </div>
                        <div class="p-5 flex flex-col flex-grow">
                            <h3 class="text-base font-bold text-slate-900 mb-2 group-hover:text-indigo-600 transition-colors">{{ item.name }}</h3>
                            <p v-if="item.description" class="text-slate-500 text-sm line-clamp-2 mb-3 flex-grow">{{ item.description }}</p>
                            <div v-else class="flex-grow"></div>
                            <div v-if="item.coach || item.schedule_day" class="flex flex-wrap gap-2 mt-2 pt-3 border-t border-slate-100">
                                <span v-if="item.coach" class="inline-flex items-center gap-1 text-xs text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5 text-indigo-400">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                    {{ item.coach }}
                                </span>
                                <span v-if="scheduleText(item)" class="inline-flex items-center gap-1 text-xs text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5 text-indigo-400">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ scheduleText(item) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 text-center md:hidden">
                    <Link href="/ekstrakurikuler" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-700 font-semibold rounded-full hover:text-indigo-600 transition-all shadow-sm">
                        Lihat Semua Ekstrakurikuler
                    </Link>
                </div>
            </div>
        </section>

        <!-- Guru & Staf Unggulan -->
        <section v-if="teachers.length > 0" class="py-20 bg-white">
            <div class="container mx-auto px-4 md:px-6">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-12" data-aos="fade-up">
                    <SectionHeading eyebrow="Tim Pendidik" title="Guru &amp; Staf Kami" class="max-w-2xl">
                        Tenaga pendidik profesional dan berdedikasi untuk masa depan peserta didik.
                    </SectionHeading>
                    <Link href="/guru-staf" class="hidden md:inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-700 font-semibold rounded-full hover:bg-slate-50 hover:text-indigo-600 transition-all shadow-sm mt-4 md:mt-0">
                        Lihat Semua
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </Link>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                    <TeacherCard
                        v-for="(teacher, index) in teachers"
                        :key="teacher.id"
                        :teacher="teacher"
                        data-aos="fade-up"
                        :data-aos-delay="(index % 4) * 80"
                    />
                </div>

                <div class="mt-8 text-center md:hidden">
                    <Link href="/guru-staf" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-700 font-semibold rounded-full hover:text-indigo-600 transition-all shadow-sm">
                        Lihat Semua Guru & Staf
                    </Link>
                </div>
            </div>
        </section>

    </PublicLayout>
</template>

<style>
/* Sembunyikan scrollbar pada slider fasilitas (tetap bisa di-scroll) */
.facility-scroll {
    -ms-overflow-style: none; /* IE & Edge lama */
    scrollbar-width: none; /* Firefox */
}
.facility-scroll::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Edge */
}

/* Styling untuk konten Visi & Misi yang dirender dari database */
.prose p {
    margin-bottom: 1rem;
    line-height: 1.75;
}
.prose ol {
    list-style-type: decimal;
    padding-left: 1.25rem;
    margin-bottom: 1rem;
}
.prose ul {
    list-style-type: disc;
    padding-left: 1.25rem;
    margin-bottom: 1rem;
}
.prose ol > li,
.prose ul > li {
    margin-top: 0.5rem;
    line-height: 1.75;
}
.prose strong {
    font-weight: 600;
    color: #4338ca; /* indigo-700 */
}
.prose h2,
.prose h3 {
    font-size: 1.125rem;
    line-height: 1.75rem;
    font-weight: 700;
    color: #1e293b; /* slate-800 */
    margin-top: 1rem;
    margin-bottom: 0.5rem;
}
</style>
