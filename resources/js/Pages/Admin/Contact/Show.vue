<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';
import { format, parseISO } from 'date-fns';
import { id } from 'date-fns/locale';

const props = defineProps({
    message: { type: Object, required: true },
});

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return format(parseISO(dateString), 'dd MMMM yyyy, HH:mm', { locale: id });
};
</script>

<template>
    <AdminLayout title="Detail Pesan">
        <!-- Back Button -->
        <div class="mb-6 flex items-center justify-between">
            <Link href="/admin/pesan" class="text-sm font-medium text-slate-500 hover:text-indigo-600 flex items-center gap-1 transition-colors w-fit">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar Pesan
            </Link>

            <span :class="[
                'px-3 py-1.5 rounded-full text-xs font-semibold border',
                message.is_read 
                    ? 'bg-slate-100 text-slate-600 border-slate-200' 
                    : 'bg-indigo-100 text-indigo-700 border-indigo-200'
            ]">
                {{ message.is_read ? 'Sudah Dibaca' : 'Baru' }}
            </span>
        </div>

        <div class="max-w-4xl space-y-6">
            <!-- Message Header -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h2 class="font-bold text-lg text-slate-800">{{ message.subject }}</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <!-- Avatar -->
                            <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-lg shrink-0">
                                {{ message.name.charAt(0).toUpperCase() }}
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900">{{ message.name }}</h3>
                                <a :href="`mailto:${message.email}`" class="text-sm text-indigo-600 hover:text-indigo-800 transition-colors">
                                    {{ message.email }}
                                </a>
                            </div>
                        </div>
                        <div class="text-right text-sm text-slate-500 shrink-0">
                            <p>{{ formatDate(message.created_at) }}</p>
                        </div>
                    </div>

                    <!-- Message Body -->
                    <div class="bg-slate-50 rounded-xl p-6 border border-slate-100">
                        <p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ message.message }}</p>
                    </div>

                    <!-- Action buttons -->
                    <div class="mt-6 flex flex-wrap gap-3 pt-6 border-t border-slate-100">
                        <a 
                            :href="`mailto:${message.email}?subject=Re: ${message.subject}`" 
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition-colors shadow-sm"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                            </svg>
                            Balas via Email
                        </a>
                        <a 
                            :href="`https://wa.me/?text=Yth. ${message.name}, terima kasih telah menghubungi kami mengenai: ${message.subject}`" 
                            target="_blank" 
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 text-white text-sm font-semibold hover:bg-green-700 transition-colors shadow-sm"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            Balas via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
