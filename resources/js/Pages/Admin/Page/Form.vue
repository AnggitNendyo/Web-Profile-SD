<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps({
    page: {
        type: Object,
        required: true,
    }
});

const form = useForm({
    title: props.page.title || '',
    content: props.page.content || '',
});

const submit = () => {
    form.put(`/admin/halaman/${props.page.slug}`);
};
</script>

<template>
    <AdminLayout title="Edit Halaman">
        <div class="mb-6 flex justify-between items-center">
            <Link href="/admin/halaman" class="text-sm font-medium text-slate-500 hover:text-indigo-600 flex items-center gap-1 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </Link>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <form @submit.prevent="submit" class="p-6 md:p-8">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-500 mb-1">URL (Slug) - Tidak dapat diubah</label>
                    <div class="px-4 py-2 bg-slate-100 rounded-lg text-slate-600 font-mono text-sm border border-slate-200 w-fit">
                        {{ page.slug }}
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-semibold text-slate-700 mb-1">Judul Halaman <span class="text-red-500">*</span></label>
                        <input 
                            id="title" 
                            v-model="form.title" 
                            type="text" 
                            required
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-lg"
                        >
                        <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">{{ form.errors.title }}</p>
                    </div>
                    
                    <div class="flex flex-col min-h-[500px]">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Konten Utama <span class="text-red-500">*</span></label>
                        <div class="flex-grow border border-slate-300 rounded-lg overflow-hidden flex flex-col">
                            <QuillEditor 
                                v-model:content="form.content" 
                                contentType="html" 
                                toolbar="full"
                                class="flex-grow bg-white min-h-[400px]" 
                            />
                        </div>
                        <p v-if="form.errors.content" class="mt-1 text-sm text-red-600">{{ form.errors.content }}</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-8 mt-8 border-t border-slate-100 flex justify-end">
                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="inline-flex justify-center items-center gap-2 rounded-lg bg-indigo-600 py-3 px-8 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition-all"
                    >
                        <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<style>
.ql-toolbar.ql-snow {
    border-color: #cbd5e1 !important;
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
    background-color: #f8fafc;
}
.ql-container.ql-snow {
    border-color: #cbd5e1 !important;
    border-bottom-left-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
    font-family: inherit !important;
    font-size: 1rem !important;
}
</style>
