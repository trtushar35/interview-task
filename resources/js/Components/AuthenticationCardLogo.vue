<script setup>
import { Link } from '@inertiajs/vue3';
import logo from '/public/assets/olara.jpg';
const imageUrl = logo;

</script>

<template>
    <Link :href="'/backend/dashboard'">
        <img class="w-32 mix-blend-multiply" :src="imageUrl" alt="logo">
    </Link>
</template>
