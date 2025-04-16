<script setup>
import Navbar from '@/Components/Supplier/Navbar.vue';
import MainLayout from './MainLayout.vue';

defineProps({ subcities: Array });

defineOptions({
    layout: MainLayout,
});
</script>

<template>
    <div class="flex min-h-screen flex-col bg-gray-50">
        <Navbar :subcities="subcities" />
        <!-- Hero Section -->
        <div class="flex flex-1 flex-col items-center justify-center gap-5">
            <div class="text-4xl">
                The supplier feature is not developed yet
            </div>
            <div class="text-3xl">Contact +2519111111 for more</div>
        </div>
    </div>
</template>
