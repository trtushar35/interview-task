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

// Helper function to check if field should render as image
const isImageField = (fieldName) => {
    return ['photo', 'image', 'avatar', 'picture'].includes(fieldName.toLowerCase());
};

// Helper function to check if field should render as status badge
const isStatusField = (fieldName) => {
    return fieldName.toLowerCase() === 'status';
};

// Get status badge class
const getStatusClass = (status) => {
    const statusLower = status?.toLowerCase();
    if (statusLower === 'active') return 'bg-green-100 text-green-800';
    if (statusLower === 'inactive') return 'bg-red-100 text-red-800';
    if (statusLower === 'pending') return 'bg-yellow-100 text-yellow-800';
    if (statusLower === 'deleted') return 'bg-gray-100 text-gray-800';
    return 'bg-gray-100 text-gray-800';
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
                                        <!-- Image rendering -->
                                        <div v-if="isImageField(dateField.fieldName)" class="flex justify-center">
                                            <img v-if="data[dateField.fieldName]" 
                                                :src="data[dateField.fieldName]" 
                                                :alt="data.name || 'Photo'"
                                                class="object-cover w-12 h-12 border-2 border-gray-300 rounded-full shadow-sm"
                                                @error="$event.target.src = 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2250%22 height=%2250%22%3E%3Crect width=%2250%22 height=%2250%22 fill=%22%23ddd%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22sans-serif%22 font-size=%2220%22 fill=%22%23999%22%3EN/A%3C/text%3E%3C/svg%3E'" />
                                            <div v-else class="flex items-center justify-center w-12 h-12 bg-gray-200 rounded-full">
                                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <!-- Status badge rendering -->
                                        <span v-else-if="isStatusField(dateField.fieldName)" 
                                            class="inline-flex px-3 py-1 text-xs font-semibold rounded-full"
                                            :class="getStatusClass(data[dateField.fieldName])">
                                            {{ data[dateField.fieldName] ?? 'N/A' }}
                                        </span>
                                        <!-- Regular text rendering -->
                                        <p v-else v-html="data[dateField.fieldName] ?? 'N/A'" class="font-medium"></p>
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
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>