<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    settings: {
        type: Object,
        required: true,
    }
});

const form = useForm({
    name: '',
    email: '',
    subject: '',
    message: '',
});

const submit = () => {
    form.post('/kontak', {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <PublicLayout title="Hubungi Kami - SD Negeri">
        <section class="bg-indigo-900 pt-32 pb-24 relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute inset-0 z-0 opacity-20">
                <div class="absolute w-full h-full bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-indigo-500 via-indigo-900 to-slate-900"></div>
            </div>
            
            <div class="container mx-auto px-4 md:px-6 relative z-10 text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Hubungi Kami</h1>
                <p class="text-indigo-100 text-lg max-w-2xl mx-auto">
                    Kami siap melayani dan menjawab pertanyaan Anda. Jangan ragu untuk menghubungi kami melalui kontak di bawah ini.
                </p>
            </div>
        </section>

        <section class="py-16 md:py-24 bg-slate-50 relative -mt-16">
            <div class="container mx-auto px-4 md:px-6">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 max-w-6xl mx-auto">
                    <!-- Contact Info -->
                    <div class="lg:col-span-5 space-y-6">
                        
                        <!-- Cards -->
                        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 flex items-start gap-5 hover:shadow-md transition-shadow">
                            <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Alamat</h3>
                                <p class="text-slate-600 leading-relaxed">{{ settings.address }}</p>
                            </div>
                        </div>

                        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 flex items-start gap-5 hover:shadow-md transition-shadow">
                            <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.864-1.068l-3.268-.636a2.25 2.25 0 00-2.072.56l-1.92 1.92A15.343 15.343 0 011.5 11.25l1.92-1.92c.49-.49.52-1.22.06-1.74l-2.08-2.08A2.25 2.25 0 00.322 6.136L2.25 6.75z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Telepon & WhatsApp</h3>
                                <p class="text-slate-600 text-lg font-medium">{{ settings.phone }}</p>
                            </div>
                        </div>

                        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 flex items-start gap-5 hover:shadow-md transition-shadow">
                            <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Email</h3>
                                <p class="text-slate-600 text-lg font-medium">{{ settings.email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="lg:col-span-7">
                        <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12 border border-slate-100">
                            
                            <h2 class="text-2xl font-bold text-slate-900 mb-2">Kirim Pesan</h2>
                            <p class="text-slate-500 mb-8 pb-6 border-b border-slate-100">Gunakan form di bawah ini untuk mengirimkan pertanyaan, kritik, atau saran kepada kami.</p>

                            <!-- Success Message -->
                            <div v-if="$page.props.flash?.success" class="mb-8 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 shrink-0 text-green-500 mt-0.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <h4 class="font-bold">Berhasil Terkirim!</h4>
                                    <p class="text-sm">{{ $page.props.flash.success }}</p>
                                </div>
                            </div>

                            <form @submit.prevent="submit" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                                        <input 
                                            id="name" 
                                            v-model="form.name" 
                                            type="text" 
                                            required
                                            class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors py-3"
                                        >
                                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Alamat Email</label>
                                        <input 
                                            id="email" 
                                            v-model="form.email" 
                                            type="email" 
                                            required
                                            class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors py-3"
                                        >
                                        <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                                    </div>
                                </div>

                                <div>
                                    <label for="subject" class="block text-sm font-semibold text-slate-700 mb-2">Subjek / Keperluan</label>
                                    <input 
                                        id="subject" 
                                        v-model="form.subject" 
                                        type="text" 
                                        required
                                        class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors py-3"
                                    >
                                    <p v-if="form.errors.subject" class="mt-1 text-sm text-red-600">{{ form.errors.subject }}</p>
                                </div>

                                <div>
                                    <label for="message" class="block text-sm font-semibold text-slate-700 mb-2">Isi Pesan</label>
                                    <textarea 
                                        id="message" 
                                        v-model="form.message" 
                                        rows="5" 
                                        required
                                        class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors"
                                    ></textarea>
                                    <p v-if="form.errors.message" class="mt-1 text-sm text-red-600">{{ form.errors.message }}</p>
                                </div>

                                <div>
                                    <button 
                                        type="submit" 
                                        :disabled="form.processing"
                                        class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-xl bg-indigo-600 py-3.5 px-8 text-base font-semibold text-white shadow-md hover:bg-indigo-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition-all duration-300"
                                    >
                                        <svg v-if="!form.processing" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                        </svg>
                                        <svg v-else class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ form.processing ? 'Mengirim...' : 'Kirim Pesan' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Map -->
                <div v-if="settings.map_embed_url" class="mt-16 bg-white p-4 rounded-2xl shadow-sm border border-slate-100 h-[500px] w-full max-w-6xl mx-auto">
                    <iframe 
                        :src="settings.map_embed_url" 
                        class="w-full h-full rounded-xl" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

            </div>
        </section>
    </PublicLayout>
</template>
