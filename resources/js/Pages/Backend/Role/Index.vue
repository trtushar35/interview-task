<script setup>
import { ref } from "vue";
import BackendLayout from '@/Layouts/BackendLayout.vue';
import BaseTable from '@/Components/BaseTable.vue';
import Pagination from '@/Components/Pagination.vue';
import { router } from '@inertiajs/vue3';

let props = defineProps({
    filters: Object,
});

const filters = ref({
    name: props.filters?.name ?? '',
    numOfData: props.filters?.numOfData ?? 10,
});

const applyFilter = () => {
    router.get(window.location.pathname, filters.value, { 
        preserveState: true,
        preserveScroll: true 
    });
};

const goToRoleAdd = () => {
    router.get('/backend/role/create');
};

</script>

<template>
    <BackendLayout>
        <div class="w-full p-6 duration-1000 ease-in-out bg-white rounded-xl shadow-lg">

            <!-- Header Section -->
            <div class="flex flex-col gap-4 mb-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $page.props.pageTitle }}</h1>
                    <p class="mt-1 text-sm text-gray-500">Manage roles and permissions</p>
                </div>

                <div class="flex items-center space-x-3">
                    <button @click="goToRoleAdd"
                        class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white transition-all duration-200 transform rounded-lg shadow-md bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 active:scale-95 cursor-pointer">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15">
                            </path>
                        </svg>
                        Add New Role
                    </button>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="p-5 mb-6 rounded-lg bg-gradient-to-r from-gray-50 to-blue-50 border border-gray-200">
                <div class="grid w-full grid-cols-1 gap-4 md:grid-cols-5">
                    <div class="flex space-x-3 md:col-span-4">
                        <div class="flex-1">
                            <label for="name" class="block mb-2 text-xs font-medium text-gray-600 uppercase">Search by Role Name</label>
                            <input id="name" v-model="filters.name"
                                class="block w-full px-4 py-2.5 text-sm text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                                type="text" placeholder="Enter role name..." @input="applyFilter" />
                        </div>

                        <div class="block min-w-32 md:hidden">
                            <label class="block mb-2 text-xs font-medium text-gray-600 uppercase">Show Entries</label>
                            <select v-model="filters.numOfData" @change="applyFilter"
                                class="w-full px-4 py-2.5 text-sm text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none">
                                <option value="10">10 entries</option>
                                <option value="20">20 entries</option>
                                <option value="30">30 entries</option>
                                <option value="40">40 entries</option>
                                <option value="100">100 entries</option>
                                <option value="150">150 entries</option>
                                <option value="500">500 entries</option>
                            </select>
                        </div>
                    </div>

                    <div class="hidden md:block">
                        <label class="block mb-2 text-xs font-medium text-gray-600 uppercase">Show Entries</label>
                        <select v-model="filters.numOfData" @change="applyFilter"
                            class="w-full px-4 py-2.5 text-sm text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none">
                            <option value="10">10 entries</option>
                            <option value="20">20 entries</option>
                            <option value="30">30 entries</option>
                            <option value="40">40 entries</option>
                            <option value="100">100 entries</option>
                            <option value="150">150 entries</option>
                            <option value="500">500 entries</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="w-full mb-5">
                <BaseTable />
            </div>

            <!-- Pagination Section -->
            <div class="pt-4 border-t border-gray-200">
                <Pagination />
            </div>
        </div>
    </BackendLayout>
</template>