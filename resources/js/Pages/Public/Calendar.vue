<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { computed, ref } from 'vue';

const props = defineProps({
    upcoming: {
        type: Array,
        required: true,
    },
    past: {
        type: Array,
        default: () => [],
    },
    all: {
        type: Array,
        default: () => [],
    },
});

const categoryStyles = {
    Kegiatan: { dot: 'bg-indigo-500', badge: 'bg-indigo-50 text-indigo-700', cell: 'bg-indigo-500' },
    Ujian: { dot: 'bg-amber-500', badge: 'bg-amber-50 text-amber-700', cell: 'bg-amber-500' },
    Libur: { dot: 'bg-rose-500', badge: 'bg-rose-50 text-rose-700', cell: 'bg-rose-500' },
    Pendaftaran: { dot: 'bg-emerald-500', badge: 'bg-emerald-50 text-emerald-700', cell: 'bg-emerald-500' },
    Rapat: { dot: 'bg-sky-500', badge: 'bg-sky-50 text-sky-700', cell: 'bg-sky-500' },
};

const styleFor = (cat) => categoryStyles[cat] || { dot: 'bg-slate-400', badge: 'bg-slate-100 text-slate-600', cell: 'bg-slate-400' };

// ============ Tabs ============
const activeTab = ref('timeline'); // 'timeline' | 'calendar'

// ============ Timeline helpers ============
const dayNum = (date) => new Date(date).toLocaleDateString('id-ID', { day: '2-digit' });
const monthShort = (date) => new Date(date).toLocaleDateString('id-ID', { month: 'short' }).toUpperCase();

const formatRange = (start, end) => {
    const opts = { day: 'numeric', month: 'long', year: 'numeric' };
    const s = new Date(start).toLocaleDateString('id-ID', opts);
    if (!end || end === start) return s;
    return `${new Date(start).toLocaleDateString('id-ID', { day: 'numeric', month: 'long' })} – ${new Date(end).toLocaleDateString('id-ID', opts)}`;
};

const isEmpty = computed(() => props.upcoming.length === 0 && props.past.length === 0);

