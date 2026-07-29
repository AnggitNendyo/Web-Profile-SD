<script setup>
import { onMounted, onUnmounted, ref, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    image: {
        type: String,
        default: '',
    },
    title: {
        type: String,
        default: '',
    },
    // 'foto' | 'video' — menentukan apakah menampilkan <img> atau <video>
    type: {
        type: String,
        default: 'foto',
    },
});

const emit = defineEmits(['close']);

const close = () => {
    emit('close');
};

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

watch(
    () => props.show,
    () => {
        document.body.style.overflow = props.show ? 'hidden' : null;
    }
);
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="ease-out duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-show="show" class="fixed inset-0 z-[60] flex items-center justify-center p-4">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-slate-900/95 backdrop-blur-sm" @click="close"></div>

                <!-- Close button -->
                <button @click="close" class="absolute top-4 right-4 z-[70] p-2 text-white/70 hover:text-white transition-colors bg-white/10 hover:bg-white/20 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Content -->
                <div class="relative z-[60] max-w-5xl w-full max-h-[90vh] flex flex-col items-center">
                    <Transition
                        enter-active-class="ease-out duration-300"
                        enter-from-class="opacity-0 scale-95"
                        enter-to-class="opacity-100 scale-100"
                        leave-active-class="ease-in duration-200"
                        leave-from-class="opacity-100 scale-100"
                        leave-to-class="opacity-0 scale-95"
                    >
                        <div v-show="show" class="w-full flex flex-col items-center gap-4">
                            <!-- Video: disematkan via iframe YouTube -->
                            <div v-if="type === 'video' && show" class="w-full max-w-4xl aspect-video">
                                <iframe
                                    :src="`${image}?autoplay=1&rel=0`"
                                    class="w-full h-full rounded-lg shadow-2xl bg-black"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen
                                ></iframe>
                            </div>
                            <!-- Foto -->
                            <img v-else :src="image" :alt="title" class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl" />

                            <div v-if="title" class="text-white text-center">
                                <h3 class="text-lg font-medium">{{ title }}</h3>
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
