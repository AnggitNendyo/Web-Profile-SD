<script setup>
import { ref, computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const settings = computed(() => page.props.schoolSettings || {});
const isSidebarOpen = ref(true);
const isMobileSidebarOpen = ref(false);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const toggleMobileSidebar = () => {
    isMobileSidebarOpen.value = !isMobileSidebarOpen.value;
};

const navItems = [
    { name: 'Dashboard',          href: '/admin/dashboard',    icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
    { name: 'Berita',             href: '/admin/berita',       icon: 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15M9 11l3 3m0 0l3-3m-3 3V8' },
    // { name: 'Galeri', href: '/admin/galeri', icon: 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z' },
    { name: 'Guru & Staf',       href: '/admin/guru',         icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z' },
    { name: 'Siswa',             href: '/admin/siswa',        icon: 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z' },
    { name: 'Ekstrakurikuler',   href: '/admin/ekstrakurikuler', icon: 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.562.562 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z' },
    { name: 'PPDB',              href: '/admin/ppdb',         icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' },
    { name: 'Pesan Masuk',       href: '/admin/pesan',        icon: 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75' },
    { name: 'Unduhan',           href: '/admin/unduhan',      icon: 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4' },
    { name: 'Kalender Akademik', href: '/admin/kalender',     icon: 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15z' },
    { name: 'Master Data',       href: '/admin/master-data',  icon: 'M20.25 6.375c0 8.485-7.5 11.25-7.5 11.25S5.25 14.86 5.25 6.375a7.5 7.5 0 0115 0zM12 6.375a.75.75 0 11-1.5 0 .75.75 0 011.5 0z' },
    { name: 'Fasilitas',         href: '/admin/fasilitas',    icon: 'M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21' },
];

if (page.props.auth.user.roles && page.props.auth.user.roles.some(r => r.name === 'super-admin' || r.name === 'operator')) {
    navItems.push(
        { name: 'Halaman', href: '/admin/halaman', icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253' },
        { name: 'Pengaturan PPDB', href: '/admin/ppdb-setting', icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM12 15a3 3 0 100-6 3 3 0 000 6z' },
        { name: 'Pengaturan Sekolah', href: '/admin/pengaturan', icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM12 15a3 3 0 100-6 3 3 0 000 6z' }
    );
}

if (page.props.auth.user.roles && page.props.auth.user.roles.some(r => r.name === 'super-admin')) {
    navItems.push(
        { name: 'Pengguna', href: '/admin/pengguna', icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z' }
    );
}

defineProps({
    title: {
        type: String,
        default: 'Admin Dashboard',
    },
});
</script>

<template>
    <Head :title="title" />

    <div class="min-h-screen bg-slate-50 flex">
        <!-- Sidebar Backdrop (Mobile) -->
        <div v-if="isMobileSidebarOpen" @click="isMobileSidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden"></div>

        <!-- Sidebar -->
        <aside 
            :class="[
                'fixed lg:sticky top-0 left-0 z-50 h-screen bg-slate-900 text-white transition-all duration-300 flex flex-col',
                isSidebarOpen ? 'w-64' : 'w-20',
                isMobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
            ]"
        >
            <!-- Sidebar Header -->
            <div class="h-16 flex items-center justify-between px-4 border-b border-slate-800">
                <div class="flex items-center gap-3 overflow-hidden">
                    <img v-if="settings.logo_path" :src="'/storage/' + settings.logo_path" alt="Logo" class="w-10 h-10 object-contain shrink-0" />
                    <div v-else class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center shrink-0 font-bold">
                        SD
                    </div>
                    <span v-if="isSidebarOpen" class="font-bold whitespace-nowrap">Admin Panel</span>
                </div>
                <button @click="toggleSidebar" class="hidden lg:block text-slate-400 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>

            <!-- Sidebar Navigation -->
            <div class="flex-grow overflow-y-auto py-4 px-3 space-y-1">
                <Link
                    v-for="item in navItems"
                    :key="item.name"
                    :href="item.href"
                    :class="[
                        'flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors group',
                        $page.url.startsWith(item.href) ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'
                    ]"
                    :title="!isSidebarOpen ? item.name : ''"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" :d="item.icon" />
                    </svg>
                    <span v-if="isSidebarOpen" class="whitespace-nowrap">{{ item.name }}</span>
                </Link>
            </div>
            
            <div class="p-4 border-t border-slate-800 text-sm">
                <Link href="/" class="flex items-center gap-2 text-slate-400 hover:text-white transition-colors py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                    </svg>
                    <span v-if="isSidebarOpen">Lihat Website</span>
                </Link>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-grow flex flex-col min-w-0">
            <!-- Topbar -->
            <header class="h-16 bg-white shadow-sm border-b border-slate-200 flex items-center justify-between px-4 sticky top-0 z-30">
                <div class="flex items-center">
                    <button @click="toggleMobileSidebar" class="lg:hidden text-slate-500 hover:text-slate-700 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <h1 class="font-semibold text-slate-800 text-lg hidden sm:block">{{ title }}</h1>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-sm text-right hidden md:block">
                        <div class="font-medium text-slate-900">{{ $page.props.auth.user.name }}</div>
                        <div class="text-slate-500 text-xs">{{ $page.props.auth.user.roles?.[0]?.name || 'Admin' }}</div>
                    </div>
                    
                    <Link href="/logout" method="post" as="button" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-slate-200 hover:text-red-600 transition-colors" title="Logout">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                    </Link>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 md:p-6 flex-grow">
                
                <!-- Flash Messages Placeholder (Optional, can be added globally via root app.js or here) -->
                <div v-if="$page.props.flash?.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ $page.props.flash.success }}</span>
                </div>
                <div v-if="$page.props.flash?.error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ $page.props.flash.error }}</span>
                </div>

                <slot />
            </main>
        </div>
    </div>
</template>
