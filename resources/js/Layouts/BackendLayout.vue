<script>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Navbar from '@/Layouts/Partials/Navbar.vue';
import Sidebar from '@/Layouts/Partials/Sidebar.vue';

export default {
    name: 'BackendLayout',
    components: {
        Navbar,
        Sidebar
    },
    setup() {
        const sidebarOpen = ref(false);
        const sidebarCollapsed = ref(false);
        const page = usePage();

        // Menus are automatically passed through Inertia by the middleware
        const menus = computed(() => page.props.menus || []);

        const toggleSidebarCollapse = () => {
            sidebarCollapsed.value = !sidebarCollapsed.value;
        };

        const toggleMobileSidebar = () => {
            sidebarOpen.value = !sidebarOpen.value;
        };

        return {
            sidebarOpen,
            sidebarCollapsed,
            toggleSidebarCollapse,
            toggleMobileSidebar,
            menus
        };
    }
}
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Mobile Menu Overlay -->
        <div 
            v-if="sidebarOpen" 
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
        ></div>

        <!-- Sidebar -->
        <Sidebar 
            :isCollapsed="sidebarCollapsed" 
            :isMobileOpen="sidebarOpen"
            @toggle-collapse="toggleSidebarCollapse"
            @toggle-sidebar="sidebarOpen = false"
        />
        
        <!-- Main Content Area -->
        <div :class="sidebarCollapsed ? 'lg:ml-16' : 'lg:ml-64'" class="transition-all duration-300">
            <!-- Navbar -->
            <Navbar 
                :sidebarCollapsed="sidebarCollapsed"
                @toggle-sidebar="toggleMobileSidebar" 
                @toggle-collapse="toggleSidebarCollapse"
            />
            
            <!-- Page Content -->
            <main class="pt-20 px-4 md:px-6 pb-6">
                <slot />
            </main>
        </div>
    </div>
</template>