<script setup>
import { ref, onMounted, computed } from 'vue';
import BackendLayout from '@/Layouts/BackendLayout.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AlertMessage from '@/Components/AlertMessage.vue';

const props = defineProps(['role', 'id', 'permissions', 'selectedPermissions']);

const form = useForm({
    name: props.role?.name ?? '',
    permissions: props.selectedPermissions ?? [],
    _method: props.role?.id ? 'put' : 'post',
});

// Permission selection state
const expandedPermissions = ref({});

// Toggle permission group
const togglePermissionGroup = (permissionId) => {
    expandedPermissions.value[permissionId] = !expandedPermissions.value[permissionId];
};

// Toggle sub-permission group
const toggleSubPermissionGroup = (parentId, childId) => {
    const key = `${parentId}-${childId}`;
    expandedPermissions.value[key] = !expandedPermissions.value[key];
};

// Select all permissions in a group (including nested)
const selectAllInGroup = (permissionGroup, isChecked) => {
    const allPermissionIds = getAllPermissionIds(permissionGroup);

    if (isChecked) {
        // Add all permissions
        allPermissionIds.forEach(permissionId => {
            if (!form.permissions.includes(permissionId)) {
                form.permissions.push(permissionId);
            }
        });
    } else {
        // Remove all permissions
        allPermissionIds.forEach(permissionId => {
            const index = form.permissions.indexOf(permissionId);
            if (index > -1) {
                form.permissions.splice(index, 1);
            }
        });
    }
};

// Get all permission IDs from a group (including nested children)
const getAllPermissionIds = (permissionGroup) => {
    let permissionIds = [permissionGroup.id];

    permissionGroup.children?.forEach(child => {
        permissionIds.push(child.id);
        child.children?.forEach(grandChild => {
            permissionIds.push(grandChild.id);
        });
    });

    return permissionIds;
};

// Check if all permissions in group are selected
const isGroupSelected = (permissionGroup) => {
    const allPermissionIds = getAllPermissionIds(permissionGroup);
    return allPermissionIds.every(permissionId =>
        form.permissions.includes(permissionId)
    );
};

// Check if some permissions in group are selected
const isGroupIndeterminate = (permissionGroup) => {
    const allPermissionIds = getAllPermissionIds(permissionGroup);
    const selectedCount = allPermissionIds.filter(permissionId =>
        form.permissions.includes(permissionId)
    ).length;
    return selectedCount > 0 && selectedCount < allPermissionIds.length;
};

// Check if all permissions in sub-group are selected
const isSubGroupSelected = (subPermissionGroup) => {
    const subPermissionIds = [subPermissionGroup.id, ...subPermissionGroup.children.map(child => child.id)];
    return subPermissionIds.every(permissionId =>
        form.permissions.includes(permissionId)
    );
};

// Check if some permissions in sub-group are selected
const isSubGroupIndeterminate = (subPermissionGroup) => {
    const subPermissionIds = [subPermissionGroup.id, ...subPermissionGroup.children.map(child => child.id)];
    const selectedCount = subPermissionIds.filter(permissionId =>
        form.permissions.includes(permissionId)
    ).length;
    return selectedCount > 0 && selectedCount < subPermissionIds.length;
};

