<script>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

export default {
    name: 'Navbar',
    components: {
        Link
    },
    props: {
        sidebarCollapsed: {
            type: Boolean,
            default: false
        }
    },
    emits: ['toggle-sidebar', 'toggle-collapse'],
    setup() {
        const userMenuOpen = ref(false);

        return {
            userMenuOpen
        };
    }
}
</script>

<template>
    <nav class="fixed top-0 bg-white border-b border-gray-200 z-30 h-16 transition-all duration-300"
        :class="sidebarCollapsed ? 'left-16 right-0' : 'left-64 right-0'">
        <div class="px-4 md:px-6 h-full">
            <div class="flex justify-between items-center h-full">
                <!-- Left Side - Menu Toggle Button -->
                <div class="flex items-center">
                    <button @click="$emit('toggle-collapse')"
                        class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Sidebar Collapse Toggle Button (Desktop) -->
                    <button @click="$emit('toggle-collapse')"
                        class="p-2 hover:bg-gray-100 rounded-lg transition-colors hidden lg:block ml-2">

                    </button>
                </div>

                <!-- Center - Search Bar -->
                <div class="flex-1 flex justify-center px-2 md:px-4">
                    <div class="relative w-full max-w-md hidden md:block">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" placeholder="Search or type command..."
                            class="w-full pl-10 pr-12 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 text-sm">
                        <span
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs font-medium">⌘
                            K</span>
                    </div>
                </div>

                <!-- Right Side - Icons & User Profile -->
                <div class="flex items-center gap-2 md:gap-4">
                    <!-- Mobile Search Button -->
                    <button class="md:hidden p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>

                    <!-- Theme Toggle -->
                    <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <!-- Notifications -->
                    <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors relative">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-orange-400 rounded-full"></span>
                    </button>

                    <!-- Divider -->
                    <div class="h-6 border-l border-gray-200 hidden sm:block"></div>

                    <!-- User Profile -->
                    <div class="relative">
                        <button @click="userMenuOpen = !userMenuOpen"
                            class="flex items-center gap-2 md:gap-3 hover:opacity-75 transition-opacity">

                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                alt="User" class="w-9 h-9 rounded-full object-cover border-2 border-gray-200">

                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-medium text-gray-900">Emirhan Boruch</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-600 hidden md:block" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- User Dropdown Menu -->
                        <div v-show="userMenuOpen" @click="userMenuOpen = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                            <Link href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            My Profile
                            </Link>
                            <Link href="/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            Settings
                            </Link>
                            <hr class="my-1 border-gray-200">
                            <button class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                Logout
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>