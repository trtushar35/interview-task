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
            analytics: false
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

                    <!-- Ecommerce -->
                    <div class="relative">
                        <button @click="toggleDropdown('ecommerce')"
                            class="flex items-center justify-between w-full px-3 py-3 text-sm font-medium rounded-lg transition-colors text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                            <div class="flex items-center">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                <span class="ml-3 transition-opacity duration-300"
                                    :class="isCollapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'">
                                    Ecommerce
                                </span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200"
                                :class="openDropdowns.ecommerce ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" v-if="!isCollapsed">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Ecommerce Dropdown -->
                        <div v-show="openDropdowns.ecommerce && !isCollapsed" class="mt-1 ml-8 space-y-1">
                            <a href="/ecommerce/products"
                                class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors">
                                Products
                            </a>
                            <a href="/ecommerce/orders"
                                class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors">
                                Orders
                            </a>
                            <a href="/ecommerce/customers"
                                class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors">
                                Customers
                            </a>
                        </div>
                    </div>

                    <!-- Analytics -->
                    <div class="relative">
                        <button @click="toggleDropdown('analytics')"
                            class="flex items-center justify-between w-full px-3 py-3 text-sm font-medium rounded-lg transition-colors text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                            <div class="flex items-center">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                                <span class="ml-3 transition-opacity duration-300"
                                    :class="isCollapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'">
                                    Analytics
                                </span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200"
                                :class="openDropdowns.analytics ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" v-if="!isCollapsed">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Analytics Dropdown -->
                        <div v-show="openDropdowns.analytics && !isCollapsed" class="mt-1 ml-8 space-y-1">
                            <a href="/analytics/overview"
                                class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors">
                                Overview
                            </a>
                            <a href="/analytics/reports"
                                class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors">
                                Reports
                            </a>
                            <a href="/analytics/insights"
                                class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-colors">
                                Insights
                            </a>
                        </div>
                    </div>

                    <!-- Marketing -->
                    <a href="/marketing"
                        class="flex items-center px-3 py-3 text-sm font-medium rounded-lg transition-colors text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                        </svg>
                        <span class="ml-3 transition-opacity duration-300"
                            :class="isCollapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'">
                            Marketing
                        </span>
                    </a>

                    <!-- CRM -->
                    <a href="/crm"
                        class="flex items-center px-3 py-3 text-sm font-medium rounded-lg transition-colors text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <span class="ml-3 transition-opacity duration-300"
                            :class="isCollapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'">
                            CRM
                        </span>
                    </a>

                    <!-- Stocks -->
                    <a href="/stocks"
                        class="flex items-center px-3 py-3 text-sm font-medium rounded-lg transition-colors text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        <span class="ml-3 transition-opacity duration-300"
                            :class="isCollapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'">
                            Stocks
                        </span>
                    </a>

                    <!-- Calendar -->
                    <a href="/calendar"
                        class="flex items-center px-3 py-3 text-sm font-medium rounded-lg transition-colors text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span class="ml-3 transition-opacity duration-300"
                            :class="isCollapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'">
                            Calendar
                        </span>
                    </a>

                    <!-- User Profile -->
                    <a href="/profile"
                        class="flex items-center px-3 py-3 text-sm font-medium rounded-lg transition-colors text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="ml-3 transition-opacity duration-300"
                            :class="isCollapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'">
                            User Profile
                        </span>
                    </a>

                    <!-- Task -->
                    <a href="/tasks"
                        class="flex items-center px-3 py-3 text-sm font-medium rounded-lg transition-colors text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        <span class="ml-3 transition-opacity duration-300"
                            :class="isCollapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'">
                            Task
                        </span>
                    </a>

                    <!-- Forms -->
                    <a href="/forms"
                        class="flex items-center px-3 py-3 text-sm font-medium rounded-lg transition-colors text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span class="ml-3 transition-opacity duration-300"
                            :class="isCollapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'">
                            Forms
                        </span>
                    </a>

                    <!-- Tables -->
                    <a href="/tables"
                        class="flex items-center px-3 py-3 text-sm font-medium rounded-lg transition-colors text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span class="ml-3 transition-opacity duration-300"
                            :class="isCollapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'">
                            Tables
                        </span>
                    </a>

                    <!-- Pages -->
                    <a href="/pages"
                        class="flex items-center px-3 py-3 text-sm font-medium rounded-lg transition-colors text-gray-600 hover:text-gray-900 hover:bg-gray-50">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"></path>
                        </svg>
                        <span class="ml-3 transition-opacity duration-300"
                            :class="isCollapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'">
                            Pages
                        </span>
                    </a>
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