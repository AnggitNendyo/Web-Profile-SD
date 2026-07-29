<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps({
    news: {
        type: Object,
        default: null,
    }
});

const isEditing = computed(() => !!props.news);

const form = useForm({
    title: props.news?.title || '',
    category: props.news?.category || '',
    content: props.news?.content || '',
    published_at: props.news?.published_at ? props.news.published_at.substring(0, 16) : '',
    thumbnail: null,
    video_url: props.news?.video_url ? `https://www.youtube.com/watch?v=${props.news.video_url}` : '',
    _method: isEditing.value ? 'PUT' : 'POST',
});

// Preview thumbnail YouTube langsung di form.
const newsYoutubeId = computed(() => {
    const match = form.video_url.match(
        /(?:youtube\.com\/(?:watch\?(?:.*&)?v=|embed\/|shorts\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/
    );
    if (match) return match[1];
    if (/^[A-Za-z0-9_-]{11}$/.test(form.video_url.trim())) return form.video_url.trim();
    return null;
});

const fileInput = ref(null);
const imagePreview = ref(props.news?.thumbnail ? `/storage/${props.news.thumbnail}` : null);

const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.thumbnail = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const submit = () => {
    // If not editing, we don't need _method for FormData via Inertia
    if (!isEditing.value) {
        delete form._method;
    }
    
    // Format published_at to full datetime if provided
    if (form.published_at && form.published_at.length === 16) {
        form.published_at = form.published_at + ':00';
    }

    if (isEditing.value) {
        // We use POST with _method=PUT for file uploads in Laravel
        form.post(`/admin/berita/${props.news.id}`);
    } else {
        form.post('/admin/berita');
    }
};
</script>

<template>
    <AdminLayout :title="isEditing ? 'Edit Berita' : 'Tulis Berita'">
        <div class="mb-6 flex justify-between items-center">
            <Link href="/admin/berita" class="text-sm font-medium text-slate-500 hover:text-indigo-600 flex items-center gap-1 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </Link>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <form @submit.prevent="submit" class="p-6 md:p-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Form Area -->
                    <div class="lg:col-span-2 space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-semibold text-slate-700 mb-1">Judul Berita <span class="text-red-500">*</span></label>
                            <input 
                                id="title" 
                                v-model="form.title" 
                                type="text" 
                                required
                                class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-lg"
                                placeholder="Masukkan judul berita..."
                            >
                            <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">{{ form.errors.title }}</p>
                        </div>
                        
                        <div class="flex flex-col h-full min-h-[400px]">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Konten <span class="text-red-500">*</span></label>
                            <div class="flex-grow border border-slate-300 rounded-lg overflow-hidden flex flex-col">
                                <QuillEditor 
                                    v-model:content="form.content" 
                                    contentType="html" 
                                    toolbar="essential"
                                    class="flex-grow bg-white min-h-[350px]" 
                                    placeholder="Tulis isi berita di sini..."
                                />
                            </div>
                            <p v-if="form.errors.content" class="mt-1 text-sm text-red-600">{{ form.errors.content }}</p>
                        </div>
                    </div>

                    <!-- Sidebar Area -->
                    <div class="space-y-6">
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200">
                            <h3 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">Pengaturan Publikasi</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="category" class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                                    <input 
                                        id="category" 
                                        v-model="form.category" 
                                        type="text" 
                                        class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm"
                                        placeholder="Contoh: Pengumuman, Prestasi"
                                    >
                                    <p v-if="form.errors.category" class="mt-1 text-sm text-red-600">{{ form.errors.category }}</p>
                                </div>
                                
                                <div>
                                    <label for="published_at" class="block text-sm font-medium text-slate-700 mb-1">Jadwal Publikasi</label>
                                    <input 
                                        id="published_at" 
                                        v-model="form.published_at" 
                                        type="datetime-local" 
                                        class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm"
                                    >
                                    <p class="text-xs text-slate-500 mt-1">Kosongkan jika ingin menyimpan sebagai draft.</p>
                                    <p v-if="form.errors.published_at" class="mt-1 text-sm text-red-600">{{ form.errors.published_at }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200">
                            <h3 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">Gambar Utama</h3>
                            
                            <div class="space-y-4">
                                <div v-if="imagePreview" class="w-full h-40 bg-slate-200 rounded-lg overflow-hidden border border-slate-300 relative group">
                                    <img :src="imagePreview" class="w-full h-full object-cover" />
                                    <div class="absolute inset-0 bg-slate-900/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button type="button" @click="$refs.fileInput.click()" class="px-3 py-1.5 bg-white text-slate-800 text-xs font-semibold rounded-lg shadow">Ganti Gambar</button>
                                    </div>
                                </div>
                                <div v-else @click="$refs.fileInput.click()" class="w-full h-40 bg-white border-2 border-dashed border-slate-300 rounded-lg flex flex-col items-center justify-center cursor-pointer hover:bg-slate-50 transition-colors text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 mb-2 text-slate-400">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                    <span class="text-sm font-medium">Klik untuk upload gambar</span>
                                </div>
                                
                                <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="handleImageChange">
                                <p v-if="form.errors.thumbnail" class="text-sm text-red-600">{{ form.errors.thumbnail }}</p>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200">
                            <h3 class="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">Video YouTube</h3>

                            <div class="space-y-3">
                                <div>
                                    <label for="video_url" class="block text-sm font-medium text-slate-700 mb-1">URL Video (Opsional)</label>
                                    <input
                                        id="video_url"
                                        v-model="form.video_url"
                                        type="text"
                                        class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm"
                                        placeholder="https://www.youtube.com/watch?v=..."
                                    >
                                    <p class="text-xs text-slate-500 mt-1">Video tampil di atas artikel. Disematkan dari YouTube, tidak menambah storage.</p>
                                    <p v-if="form.errors.video_url" class="mt-1 text-sm text-red-600">{{ form.errors.video_url }}</p>
                                </div>

                                <div v-if="newsYoutubeId" class="rounded-lg overflow-hidden border border-slate-200 relative aspect-video bg-slate-900">
                                    <img :src="`https://img.youtube.com/vi/${newsYoutubeId}/hqdefault.jpg`" class="w-full h-full object-cover" />
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-12 h-12 bg-red-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 ml-0.5">
                                                <path fill-rule="evenodd" d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4 flex justify-end">
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="w-full inline-flex justify-center items-center gap-2 rounded-lg bg-indigo-600 py-3 px-4 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition-all"
                            >
                                <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                </svg>
                                {{ form.processing ? 'Menyimpan...' : (isEditing ? 'Simpan Perubahan' : 'Simpan Berita') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<style>
/* Adjust Quill Editor to match Tailwind sizing */
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
