<script>
import { ref, onMounted, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

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
        const openDropdowns = ref({});
        const page = usePage();
        
        // Get menus from Inertia props
        const menus = computed(() => page.props.menus || []);

        const toggleDropdown = (menuId) => {
            openDropdowns.value[menuId] = !openDropdowns.value[menuId];
        };

        // Initialize dropdown states
        onMounted(() => {
            menus.value.forEach(menu => {
                openDropdowns.value[menu.id] = false;
            });
        });

        return {
            openDropdowns,
            toggleDropdown,
            menus
        };
    },
    methods: {
        toggleCollapse() {
            this.$emit('toggle-collapse');
        },
        
        // Generate unique key for menu
        getMenuKey(menu) {
            return `menu-${menu.id}`;
        },
        
        // Check if menu has active children
        hasActiveChildren(menu) {
            if (!menu.children || menu.children.length === 0) {
                return false;
            }
            
            const currentRoute = window.location.pathname;
            return menu.children.some(child => 
                child.href && currentRoute.startsWith(child.href.replace(/^https?:\/\/[^\/]+/, ''))
            );
        },
        
        // Check if current route matches menu route
        isActiveMenu(menu) {
            if (!menu.href || menu.href === '#') return false;
            
            const currentRoute = window.location.pathname;
            const menuRoute = menu.href.replace(/^https?:\/\/[^\/]+/, '');
            
            return currentRoute === menuRoute || currentRoute.startsWith(menuRoute + '/');
        },
        
        // Get Bootstrap Icon class based on menu name or icon field
        getBootstrapIcon(menu) {
            // If menu has custom icon field, use it
            if (menu.icon) {
                return menu.icon;
            }
            
            // Map by menu name for both main menus and submenus
            const iconMap = {
                // Main menus
                'Dashboard': 'bi-speedometer2',
                'Role Management': 'bi-shield-lock',
                'User Management': 'bi-people-fill',
                
                // Submenus
                'Overview': 'bi-house-door',
                'Analytics': 'bi-graph-up-arrow',
                'Reports': 'bi-bar-chart-line',
                'Role List': 'bi-list-check',
                'User List': 'bi-person-badge',
            };
            
            return iconMap[menu.name] || 'bi-circle';
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

        <!-- Menu Content -->
        <div class="overflow-y-auto h-[calc(100vh-4rem)]">
            <nav class="p-2">
                <div class="space-y-1">

                    <!-- Dynamic Menu Items -->
                    <div v-for="menu in menus" :key="getMenuKey(menu)" class="relative">
                        <!-- Parent Menu Item with Children -->
                        <div v-if="menu.children && menu.children.length > 0">
                            <!-- Dropdown Menu Button -->
                            <button 
                                @click="toggleDropdown(menu.id)"
                                :class="[
                                    'flex items-center justify-between w-full px-3 py-3 text-sm font-medium rounded-lg transition-colors',
                                    hasActiveChildren(menu) 
                                        ? 'bg-blue-50 text-blue-600 border border-blue-100' 
                                        : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
                                ]">
                                <div class="flex items-center">
                                    <!-- Bootstrap Icon -->
                                    <i :class="['bi', getBootstrapIcon(menu), 'text-lg']"></i>
                                    <span class="ml-3 transition-opacity duration-300"
                                        :class="isCollapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'">
                                        {{ menu.name }}
                                    </span>
                                </div>
                                <i class="bi bi-chevron-down transition-transform duration-200"
                                    :class="openDropdowns[menu.id] ? 'rotate-180' : ''" 
                                    v-if="!isCollapsed"></i>
                            </button>
    
                            <!-- Dropdown Content -->
                            <div v-show="openDropdowns[menu.id] && !isCollapsed" class="mt-1 ml-8 space-y-1">
                                <a 
                                    v-for="child in menu.children" 
                                    :key="child.id"
                                    :href="child.href || '#'"
                                    :class="[
                                        'flex items-center px-3 py-2 text-sm rounded-lg transition-colors',
                                        isActiveMenu(child)
                                            ? 'text-blue-600 bg-blue-50' 
                                            : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
                                    ]">
                                    <!-- Submenu Icon -->
                                    <i :class="['bi', getBootstrapIcon(child), 'text-md mr-3']"></i>
                                    {{ child.name }}
                                </a>
                            </div>
                        </div>


                        <!-- Single Menu Item (No Children) -->
                        <a 
                            v-else
                            :href="menu.href || '#'"
                            :class="[
                                'flex items-center w-full px-3 py-3 text-sm font-medium rounded-lg transition-colors',
                                isActiveMenu(menu)
                                    ? 'bg-blue-50 text-blue-600 border border-blue-100' 
                                    : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
                            ]">
                            <div class="flex items-center">
                                <!-- Bootstrap Icon -->
                                <i :class="['bi', getBootstrapIcon(menu), 'text-lg']"></i>
                                <span class="ml-3 transition-opacity duration-300"
                                    :class="isCollapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'">
                                    {{ menu.name }}
                                </span>
                            </div>
                        </a>
                    </div>

                    <!-- No Menus Message -->
                    <div v-if="menus.length === 0" class="p-4 text-center text-gray-500 text-sm">
                        No menus available for your role
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