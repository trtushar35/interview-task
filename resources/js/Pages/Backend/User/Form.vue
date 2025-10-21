<script setup>
import { ref, computed } from 'vue';
import { useForm, usePage, router } from '@inertiajs/vue3';
import BackendLayout from '@/Layouts/BackendLayout.vue';

const props = defineProps({
    user: Object,
    roles: Array,
    id: String,
});

const page = usePage();
const isEdit = computed(() => !!props.id);

const form = useForm({
    name: props.user?.name || '',
    email: props.user?.email || '',
    number: props.user?.number || '',
    photo: null,
    company_name: props.user?.company_name || '',
    address: props.user?.address || '',
    role_id: props.user?.role_id || '',
    status: props.user?.status || 'Active',
    password: '',
    password_confirmation: '',
    _method: isEdit.value ? 'PUT' : 'POST', // Add this for Laravel method spoofing
});

// Handle photo URL properly - model accessor already adds full URL
const photoPreview = ref(null);
const currentPhoto = ref(props.user?.photo || null);

const handlePhotoChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            return;
        }
        
        // Validate file type
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            alert('Please select a valid image file (JPEG, PNG, JPG, GIF)');
            return;
        }
        
        form.photo = file;

        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            photoPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const removePhoto = () => {
    form.photo = null;
    photoPreview.value = null;
    if (isEdit.value) {
        form.photo = 'delete';
    }
    // Clear file input
    const fileInput = document.getElementById('photo');
    if (fileInput) fileInput.value = '';
};

const submit = () => {
    if (isEdit.value) {
        // Use POST with _method for file uploads
        form.post(route('backend.user.update', props.id), {
            preserveScroll: true,
            forceFormData: true, // Force multipart/form-data
            onSuccess: () => {
                form.reset('password', 'password_confirmation', 'photo');
            },
        });
    } else {
        form.post(route('backend.user.store'), {
            preserveScroll: true,
            onSuccess: () => form.reset(),
        });
    }
};
</script>

<template>
    <BackendLayout>
        <div class="w-full p-6 duration-1000 ease-in-out bg-white rounded-xl shadow-lg">
            <!-- Header Section -->
            <div class="flex flex-col gap-4 mb-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $page.props.pageTitle }}</h1>
                    <p class="mt-1 text-sm text-gray-500">{{ isEdit ? 'Update user information' : 'Create new user account' }}</p>
                </div>
            </div>

            <!-- Form Section -->
            <div class="max-w-full p-6 rounded-lg bg-gradient-to-r from-gray-50 to-blue-50 border border-gray-200">
                <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">
                    <!-- Personal Information -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Full Name *</label>
                            <input id="name" v-model="form.name" type="text"
                                class="w-full px-4 py-2.5 text-sm text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                                :class="{ 'border-red-500': form.errors.name }" />
                            <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email Address
                                *</label>
                            <input id="email" v-model="form.email" type="email" :readonly="isEdit"
                                class="w-full px-4 py-2.5 text-sm text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                                :class="{ 'border-red-500': form.errors.email, 'bg-gray-100': isEdit }" />
                            <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label for="number" class="block mb-2 text-sm font-medium text-gray-700">Phone
                                Number</label>
                            <input id="number" v-model="form.number" type="text"
                                class="w-full px-4 py-2.5 text-sm text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                                :class="{ 'border-red-500': form.errors.number }" />
                            <p v-if="form.errors.number" class="mt-1 text-sm text-red-600">{{ form.errors.number }}</p>
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="role_id" class="block mb-2 text-sm font-medium text-gray-700">Role *</label>
                            <select id="role_id" v-model="form.role_id"
                                class="w-full px-4 py-2.5 text-sm text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                                :class="{ 'border-red-500': form.errors.role_id }">
                                <option value="">Select Role</option>
                                <option v-for="role in roles" :key="role.id" :value="role.id">
                                    {{ role.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.role_id" class="mt-1 text-sm text-red-600">{{ form.errors.role_id }}
                            </p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block mb-2 text-sm font-medium text-gray-700">Status *</label>
                            <select id="status" v-model="form.status"
                                class="w-full px-4 py-2.5 text-sm text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                                :class="{ 'border-red-500': form.errors.status }">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <p v-if="form.errors.status" class="mt-1 text-sm text-red-600">{{ form.errors.status }}</p>
                        </div>

                        <!-- Photo Upload -->
                        <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Profile Photo</label>
                            <div class="flex items-center space-x-4">
                                <div v-if="photoPreview || currentPhoto" class="relative">
                                    <img :src="photoPreview || currentPhoto" alt="Profile preview"
                                        class="w-20 h-20 rounded-full object-cover border-2 border-gray-300">
                                    <button type="button" @click="removePhoto"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                        ×
                                    </button>
                                </div>
                                <div class="flex-1">
                                    <input id="photo" type="file" @change="handlePhotoChange" accept="image/*"
                                        class="w-full px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                                    <p class="mt-1 text-xs text-gray-500">JPEG, PNG, JPG, GIF (Max: 2MB)</p>
                                    <p v-if="isEdit" class="mt-1 text-xs text-gray-500">Leave empty to keep current photo</p>
                                </div>
                            </div>
                            <p v-if="form.errors.photo" class="mt-1 text-sm text-red-600">{{ form.errors.photo }}</p>
                        </div>

                        <!-- Company Name -->
                        <div class="md:col-span-2">
                            <label for="company_name" class="block mb-2 text-sm font-medium text-gray-700">Company
                                Name</label>
                            <input id="company_name" v-model="form.company_name" type="text"
                                class="w-full px-4 py-2.5 text-sm text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                                :class="{ 'border-red-500': form.errors.company_name }" />
                            <p v-if="form.errors.company_name" class="mt-1 text-sm text-red-600">{{
                                form.errors.company_name }}</p>
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="block mb-2 text-sm font-medium text-gray-700">Address</label>
                            <textarea id="address" v-model="form.address" rows="3"
                                class="w-full px-4 py-2.5 text-sm text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                                :class="{ 'border-red-500': form.errors.address }"></textarea>
                            <p v-if="form.errors.address" class="mt-1 text-sm text-red-600">{{ form.errors.address }}
                            </p>
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-700">
                                {{ isEdit ? 'New Password' : 'Password *' }}
                            </label>
                            <input id="password" v-model="form.password" type="password"
                                class="w-full px-4 py-2.5 text-sm text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                                :class="{ 'border-red-500': form.errors.password }" />
                            <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}
                            </p>
                            <p v-if="isEdit" class="mt-1 text-xs text-gray-500">Leave empty to keep current password</p>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-700">
                                {{ isEdit ? 'Confirm New Password' : 'Confirm Password *' }}
                            </label>
                            <input id="password_confirmation" v-model="form.password_confirmation" type="password"
                                class="w-full px-4 py-2.5 text-sm text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none"
                                :class="{ 'border-red-500': form.errors.password_confirmation }" />
                            <p v-if="form.errors.password_confirmation" class="mt-1 text-sm text-red-600">{{
                                form.errors.password_confirmation }}</p>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end pt-6 space-x-4 border-t border-gray-200">
                        <button type="button" @click="window.history.back()"
                            class="px-6 py-2.5 text-sm font-semibold text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-200">
                            Cancel
                        </button>
                        <button type="submit" :disabled="form.processing"
                            class="px-6 py-2.5 text-sm font-semibold text-white transition-all duration-200 transform rounded-lg shadow-md bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            {{ form.processing ? 'Processing...' : (isEdit ? 'Update User' : 'Create User') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </BackendLayout>
</template>