// ============ Calendar grid ============
const toKey = (d) => `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
const parseDate = (s) => {
    // s = 'YYYY-MM-DD...' -> local date at midnight
    const [y, m, d] = s.substring(0, 10).split('-').map(Number);
    return new Date(y, m - 1, d);
};

const today = new Date();
const todayKey = toKey(today);

// Titik fokus kalender: bulan dari agenda mendatang terdekat, jika tidak ada pakai bulan ini.
const initialFocus = props.upcoming.length > 0 ? parseDate(props.upcoming[0].start_date) : today;
const viewYear = ref(initialFocus.getFullYear());
const viewMonth = ref(initialFocus.getMonth()); // 0-11

const monthLabel = computed(() =>
    new Date(viewYear.value, viewMonth.value, 1).toLocaleDateString('id-ID', { month: 'long', year: 'numeric' })
);

const weekDays = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

// Petakan setiap tanggal (key) -> array event yang menutupinya.
const eventsByDay = computed(() => {
    const map = {};
    for (const ev of props.all) {
        const start = parseDate(ev.start_date);
        const end = ev.end_date ? parseDate(ev.end_date) : start;
        const cursor = new Date(start);
        // Batasi loop agar aman (maks ~366 hari).
        let guard = 0;
        while (cursor <= end && guard < 400) {
            const key = toKey(cursor);
            (map[key] ||= []).push(ev);
            cursor.setDate(cursor.getDate() + 1);
            guard++;
        }
    }
    return map;
});

// Susun sel grid: awali dengan padding hari kosong dari minggu sebelumnya.
const calendarCells = computed(() => {
    const firstOfMonth = new Date(viewYear.value, viewMonth.value, 1);
    const daysInMonth = new Date(viewYear.value, viewMonth.value + 1, 0).getDate();
    const leadingBlanks = firstOfMonth.getDay(); // 0=Min

    const cells = [];
    for (let i = 0; i < leadingBlanks; i++) {
        cells.push({ blank: true, key: `blank-${i}` });
    }
    for (let d = 1; d <= daysInMonth; d++) {
        const date = new Date(viewYear.value, viewMonth.value, d);
        const key = toKey(date);
        cells.push({
            blank: false,
            key,
            day: d,
            isToday: key === todayKey,
            events: eventsByDay.value[key] || [],
        });
    }
    return cells;
});

const goPrevMonth = () => {
    if (viewMonth.value === 0) {
        viewMonth.value = 11;
        viewYear.value--;
    } else {
        viewMonth.value--;
    }
};

const goNextMonth = () => {
    if (viewMonth.value === 11) {
        viewMonth.value = 0;
        viewYear.value++;
    } else {
        viewMonth.value++;
    }
};

const goToday = () => {
    viewYear.value = today.getFullYear();
    viewMonth.value = today.getMonth();
    selectedDay.value = null;
};

// Sel yang dipilih -> tampilkan detail agenda di bawah grid.
const selectedDay = ref(null);
const selectDay = (cell) => {
    if (cell.blank || cell.events.length === 0) {
        selectedDay.value = null;
        return;
    }
    selectedDay.value = cell;
};

const selectedLabel = computed(() => {
    if (!selectedDay.value) return '';
    return new Date(viewYear.value, viewMonth.value, selectedDay.value.day)
        .toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
});
</script>

<template>
    <PublicLayout title="Kalender Akademik - SD Negeri">
        <!-- Hero -->
        <section class="bg-indigo-900 pt-32 pb-20 relative overflow-hidden">
            <div v-if="$page.props.schoolSettings?.banner_calendar" class="absolute inset-0 z-0">
                <img :src="`/storage/${$page.props.schoolSettings.banner_calendar}`" alt="Banner" class="w-full h-full object-cover opacity-20 mix-blend-overlay" />
            </div>
            <div v-else class="absolute inset-0 z-0">
                <div class="absolute w-full h-full bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCI+CjxjaXJjbGUgY3g9IjIiIGN5PSIyIiByPSIyIiBmaWxsPSIjZmZmZmZmIi8+Cjwvc3ZnPg==')] opacity-10"></div>
            </div>
            <div class="container mx-auto px-4 md:px-6 relative z-10 text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Kalender Akademik</h1>
                <p class="text-indigo-100 text-lg max-w-2xl mx-auto">
                    Jadwal kegiatan, ujian, hari libur, dan agenda penting sepanjang tahun ajaran.
                </p>
            </div>
        </section>

        <section class="py-16 bg-slate-50 min-h-[50vh]">
            <div class="container mx-auto px-4 md:px-6">
                <div class="max-w-3xl mx-auto">

                    <!-- Tab switcher -->
                    <div class="flex justify-center mb-10">
                        <div class="inline-flex items-center gap-1 rounded-full bg-white ring-1 ring-slate-200 shadow-sm p-1.5">
                            <button
                                @click="activeTab = 'timeline'"
                                :class="['flex items-center gap-2 px-5 py-2 rounded-full text-sm font-semibold transition-all', activeTab === 'timeline' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:text-indigo-600']"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
                                </svg>
                                Linimasa
                            </button>
                            <button
                                @click="activeTab = 'calendar'"
                                :class="['flex items-center gap-2 px-5 py-2 rounded-full text-sm font-semibold transition-all', activeTab === 'calendar' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:text-indigo-600']"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                Kalender
                            </button>
                        </div>
                    </div>

                    <!-- Empty state (shared) -->
                    <div v-if="isEmpty" class="text-center py-20 bg-white rounded-2xl border border-slate-100 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-slate-300 mx-auto mb-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        <h3 class="text-xl font-bold text-slate-700 mb-2">Belum ada agenda</h3>
                        <p class="text-slate-500">Jadwal akademik akan ditampilkan di sini setelah ditambahkan.</p>
                    </div>

                    <!-- ============ TAB: TIMELINE ============ -->
                    <template v-else-if="activeTab === 'timeline'">
                        <!-- Upcoming -->
                        <div v-if="upcoming.length > 0" class="mb-14">
                            <div class="flex items-center gap-3 mb-8">
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-600 animate-pulse"></span>
                                <h2 class="text-xl font-bold text-slate-900">Agenda Mendatang</h2>
                            </div>

                            <ol class="relative border-l-2 border-slate-200 ml-4 space-y-8">
                                <li v-for="event in upcoming" :key="event.id" class="ml-8 relative">
                                    <span class="absolute -left-[3.15rem] top-0 flex flex-col items-center justify-center w-16 h-16 rounded-xl bg-white shadow-md border border-slate-100">
                                        <span class="text-lg font-extrabold text-slate-900 leading-none">{{ dayNum(event.start_date) }}</span>
                                        <span class="text-[0.65rem] font-semibold text-indigo-600 tracking-wider">{{ monthShort(event.start_date) }}</span>
                                    </span>
                                    <span :class="['absolute -left-[0.55rem] top-6 w-3.5 h-3.5 rounded-full ring-4 ring-slate-50', styleFor(event.category).dot]"></span>

                                    <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 hover:shadow-md transition-shadow">
                                        <div class="flex items-start justify-between gap-3 mb-1">
                                            <h3 class="font-bold text-slate-800 text-lg">{{ event.title }}</h3>
                                            <span v-if="event.category" :class="['shrink-0 px-2.5 py-1 text-xs font-semibold rounded-full', styleFor(event.category).badge]">
                                                {{ event.category }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-slate-500 mb-2">{{ formatRange(event.start_date, event.end_date) }}</p>
                                        <p v-if="event.description" class="text-slate-600 leading-relaxed">{{ event.description }}</p>
                                    </div>
                                </li>
                            </ol>
                        </div>

                        <!-- Past -->
                        <div v-if="past.length > 0">
                            <div class="flex items-center gap-3 mb-8">
                                <span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>
                                <h2 class="text-xl font-bold text-slate-400">Telah Berlangsung</h2>
                            </div>

                            <ul class="space-y-3">
                                <li v-for="event in past" :key="event.id" class="flex items-center gap-4 bg-white/60 rounded-lg border border-slate-100 px-4 py-3">
                                    <span :class="['w-2 h-2 rounded-full shrink-0 opacity-60', styleFor(event.category).dot]"></span>
                                    <div class="flex-grow min-w-0">
                                        <span class="font-semibold text-slate-500">{{ event.title }}</span>
                                    </div>
                                    <span class="text-sm text-slate-400 whitespace-nowrap">{{ formatRange(event.start_date, event.end_date) }}</span>
                                </li>
                            </ul>
                        </div>
                    </template>

                    <!-- ============ TAB: CALENDAR ============ -->
                    <template v-else>
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sm:p-6">
                            <!-- Month nav -->
                            <div class="flex items-center justify-between mb-6">
                                <button @click="goPrevMonth" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-indigo-600 transition-colors" title="Bulan sebelumnya">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                    </svg>
                                </button>

                                <div class="text-center">
                                    <h2 class="text-lg font-bold text-slate-900 capitalize">{{ monthLabel }}</h2>
                                    <button @click="goToday" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">Ke hari ini</button>
                                </div>

                                <button @click="goNextMonth" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-indigo-600 transition-colors" title="Bulan berikutnya">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Weekday header -->
                            <div class="grid grid-cols-7 gap-1 mb-1">
                                <div v-for="wd in weekDays" :key="wd" class="text-center text-xs font-semibold text-slate-400 py-2">
                                    {{ wd }}
                                </div>
                            </div>

                            <!-- Day grid -->
                            <div class="grid grid-cols-7 gap-1">
                                <template v-for="cell in calendarCells" :key="cell.key">
                                    <div v-if="cell.blank" class="aspect-square"></div>
                                    <button
                                        v-else
                                        @click="selectDay(cell)"
                                        :class="[
                                            'aspect-square rounded-lg p-1 flex flex-col items-center transition-all relative',
                                            cell.events.length > 0 ? 'hover:bg-indigo-50 cursor-pointer' : 'cursor-default',
                                            selectedDay?.key === cell.key ? 'ring-2 ring-indigo-500 bg-indigo-50' : '',
                                        ]"
                                    >
                                        <span
                                            :class="[
                                                'text-sm font-semibold w-7 h-7 flex items-center justify-center rounded-full',
                                                cell.isToday ? 'bg-indigo-600 text-white' : 'text-slate-700',
                                            ]"
                                        >
                                            {{ cell.day }}
                                        </span>
                                        <!-- Event dots -->
                                        <div v-if="cell.events.length > 0" class="flex flex-wrap gap-0.5 justify-center mt-0.5">
                                            <span
                                                v-for="(ev, i) in cell.events.slice(0, 3)"
                                                :key="i"
                                                :class="['w-1.5 h-1.5 rounded-full', styleFor(ev.category).cell]"
                                            ></span>
                                        </div>
                                    </button>
                                </template>
                            </div>

                            <!-- Legend -->
                            <div class="flex flex-wrap gap-x-4 gap-y-2 mt-6 pt-4 border-t border-slate-100">
                                <div v-for="(style, cat) in categoryStyles" :key="cat" class="flex items-center gap-1.5">
                                    <span :class="['w-2.5 h-2.5 rounded-full', style.cell]"></span>
                                    <span class="text-xs text-slate-500">{{ cat }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Selected day detail -->
                        <div v-if="selectedDay" class="mt-6 bg-white rounded-xl border border-slate-100 shadow-sm p-5">
                            <h3 class="font-bold text-slate-900 mb-4 capitalize">{{ selectedLabel }}</h3>
                            <ul class="space-y-3">
                                <li v-for="event in selectedDay.events" :key="event.id" class="flex items-start gap-3">
                                    <span :class="['w-2.5 h-2.5 rounded-full shrink-0 mt-1.5', styleFor(event.category).dot]"></span>
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="font-semibold text-slate-800">{{ event.title }}</span>
                                            <span v-if="event.category" :class="['px-2 py-0.5 text-xs font-semibold rounded-full', styleFor(event.category).badge]">
                                                {{ event.category }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ formatRange(event.start_date, event.end_date) }}</p>
                                        <p v-if="event.description" class="text-sm text-slate-600 mt-1">{{ event.description }}</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <p v-else class="text-center text-sm text-slate-400 mt-6">
                            Klik tanggal yang bertanda untuk melihat detail agenda.
                        </p>
                    </template>

                </div>
            </div>
        </section>
    </PublicLayout>
</template>