// Select all permissions in sub-group
const selectAllInSubGroup = (subPermissionGroup, isChecked) => {
    const subPermissionIds = [subPermissionGroup.id, ...subPermissionGroup.children.map(child => child.id)];

    if (isChecked) {
        // Add all sub-permissions
        subPermissionIds.forEach(permissionId => {
            if (!form.permissions.includes(permissionId)) {
                form.permissions.push(permissionId);
            }
        });
    } else {
        // Remove all sub-permissions
        subPermissionIds.forEach(permissionId => {
            const index = form.permissions.indexOf(permissionId);
            if (index > -1) {
                form.permissions.splice(index, 1);
            }
        });
    }
};

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
                        class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white transition-all duration-200 transform rounded-lg shadow-md bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 active:scale-95 cursor-pointer">
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
                <div class="grid grid-cols-1 gap-6">
                    <!-- Role Name -->
                    <div class="col-span-1">
                        <InputLabel for="name" value="Role Name" class="mb-2 text-sm font-semibold text-gray-700" />
                        <input id="name"
                            class="block w-full px-4 py-3 text-sm text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none placeholder-gray-400"
                            v-model="form.name" type="text" placeholder="Enter role name (e.g., Admin, Manager, User)"
                            :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-200': form.errors.name }" />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <!-- Permissions Section -->
                    <div class="col-span-1">
                        <InputLabel value="Permissions" class="mb-4 text-sm font-semibold text-gray-700" />

                        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="space-y-3">
                                <!-- Permission Groups -->
                                <div v-for="permissionGroup in permissions" :key="permissionGroup.id"
                                    class="border border-gray-200 rounded-lg">
                                    <!-- Group Header -->
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-t-lg">
                                        <div class="flex items-center space-x-3">
                                            <input type="checkbox" :id="`group-${permissionGroup.id}`"
                                                :checked="isGroupSelected(permissionGroup)"
                                                :indeterminate="isGroupIndeterminate(permissionGroup)"
                                                @change="selectAllInGroup(permissionGroup, $event.target.checked)"
                                                class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 focus:ring-2" />
                                            <label :for="`group-${permissionGroup.id}`"
                                                class="text-sm font-medium text-gray-700 cursor-pointer">
                                                {{ permissionGroup.name }}
                                            </label>
                                        </div>
                                        <button type="button" @click="togglePermissionGroup(permissionGroup.id)"
                                            class="p-1 text-gray-400 transition-colors rounded hover:text-gray-600 hover:bg-gray-200">
                                            <svg class="w-5 h-5 transition-transform duration-200"
                                                :class="{ 'rotate-180': expandedPermissions[permissionGroup.id] }"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Child Permissions -->
                                    <div v-show="expandedPermissions[permissionGroup.id]"
                                        class="p-4 bg-white border-t border-gray-200 rounded-b-lg">
                                        <div class="space-y-4">
                                            <div v-for="childPermission in permissionGroup.children"
                                                :key="childPermission.id" class="pl-4 border-l-2 border-gray-200">
                                                <!-- Sub-group Header -->
                                                <div class="flex items-center justify-between mb-3">
                                                    <div class="flex items-center space-x-3">
                                                        <input type="checkbox" :id="`subgroup-${childPermission.id}`"
                                                            :checked="isSubGroupSelected(childPermission)"
                                                            :indeterminate="isSubGroupIndeterminate(childPermission)"
                                                            @change="selectAllInSubGroup(childPermission, $event.target.checked)"
                                                            class="w-4 h-4 text-green-600 bg-white border-gray-300 rounded focus:ring-green-500 focus:ring-2" />
                                                        <label :for="`subgroup-${childPermission.id}`"
                                                            class="text-sm font-medium text-gray-600 cursor-pointer">
                                                            {{ childPermission.name }}
                                                        </label>
                                                    </div>
                                                    <button
                                                        v-if="childPermission.children && childPermission.children.length > 0"
                                                        type="button"
                                                        @click="toggleSubPermissionGroup(permissionGroup.id, childPermission.id)"
                                                        class="p-1 text-gray-400 transition-colors rounded hover:text-gray-600 hover:bg-gray-200">
                                                        <svg class="w-4 h-4 transition-transform duration-200"
                                                            :class="{ 'rotate-180': expandedPermissions[`${permissionGroup.id}-${childPermission.id}`] }"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </button>
                                                </div>

                                                <!-- Grandchild Permissions -->
                                                <div v-if="childPermission.children && childPermission.children.length > 0"
                                                    v-show="expandedPermissions[`${permissionGroup.id}-${childPermission.id}`]"
                                                    class="pl-6 mt-2">
                                                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-3">
                                                        <div v-for="grandChildPermission in childPermission.children"
                                                            :key="grandChildPermission.id"
                                                            class="flex items-center space-x-2">
                                                            <input type="checkbox"
                                                                :id="`permission-${grandChildPermission.id}`"
                                                                :value="grandChildPermission.id"
                                                                v-model="form.permissions"
                                                                class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 focus:ring-2" />
                                                            <label :for="`permission-${grandChildPermission.id}`"
                                                                class="text-xs text-gray-500 cursor-pointer select-none">
                                                                {{ grandChildPermission.name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- No Permissions Message -->
                                <div v-if="permissions.length === 0"
                                    class="p-4 text-center text-gray-500 bg-gray-50 rounded-lg">
                                    No permissions available
                                </div>
                            </div>
                        </div>
                        <InputError class="mt-2" :message="form.errors.permissions" />
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