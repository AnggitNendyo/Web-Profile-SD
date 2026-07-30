<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';

const isScrolled = ref(false);
const isMobileMenuOpen = ref(false);

// Data sekolah dari shared props global (HandleInertiaRequests).
const page = usePage();
const settings = computed(() => page.props.schoolSettings || {});
const schoolName = computed(() => settings.value.school_name || 'SD Negeri 1 Nusantara');
// Inisial untuk kotak logo (mis. "SD Negeri 1 Nusantara" -> "SD").
const initials = computed(() => {
    const words = schoolName.value.trim().split(/\s+/).filter(Boolean);
    return (words.slice(0, 2).map((w) => w[0]).join('') || 'SD').toUpperCase();
});
const currentYear = new Date().getFullYear();

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

defineProps({
    title: {
        type: String,
        default: 'SD Negeri',
    },
});
</script>

<template>
    <Head :title="title" />

    <div class="min-h-screen flex flex-col bg-slate-50 text-slate-800 font-sans">
        <!-- Navbar -->
        <header 
            :class="[
                'fixed top-0 left-0 right-0 z-50 transition-all duration-300',
                isScrolled ? 'bg-white/90 backdrop-blur-md shadow-sm py-3' : 'bg-transparent py-5'
            ]"
        >
            <div class="container mx-auto px-4 md:px-6 flex justify-between items-center">
                <!-- Logo -->
                <Link href="/" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-indigo-600 text-white rounded-lg flex items-center justify-center font-bold text-xl group-hover:bg-indigo-700 transition-colors">
                        {{ initials }}
                    </div>
                    <div>
                        <h1 :class="['font-bold leading-tight transition-colors', isScrolled ? 'text-slate-900' : 'text-slate-900 md:text-white']">{{ schoolName }}</h1>
                        <p v-if="settings.motto" :class="['text-xs font-medium transition-colors', isScrolled ? 'text-slate-500' : 'text-slate-500 md:text-slate-200']">{{ settings.motto }}</p>
                    </div>
                </Link>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center gap-2">
                    <div class="flex items-center gap-1 rounded-full bg-white/95 backdrop-blur-xl ring-1 ring-slate-900/5 shadow-lg shadow-slate-900/5 p-1.5">
                        <Link href="/" :class="['nav-item', $page.component === 'Public/Home' ? 'is-active' : '']">Beranda</Link>
                        <Link href="/profil" :class="['nav-item', $page.component === 'Public/Profile' ? 'is-active' : '']">Profil</Link>
                        <Link href="/berita" :class="['nav-item', $page.component.startsWith('Public/News') ? 'is-active' : '']">Berita</Link>
                        <!-- <Link href="/galeri" :class="['nav-item', $page.component === 'Public/Gallery' ? 'is-active' : '']">Galeri</Link> -->
                        <Link href="/ekstrakurikuler" :class="['nav-item', $page.component === 'Public/Extracurriculars' ? 'is-active' : '']">Ekstrakurikuler</Link>
                        <Link href="/kalender" :class="['nav-item', $page.component === 'Public/Calendar' ? 'is-active' : '']">Kalender</Link>
                        <Link href="/ppdb" :class="['nav-item', $page.component === 'Public/Ppdb' ? 'is-active' : '']">PPDB</Link>
                    </div>
                </nav>

                <!-- Mobile Menu Button -->
                <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="md:hidden text-slate-800 p-2">
                    <svg v-if="!isMobileMenuOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Navigation -->
            <div v-show="isMobileMenuOpen" class="md:hidden absolute top-full left-0 right-0 bg-white shadow-xl border-t border-slate-100 py-4 px-6 flex flex-col gap-4 z-50">
                <Link href="/" class="font-medium text-slate-700 hover:text-indigo-600 py-2 border-b border-slate-50">Beranda</Link>
                <Link href="/profil" class="font-medium text-slate-700 hover:text-indigo-600 py-2 border-b border-slate-50">Profil</Link>
                <Link href="/berita" class="font-medium text-slate-700 hover:text-indigo-600 py-2 border-b border-slate-50">Berita</Link>
                <!-- <Link href="/galeri" class="font-medium text-slate-700 hover:text-indigo-600 py-2 border-b border-slate-50">Galeri</Link> -->
                <Link href="/ekstrakurikuler" class="font-medium text-slate-700 hover:text-indigo-600 py-2 border-b border-slate-50">Ekstrakurikuler</Link>
                <Link href="/kalender" class="font-medium text-slate-700 hover:text-indigo-600 py-2 border-b border-slate-50">Kalender</Link>
                <Link href="/ppdb" class="font-medium text-slate-700 hover:text-indigo-600 py-2 border-b border-slate-50">PPDB</Link>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-slate-900 text-slate-300 pt-16 pb-8 border-t-4 border-indigo-500">
            <div class="container mx-auto px-4 md:px-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                    <div class="md:col-span-2">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-indigo-500 text-white rounded-lg flex items-center justify-center font-bold text-xl">
                                {{ initials }}
                            </div>
                            <div>
                                <h2 class="font-bold text-xl text-white">{{ schoolName }}</h2>
                                <p v-if="settings.motto" class="text-sm text-slate-400">{{ settings.motto }}</p>
                            </div>
                        </div>
                        <p class="text-slate-400 leading-relaxed max-w-md mb-6">
                            {{ settings.footer_description || 'Kami berkomitmen memberikan pendidikan berkualitas yang tidak hanya fokus pada pencapaian akademik, tetapi juga pembentukan karakter siswa.' }}
                        </p>
                        <div class="flex gap-4">
                            <a
                                v-if="settings.social_instagram"
                                :href="settings.social_instagram"
                                target="_blank"
                                rel="noopener"
                                class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-indigo-500 hover:text-white transition-colors"
                                aria-label="Instagram"
                            >
                                IG
                            </a>
                            <a
                                v-if="settings.social_facebook"
                                :href="settings.social_facebook"
                                target="_blank"
                                rel="noopener"
                                class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-indigo-500 hover:text-white transition-colors"
                                aria-label="Facebook"
                            >
                                FB
                            </a>
                            <a
                                v-if="settings.social_youtube"
                                :href="settings.social_youtube"
                                target="_blank"
                                rel="noopener"
                                class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-indigo-500 hover:text-white transition-colors"
                                aria-label="YouTube"
                            >
                                YT
                            </a>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-white font-semibold text-lg mb-6">Tautan Cepat</h3>
                        <ul class="flex flex-col gap-3">
                            <li><Link href="/profil" class="hover:text-indigo-400 transition-colors">Profil Sekolah</Link></li>
                            <li><Link href="/guru-staf" class="hover:text-indigo-400 transition-colors">Guru & Staf</Link></li>
                            <li><Link href="/ekstrakurikuler" class="hover:text-indigo-400 transition-colors">Ekstrakurikuler</Link></li>
                            <li><Link href="/berita" class="hover:text-indigo-400 transition-colors">Berita & Pengumuman</Link></li>
                            <!-- <li><Link href="/galeri" class="hover:text-indigo-400 transition-colors">Galeri Kegiatan</Link></li> -->
                            <li><Link href="/kalender" class="hover:text-indigo-400 transition-colors">Kalender Akademik</Link></li>
                            <li><Link href="/ppdb" class="hover:text-indigo-400 transition-colors">Pendaftaran Siswa Baru</Link></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-white font-semibold text-lg mb-6">Kontak</h3>
                        <ul class="flex flex-col gap-4">
                            <li class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-indigo-400 shrink-0">
                                    <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ settings.address || '-' }}</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-indigo-400 shrink-0">
                                    <path fill-rule="evenodd" d="M1.5 4.5a3 3 0 013-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 01-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 006.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 011.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 01-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ settings.phone || '-' }}</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-indigo-400 shrink-0">
                                    <path d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z" />
                                    <path d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z" />
                                </svg>
                                <span>{{ settings.email || '-' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="pt-8 border-t border-slate-800 text-sm text-center md:text-left flex flex-col md:flex-row justify-between items-center gap-4">
                    <p>&copy; {{ currentYear }} {{ schoolName }}. Hak Cipta Dilindungi.</p>
                    <div class="flex gap-4">
                        <Link href="/login" class="hover:text-white transition-colors">Admin Login</Link>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style>
/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}

/*
 * Nav items: a "chalk underline" marker instead of a filled pill.
 * The subject is a school — the active mark reads like a word underlined
 * on the chalkboard behind the hero. Hover draws the same stroke in.
 */
.nav-item {
    position: relative;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: #334155; /* slate-700 */
    transition: color 0.2s ease;
}

.nav-item::after {
    content: '';
    position: absolute;
    left: 0.75rem;
    right: 0.75rem;
    bottom: 0.3rem;
    height: 3px;
    border-radius: 9999px;
    background: #4f46e5; /* indigo-600 */
    transform: scaleX(0);
    transform-origin: left center;
    transition: transform 0.28s cubic-bezier(0.65, 0, 0.35, 1);
}

.nav-item:hover {
    color: #4338ca; /* indigo-700 */
}

.nav-item:hover::after {
    transform: scaleX(1);
}

.nav-item.is-active {
    color: #0f172a; /* slate-900 */
}

.nav-item.is-active::after {
    transform: scaleX(1);
    background: #4f46e5;
}

@media (prefers-reduced-motion: reduce) {
    .nav-item::after {
        transition: none;
    }
}
</style>
