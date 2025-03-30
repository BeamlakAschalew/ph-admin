<script setup>
import Navbar from '@/Components/Navbar.vue';
</script>

<template>
    <div class="flex min-h-screen flex-col">
        <Navbar />
        <slot></slot>
    </div>
</template>
