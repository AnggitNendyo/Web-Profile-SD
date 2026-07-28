<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    columns: {
        type: Array,
        required: true,
        // Format: [{ key: 'name', label: 'Nama' }, ...]
    },
    data: {
        type: Object, // Laravel paginator object expected
        required: true,
    },
    actions: {
        type: Boolean,
        default: true,
    }
});
</script>

<template>
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-500">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-6 py-3 w-10">No</th>
                        <th v-for="col in columns" :key="col.key" scope="col" class="px-6 py-3">
                            {{ col.label }}
                        </th>
                        <th v-if="actions" scope="col" class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in data.data" :key="item.id || index" class="bg-white border-b hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            {{ data.from + index }}
                        </td>
                        <td v-for="col in columns" :key="col.key" class="px-6 py-4">
                            <!-- Slot for custom cell rendering, fallback to normal value -->
                            <slot :name="`cell-${col.key}`" :item="item">
                                {{ item[col.key] }}
                            </slot>
                        </td>
                        <td v-if="actions" class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <slot name="actions" :item="item"></slot>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!data.data || data.data.length === 0">
                        <td :colspan="columns.length + (actions ? 2 : 1)" class="px-6 py-8 text-center text-slate-500">
                            Tidak ada data tersedia.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div v-if="data.links && data.links.length > 3" class="px-6 py-4 border-t border-slate-200 flex items-center justify-between">
            <span class="text-sm text-slate-500">
                Menampilkan <span class="font-medium text-slate-900">{{ data.from || 0 }}</span> sampai <span class="font-medium text-slate-900">{{ data.to || 0 }}</span> dari <span class="font-medium text-slate-900">{{ data.total }}</span> hasil
            </span>
            <div class="flex gap-1">
                <template v-for="(link, i) in data.links" :key="i">
                    <div v-if="link.url === null" class="px-3 py-1.5 text-sm text-slate-400 border border-slate-200 rounded cursor-not-allowed bg-slate-50" v-html="link.label"></div>
                    <Link v-else :href="link.url" :class="['px-3 py-1.5 text-sm border rounded transition-colors', link.active ? 'bg-indigo-600 border-indigo-600 text-white' : 'text-slate-600 border-slate-200 hover:bg-slate-50']" v-html="link.label" />
                </template>
            </div>
        </div>
    </div>
</template>
