<script setup>
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
    }
});
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

        <!-- Content -->
        <section class="py-16 md:py-24 bg-slate-50 relative">
            <div class="container mx-auto px-4 md:px-6">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 max-w-6xl mx-auto">
                    <!-- Sidebar Nav (Optional/Desktop) -->
                    <div class="lg:col-span-3 hidden lg:block">
                        <div class="sticky top-24 bg-white rounded-xl shadow-sm border border-slate-100 p-4">
                            <h3 class="font-bold text-slate-900 mb-4 px-2 uppercase text-sm tracking-wider">Daftar Isi</h3>
                            <ul class="space-y-1">
                                <li>
                                    <a href="#profil" class="block px-3 py-2 rounded-lg text-indigo-600 font-medium bg-indigo-50 transition-colors">Profil Sekolah</a>
                                </li>
                                <li>
                                    <a href="#visi-misi" class="block px-3 py-2 rounded-lg text-slate-600 hover:text-indigo-600 hover:bg-slate-50 font-medium transition-colors">Visi & Misi</a>
                                </li>
                                <li>
                                    <a href="#identitas" class="block px-3 py-2 rounded-lg text-slate-600 hover:text-indigo-600 hover:bg-slate-50 font-medium transition-colors">Identitas Sekolah</a>
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
