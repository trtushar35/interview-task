<script setup>
import { usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const page = usePage();
const emit = defineEmits(['action']);

const handleDelete = (url) => {
    if (confirm('Are you sure you want to delete this item?')) {
        router.delete(url, {
            preserveScroll: true,
            preserveState: false,
            onSuccess: (page) => {
                console.log('Delete successful');
            },
            onError: (errors) => {
                console.error('Delete failed:', errors);
                alert('Failed to delete item');
            }
        });
    }
};

const handleStatusChange = (url) => {
    if (confirm('Are you sure you want to change the status?')) {
        router.post(url, {}, {
            preserveScroll: true,
            onSuccess: () => {
                // Optional: Show success message
            },
            onError: () => {
                alert('Failed to change status');
            }
        });
    }
};

</script>

<template>
    <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden">
                <table class="min-w-full text-sm border-collapse">
                    <thead class="bg-gradient-to-r from-blue-50 to-indigo-50">
                        <tr class="text-xs font-semibold tracking-wide">
                            <template v-for="(header, index) in $page.props.tableHeaders" :key="`header-${index}`">
                                <th scope="col" class="px-6 py-4 text-center text-gray-700 uppercase border-b-2 border-blue-200 whitespace-nowrap" :class="header.class || ''">
                                    {{ typeof header === 'object' ? header.label : header }}
                                </th>
                            </template>
                        </tr>
                    </thead>
                    <tbody class="text-sm bg-white divide-y divide-gray-100">
                        <template v-for="(data, dataIndex) in $page.props.datas.data" :key="`row-${dataIndex}`">
                            <tr class="transition-all duration-300 hover:bg-blue-50 hover:shadow-md">
                                <template v-for="(dateField, dateFieldIndex) in $page.props.dataFields" :key="`field-${dataIndex}-${dateFieldIndex}`">
                                    <td class="px-6 py-4 text-center text-gray-800 border-b border-gray-100 whitespace-nowrap" :class="dateField.class">
                                        <p v-html="data[dateField.fieldName] ?? ''" class="font-medium"></p>
                                    </td>
                                </template>
                                <!-- Only show actions column if data has links -->
                                <td v-if="data.links" class="px-6 py-4 border-b border-gray-100 whitespace-nowrap">
                                    <div class="flex justify-center w-full space-x-2">
                                        <template v-for="(linkInfo, linkIndex) in data.links" :key="`link-${dataIndex}-${linkIndex}`">
                                            <button v-if="linkInfo.actionName"
                                                @click="$emit('action', linkInfo.actionName, linkInfo.actionId)"
                                                class="px-4 py-2 text-xs font-semibold transition-all duration-200 transform rounded-md shadow-sm hover:scale-105 hover:shadow-md" 
                                                :class="linkInfo.linkClass">
                                                <span v-html="linkInfo.linkLabel"></span>
                                            </button>
                                            <a v-else-if="linkInfo.linkClass && linkInfo.linkClass.includes('deleteButton')"
                                                @click.prevent="handleDelete(linkInfo.link)"
                                                href="#"
                                                class="px-4 py-2 text-xs font-semibold transition-all duration-200 transform rounded-md shadow-sm hover:scale-105 hover:shadow-md cursor-pointer" 
                                                :class="linkInfo.linkClass">
                                                <span v-html="linkInfo.linkLabel"></span>
                                            </a>
                                            <a v-else-if="linkInfo.linkClass && linkInfo.linkClass.includes('statusChange')"
                                                @click.prevent="handleStatusChange(linkInfo.link)"
                                                href="#"
                                                class="px-4 py-2 text-xs font-semibold transition-all duration-200 transform rounded-md shadow-sm hover:scale-105 hover:shadow-md cursor-pointer" 
                                                :class="linkInfo.linkClass">
                                                <span v-html="linkInfo.linkLabel"></span>
                                            </a>
                                            <a v-else 
                                                class="px-4 py-2 text-xs font-semibold text-white transition-all duration-200 transform bg-green-500 rounded-md shadow-sm hover:bg-green-600 hover:scale-105 hover:shadow-md"
                                                :href="linkInfo.link" 
                                                :class="linkInfo.linkClass">
                                                <span v-html="linkInfo.linkLabel"></span>
                                            </a>
                                        </template>
                                    </div>
                                </td>
                                <td v-if="data.linked" class="px-6 py-4 border-b border-gray-100 whitespace-nowrap">
                                    <div class="flex justify-center w-full space-x-2">
                                        <template v-for="(linkInfo, linkIndex) in data.linked" :key="`linked-${dataIndex}-${linkIndex}`">
                                            <button v-if="linkInfo.actionName"
                                                @click="$emit('action', linkInfo.actionName, linkInfo.actionId)"
                                                class="px-4 py-2 text-xs font-semibold transition-all duration-200 transform rounded-md shadow-sm hover:scale-105 hover:shadow-md" 
                                                :class="linkInfo.linkClass">
                                                <span v-html="linkInfo.linkLabel"></span>
                                            </button>
                                            <a v-else-if="linkInfo.linkClass && linkInfo.linkClass.includes('deleteButton')"
                                                @click.prevent="handleDelete(linkInfo.link)"
                                                href="#"
                                                class="px-4 py-2 text-xs font-semibold transition-all duration-200 transform rounded-md shadow-sm hover:scale-105 hover:shadow-md cursor-pointer" 
                                                :class="linkInfo.linkClass">
                                                <span v-html="linkInfo.linkLabel"></span>
                                            </a>
                                            <a v-else-if="linkInfo.linkClass && linkInfo.linkClass.includes('statusChange')"
                                                @click.prevent="handleStatusChange(linkInfo.link)"
                                                href="#"
                                                class="px-4 py-2 text-xs font-semibold transition-all duration-200 transform rounded-md shadow-sm hover:scale-105 hover:shadow-md cursor-pointer" 
                                                :class="linkInfo.linkClass">
                                                <span v-html="linkInfo.linkLabel"></span>
                                            </a>
                                            <a v-else 
                                                class="px-4 py-2 text-xs font-semibold text-white transition-all duration-200 transform bg-green-500 rounded-md shadow-sm hover:bg-green-600 hover:scale-105 hover:shadow-md"
                                                :href="linkInfo.link" 
                                                :class="linkInfo.linkClass">
                                                <span v-html="linkInfo.linkLabel"></span>
                                            </a>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>