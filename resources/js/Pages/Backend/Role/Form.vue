<script setup>
import { ref, onMounted, computed } from 'vue';
import BackendLayout from '@/Layouts/BackendLayout.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AlertMessage from '@/Components/AlertMessage.vue';

const props = defineProps(['role', 'id']);

const form = useForm({
    name: props.role?.name ?? '',
    _method: props.role?.id ? 'put' : 'post',
});

const submit = () => {
    const routeName = props.id ? route('backend.role.update', props.id) : route('backend.role.store');

    form.transform(data => ({
        ...data,
        remember: '',
        isDirty: false,
    })).post(routeName, {
        preserveScroll: true,
        onSuccess: () => {
            if (!props.id) {
                form.reset();
            }
        },
    });
};

const goToRoleList = () => {
    router.visit(route('backend.role.index'));
};
</script>

<template>
    <BackendLayout>
        <div class="w-full p-6 duration-1000 ease-in-out bg-white rounded-xl shadow-lg">

            <!-- Header Section -->
            <div class="flex flex-col gap-4 mb-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $page.props.pageTitle }}</h1>
                </div>

                <div class="flex items-center space-x-3">
                    <button @click="goToRoleList"
                        class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white transition-all duration-200 transform rounded-lg shadow-md bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 active:scale-95">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                        Role List
                    </button>
                </div>
            </div>

            <!-- Alert Message -->
            <AlertMessage />

            <!-- Form Section -->
            <form @submit.prevent="submit"
                class="p-6 rounded-lg bg-gradient-to-r from-gray-50 to-blue-50 border border-gray-200">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                    <div class="col-span-1 md:col-span-2">
                        <InputLabel for="name" value="Role Name" class="mb-2 text-sm font-semibold text-gray-700" />
                        <input id="name"
                            class="block w-full px-4 py-3 text-sm text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none placeholder-gray-400"
                            v-model="form.name" type="text" placeholder="Enter role name (e.g., Admin, Manager, User)"
                            :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-200': form.errors.name }" />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end pt-6 mt-6 border-t border-gray-200">
                    <PrimaryButton type="submit" :class="[{ 'opacity-25 cursor-not-allowed': form.processing }, 'px-8']"
                        :disabled="form.processing">
                        <span v-if="form.processing">
                            <svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Processing...
                        </span>
                        <span v-else>
                            {{ (props.id ? 'Update' : 'Create') }} Role
                        </span>
                    </PrimaryButton>
                </div>
            </form>

        </div>
    </BackendLayout>
</template>