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
               bg-gradient-to-br from-teal-700 via-emerald-700 to-teal-800
               hover:from-red-600 hover:via-red-700 hover:to-red-800
               border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest
               focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
               transition ease-in-out duration-150">
        <slot />
    </button>
</template>
