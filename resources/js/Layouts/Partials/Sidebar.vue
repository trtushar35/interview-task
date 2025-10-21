<script>
import { ref } from 'vue';

export default {
    name: 'Sidebar',
    props: {
        isCollapsed: {
            type: Boolean,
            default: false
        }
    },
    emits: ['toggle-collapse'],
    setup() {
        const openDropdowns = ref({
            dashboard: false,
            ecommerce: false,
            analytics: false,
            role: false,
            user: false
        });

        const toggleDropdown = (menu) => {
            openDropdowns.value[menu] = !openDropdowns.value[menu];
        };

        return {
            openDropdowns,
            toggleDropdown
        };
    },
    methods: {
        toggleCollapse() {
            this.$emit('toggle-collapse');
        }
    }
}
</script>

<template>
    <aside
        class="bg-white shadow-sm border-r border-gray-200 h-screen fixed left-0 top-0 overflow-hidden transition-all duration-300 z-40"
        :class="isCollapsed ? 'w-16' : 'w-64'">
        <!-- Header -->
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <h1 class="text-xl font-bold text-gray-800 transition-opacity duration-300"
                :class="isCollapsed ? 'opacity-0 w-0' : 'opacity-100'">
                TailAdmin
            </h1>
        </div>

        <!-- Menu Content - Scrollable Area -->
        <div class="overflow-y-auto h-[calc(100vh-4rem)]">
            <nav class="p-2">
                <div class="space-y-1">
                    <!-- Dashboard -->
                    <div class="relative">
                        <button @click="toggleDropdown('dashboard')"
                            class="flex items-center justify-between w-full px-3 py-3 text-sm font-medium rounded-lg transition-colors bg-blue-50 text-blue-600 border border-blue-100">
                            <div class="flex items-center">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                    </path>
                                </svg>
                                <span class="ml-3 transition-opacity duration-300"
                                    :class="isCollapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'">
                                    Dashboard
                                </span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200"
                                :class="openDropdowns.dashboard ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" v-if="!isCollapsed">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dashboard Dropdown -->
                        <div v-show="openDropdowns.dashboard && !isCollapsed" class="mt-1 ml-8 space-y-1">
                            <a href="/dashboard/overview"
                                class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors">
                                Overview
                            </a>
                            <a href="/dashboard/analytics"
                                class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors">
                                Analytics
                            </a>
                            <a href="/dashboard/reports"
                                class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors">
                                Reports
                            </a>
                        </div>
                    </div>

                    <!-- Role Management -->
                    <div class="relative">
                        <button @click="toggleDropdown('role')"
                            class="flex items-center justify-between w-full px-3 py-3 text-sm font-medium rounded-lg transition-colors text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                            <div class="flex items-center">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                    </path>
                                </svg>
                                <span class="ml-3 transition-opacity duration-300"
                                    :class="isCollapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'">
                                    Role Management
                                </span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200"
                                :class="openDropdowns.role ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" v-if="!isCollapsed">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Role Dropdown -->
                        <div v-show="openDropdowns.role && !isCollapsed" class="mt-1 ml-8 space-y-1">
                            <a href="role"
                                class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors">
                                Role List
                            </a>
                        </div>
                    </div>

                    <!-- User Management -->
                    <div class="relative">
                        <button @click="toggleDropdown('user')"
                            class="flex items-center justify-between w-full px-3 py-3 text-sm font-medium rounded-lg transition-colors text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                            <div class="flex items-center">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                    </path>
                                </svg>
                                <span class="ml-3 transition-opacity duration-300"
                                    :class="isCollapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'">
                                    User Management
                                </span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200"
                                :class="openDropdowns.user ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" v-if="!isCollapsed">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- User Dropdown -->
                        <div v-show="openDropdowns.user && !isCollapsed" class="mt-1 ml-8 space-y-1">
                            <a href="user"
                                class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors">
                                User List
                            </a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </aside>
</template>

<style scoped>
.h-screen {
    height: 100vh;
}

.overflow-y-auto {
    overflow-y: auto;
}

/* Custom scrollbar styling */
::-webkit-scrollbar {
    width: 4px;
}

::-webkit-scrollbar-track {
    background: #f8fafc;
    border-radius: 2px;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 2px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}
</style>