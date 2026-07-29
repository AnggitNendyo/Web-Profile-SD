<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import HeroSection from '@/Components/Public/HeroSection.vue';
import StatCounter from '@/Components/Public/StatCounter.vue';
import NewsCard from '@/Components/Public/NewsCard.vue';
import TeacherCard from '@/Components/Public/TeacherCard.vue';
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
    },
    teachers: {
        type: Array,
        default: () => [],
    },
    extracurriculars: {
        type: Array,
        default: () => [],
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

        <!-- PPDB Status Banner (dinamis) -->
        <section v-if="ppdbStatus.state !== 'ended'" class="py-4">
            <div class="container mx-auto px-4 md:px-6">
                <div
                    :class="[ppdbBannerClass, 'rounded-2xl px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-lg']"
                    data-aos="fade-up"
                >
                    <div class="flex items-center gap-3 text-white">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" :d="ppdbBannerIcon" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-sm sm:text-base">
                                <span v-if="ppdbStatus.state === 'open'">🎉 PPDB Sedang Dibuka!</span>
                                <span v-else-if="ppdbStatus.state === 'not_started'">📅 PPDB Akan Segera Dibuka</span>
                                <span v-else>ℹ️ Informasi PPDB</span>
                            </p>
                            <p class="text-sm text-white/80">{{ ppdbStatus.message }}</p>
                        </div>
                    </div>
                    <Link
                        href="/ppdb"
                        class="shrink-0 px-5 py-2 bg-white rounded-full text-sm font-bold transition-all hover:scale-105 shadow"
                        :class="ppdbStatus.state === 'open' ? 'text-emerald-700' : ppdbStatus.state === 'not_started' ? 'text-blue-700' : 'text-slate-700'"
                    >
                        {{ ppdbStatus.state === 'open' ? 'Daftar Sekarang' : 'Lihat Info' }}
                    </Link>
                </div>
            </div>
        </section>

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
                        <span class="text-indigo-600 font-semibold tracking-wider uppercase text-sm mb-2 block">Dari Pimpinan</span>
                        <h2 class="text-3xl font-bold text-slate-900 mb-2">Sambutan Kepala Sekolah</h2>
                        <div class="w-16 h-1.5 bg-indigo-600 rounded-full mb-6"></div>

                        <blockquote class="text-lg text-slate-600 italic leading-relaxed mb-6 border-l-4 border-indigo-200 pl-5">
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

        <!-- Ekstrakurikuler Unggulan -->
        <section v-if="extracurriculars.length > 0" class="py-20 bg-gradient-to-br from-indigo-50 to-slate-50 relative overflow-hidden">
            <div class="absolute -top-16 -right-16 w-72 h-72 bg-indigo-200/30 rounded-full blur-3xl z-0"></div>
            <div class="absolute -bottom-16 -left-16 w-72 h-72 bg-purple-200/30 rounded-full blur-3xl z-0"></div>

            <div class="container mx-auto px-4 md:px-6 relative z-10">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-12" data-aos="fade-up">
                    <div class="max-w-2xl">
                        <span class="text-indigo-600 font-semibold tracking-wider uppercase text-sm mb-2 block">Pengembangan Bakat</span>
                        <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Kegiatan Ekstrakurikuler</h2>
                        <p class="text-slate-600 text-lg">Beragam kegiatan untuk mengasah bakat dan membentuk karakter peserta didik.</p>
                    </div>
                    <Link href="/ekstrakurikuler" class="hidden md:inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-700 font-semibold rounded-full hover:bg-slate-50 hover:text-indigo-600 transition-all shadow-sm mt-4 md:mt-0">
                        Lihat Semua
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </Link>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="(item, index) in extracurriculars"
                        :key="item.id"
                        class="group bg-white rounded-2xl shadow-sm hover:shadow-xl border border-slate-100 overflow-hidden transition-all duration-300 hover:-translate-y-1 flex flex-col"
                        data-aos="fade-up"
                        :data-aos-delay="(index % 3) * 100"
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

        <!-- Guru & Staf Unggulan -->
        <section v-if="teachers.length > 0" class="py-20 bg-white">
            <div class="container mx-auto px-4 md:px-6">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-12" data-aos="fade-up">
                    <div class="max-w-2xl">
                        <span class="text-indigo-600 font-semibold tracking-wider uppercase text-sm mb-2 block">Tim Pendidik</span>
                        <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Guru & Staf Kami</h2>
                        <p class="text-slate-600 text-lg">Tenaga pendidik profesional dan berdedikasi untuk masa depan peserta didik.</p>
                    </div>
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

        <!-- CTA PPDB -->
        <section class="py-20 relative overflow-hidden bg-indigo-900">
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?q=80&w=2022&auto=format&fit=crop" class="w-full h-full object-cover opacity-20" alt="School Background" />
                <div class="absolute inset-0 bg-indigo-900/80 mix-blend-multiply"></div>
            </div>

            <div class="container mx-auto px-4 md:px-6 relative z-10 text-center" data-aos="zoom-in">
                <h2 class="text-3xl md:text-5xl font-bold text-white mb-6">Penerimaan Peserta Didik Baru (PPDB)</h2>
                <p class="text-indigo-100 text-lg md:text-xl max-w-3xl mx-auto mb-10 leading-relaxed">
                    Mari bergabung bersama kami. Dapatkan pendidikan terbaik untuk masa depan gemilang putra-putri Anda.
                </p>
                <Link href="/ppdb" class="inline-block px-10 py-5 bg-white text-indigo-700 font-bold text-lg rounded-full hover:bg-indigo-50 hover:scale-105 transition-all duration-300 shadow-xl shadow-indigo-900/50">
                    Daftar Sekarang
                </Link>
            </div>
        </section>
    </PublicLayout>
</template>
