<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
    stats: {
        type: Array,
        required: true,
        // Format: [{ label: 'Siswa', value: 320, icon: '...', suffix: '+' }]
    }
});

const counters = ref([]);
const targetSection = ref(null);
const hasAnimated = ref(false);

const animateCounter = (el, target, duration = 2000) => {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        
        // Easing function: easeOutQuart
        const easeProgress = 1 - Math.pow(1 - progress, 4);
        
        el.innerText = Math.floor(easeProgress * target).toLocaleString('id-ID');
        
        if (progress < 1) {
            window.requestAnimationFrame(step);
        } else {
            el.innerText = target.toLocaleString('id-ID');
        }
    };
    window.requestAnimationFrame(step);
};

onMounted(() => {
    const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting && !hasAnimated.value) {
            hasAnimated.value = true;
            counters.value.forEach((el, index) => {
                if (el) {
                    setTimeout(() => {
                        animateCounter(el, props.stats[index].value);
                    }, index * 100);
                }
            });
        }
    }, { threshold: 0.1 });

    if (targetSection.value) {
        observer.observe(targetSection.value);
    }
});
</script>

<template>
    <section ref="targetSection" class="py-16 bg-white relative -mt-16 z-20 mx-4 md:mx-auto max-w-6xl rounded-2xl shadow-xl border border-slate-100">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-4 px-6 md:px-12 divide-x divide-slate-100">
            <div v-for="(stat, index) in stats" :key="index" class="text-center px-4" data-aos="fade-up" :data-aos-delay="100 * index">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-indigo-50 text-indigo-600 mb-4 shadow-inner">
                    <svg v-if="stat.icon === 'users'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    <svg v-else-if="stat.icon === 'academic-cap'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                    </svg>
                    <svg v-else-if="stat.icon === 'newspaper'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
                
                <div class="flex items-baseline justify-center gap-1 font-bold text-4xl text-slate-800 mb-2">
                    <span :ref="el => counters[index] = el">0</span>
                    <span v-if="stat.suffix" class="text-indigo-600">{{ stat.suffix }}</span>
                </div>
                
                <h3 class="text-slate-500 font-medium tracking-wide uppercase text-sm">{{ stat.label }}</h3>
            </div>
        </div>
    </section>
</template>
