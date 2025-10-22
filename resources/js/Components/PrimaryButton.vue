<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center px-4 py-2 
               bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800
               hover:from-blue-700 hover:via-blue-800 hover:to-blue-900
               border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest
               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
               transition ease-in-out duration-150
               cursor-pointer">
        <slot />
    </button>
</template>