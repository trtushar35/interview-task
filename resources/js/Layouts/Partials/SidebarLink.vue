<script>
import { Link } from '@inertiajs/vue3';

export default {
    name: 'SidebarLink',
    components: {
        Link
    },
    props: {
        href: {
            type: String,
            required: true
        },
        active: {
            type: Boolean,
            default: false
        }
    }
}
</script>

<template>
    <Link 
        :href="href" 
        class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors"
        :class="active ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
    >
        <slot name="icon"></slot>
        <slot></slot>
    </Link>
</template>
