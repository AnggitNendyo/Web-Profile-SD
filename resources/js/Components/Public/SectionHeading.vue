<script setup>
import { computed } from 'vue';

/**
 * Heading section publik yang seragam: eyebrow + judul (Fraunces) + garis aksen.
 *
 * Signature "buku tulis": eyebrow didahului garis margin merah tipis, dan judul
 * diikuti garis aksen indigo — meniru kata yang digarisi di buku tulis. Merah
 * dipakai sangat hemat (hanya penanda eyebrow); indigo tetap warna utama.
 */
const props = defineProps({
    eyebrow: { type: String, default: '' },
    title: { type: String, required: true },
    align: { type: String, default: 'left' }, // 'left' | 'center'
    light: { type: Boolean, default: false }, // untuk latar gelap
    size: { type: String, default: 'md' }, // 'md' | 'lg'
});

const isCenter = computed(() => props.align === 'center');

const titleClass = computed(() => [
    'font-display font-semibold tracking-tight',
    props.size === 'lg' ? 'text-3xl md:text-4xl lg:text-5xl' : 'text-3xl md:text-4xl',
    props.light ? 'text-white' : 'text-slate-900',
]);

const eyebrowClass = computed(() => [
    'font-semibold tracking-wider uppercase text-sm',
    props.light ? 'text-indigo-200' : 'text-indigo-600',
]);
</script>

<template>
    <div :class="isCenter ? 'text-center' : 'text-left'">
        <!-- Eyebrow dengan penanda garis margin merah -->
        <span
            v-if="eyebrow"
            :class="['inline-flex items-center gap-2 mb-3', ...eyebrowClass]"
        >
            <span class="inline-block h-3.5 w-0.5 rounded-full bg-red-500/80" aria-hidden="true"></span>
            {{ eyebrow }}
        </span>

        <h2 :class="titleClass">
            <slot name="title">{{ title }}</slot>
        </h2>

        <!-- Garis aksen di bawah judul -->
        <div
            :class="[
                'mt-4 h-1 rounded-full bg-indigo-600',
                isCenter ? 'mx-auto w-16' : 'w-16',
            ]"
        ></div>

        <!-- Deskripsi opsional -->
        <p
            v-if="$slots.default"
            :class="[
                'mt-5 text-lg leading-relaxed',
                light ? 'text-slate-200' : 'text-slate-600',
                isCenter ? 'mx-auto max-w-2xl' : 'max-w-2xl',
            ]"
        >
            <slot />
        </p>
    </div>
</template>
