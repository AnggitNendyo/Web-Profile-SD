<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataTable from '@/Components/Admin/DataTable.vue';
import ConfirmModal from '@/Components/Admin/ConfirmModal.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    users: {
        type: Object,
        required: true,
    },
    roles: {
        type: Array,
        required: true,
    }
});

const page = usePage();
const currentUser = page.props.auth.user;

const columns = [
    { key: 'name', label: 'Nama Lengkap' },
    { key: 'email', label: 'Alamat Email' },
    { key: 'role_name', label: 'Hak Akses' },
];

const showDeleteModal = ref(false);
const showAddModal = ref(false);
const itemToDelete = ref(null);
const isEditing = ref(false);
const editId = ref(null);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: '',
});

const deleteForm = useForm({});

const openAddModal = () => {
    isEditing.value = false;
    form.reset();
    showAddModal.value = true;
};

const openEditModal = (item) => {
    isEditing.value = true;
    editId.value = item.id;
    form.name = item.name;
    form.email = item.email;
    form.password = ''; // empty password on edit
    form.password_confirmation = '';
    form.role = item.roles && item.roles.length > 0 ? item.roles[0].name : '';
    showAddModal.value = true;
};

const submitForm = () => {
    if (isEditing.value) {
        form.put(`/admin/pengguna/${editId.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                showAddModal.value = false;
            }
        });
    } else {
        form.post('/admin/pengguna', {
            preserveScroll: true,
            onSuccess: () => {
                showAddModal.value = false;
                form.reset();
            }
        });
    }
};

const confirmDelete = (item) => {
    itemToDelete.value = item;
    showDeleteModal.value = true;
};

const deleteItem = () => {
    deleteForm.delete(`/admin/pengguna/${itemToDelete.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteModal.value = false;
            itemToDelete.value = null;
        }
    });
};
</script>

<template>
    <AdminLayout title="Manajemen Pengguna">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <p class="text-slate-500 text-sm">Kelola akun administrator, operator, dan guru.</p>
            </div>
            <button @click="openAddModal" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                </svg>
                Tambah Pengguna
            </button>
        </div>

        <DataTable :columns="columns" :data="users">
            <!-- Custom Cell: Name -->
            <template #cell-name="{ item }">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs uppercase">
                        {{ item.name.substring(0, 2) }}
                    </div>
                    <div class="font-bold text-slate-800">
                        {{ item.name }}
                        <span v-if="item.id === currentUser.id" class="ml-2 text-xs font-medium text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">Anda</span>
                    </div>
                </div>
            </template>

            <!-- Custom Cell: Role -->
            <template #cell-role_name="{ item }">
                <span v-if="item.roles && item.roles.length > 0" class="px-2.5 py-1 text-xs font-semibold rounded-full border"
                    :class="{
                        'bg-purple-100 text-purple-700 border-purple-200': item.roles[0].name === 'super-admin',
                        'bg-blue-100 text-blue-700 border-blue-200': item.roles[0].name === 'operator',
                        'bg-slate-100 text-slate-700 border-slate-200': item.roles[0].name === 'guru',
                    }"
                >
                    {{ item.roles[0].name }}
                </span>
                <span v-else class="text-xs text-slate-400 italic">Belum ada hak akses</span>
            </template>

            <!-- Actions -->
            <template #actions="{ item }">
                <button @click="openEditModal(item)" class="p-1.5 text-slate-400 hover:text-blue-600 transition-colors bg-slate-50 hover:bg-blue-50 rounded" title="Edit Pengguna">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                    </svg>
                </button>
                <button v-if="item.id !== currentUser.id" @click="confirmDelete(item)" class="p-1.5 text-slate-400 hover:text-red-600 transition-colors bg-slate-50 hover:bg-red-50 rounded ml-1" title="Hapus Pengguna">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </button>
            </template>
        </DataTable>

        <!-- Form Modal -->
        <Teleport to="body">
            <div v-if="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showAddModal = false"></div>
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg relative z-10 overflow-hidden flex flex-col max-h-[90vh]">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                        <h3 class="font-bold text-slate-800 text-lg">{{ isEditing ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}</h3>
                        <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <form @submit.prevent="submitForm" class="overflow-y-auto p-6 space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input v-model="form.name" type="text" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm" placeholder="Contoh: Budi Santoso">
                            <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat Email <span class="text-red-500">*</span></label>
                            <input v-model="form.email" type="email" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm" placeholder="Contoh: budi@sekolah.id">
                            <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Hak Akses (Role) <span class="text-red-500">*</span></label>
                            <select v-model="form.role" required class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm">
                                <option value="" disabled>Pilih Role...</option>
                                <option v-for="role in roles" :key="role.name" :value="role.name">{{ role.name }}</option>
                            </select>
                            <p v-if="form.errors.role" class="mt-1 text-sm text-red-600">{{ form.errors.role }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Password <span v-if="!isEditing" class="text-red-500">*</span></label>
                                <input v-model="form.password" type="password" :required="!isEditing" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm" placeholder="Minimal 8 karakter">
                                <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}</p>
                                <p v-if="isEditing" class="mt-1 text-xs text-slate-500">Kosongkan jika tidak ingin mengubah password.</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Konfirmasi Password <span v-if="!isEditing" class="text-red-500">*</span></label>
                                <input v-model="form.password_confirmation" type="password" :required="!isEditing || form.password.length > 0" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors text-sm" placeholder="Ketik ulang password">
                            </div>
                        </div>
                        
                        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                            <button type="button" @click="showAddModal = false" class="px-4 py-2 bg-white text-slate-700 font-medium text-sm rounded-lg border border-slate-300 hover:bg-slate-50 transition-colors">Batal</button>
                            <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50 flex items-center gap-2">
                                <svg v-if="form.processing" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Teleport>

        <ConfirmModal 
            :show="showDeleteModal"
            title="Hapus Pengguna"
            :message="`Apakah Anda yakin ingin menghapus pengguna '${itemToDelete?.name}'? Tindakan ini tidak dapat dibatalkan.`"
            @close="showDeleteModal = false"
            @confirm="deleteItem"
        />
    </AdminLayout>
</template>
