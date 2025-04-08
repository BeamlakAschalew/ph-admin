<script setup>
import Navbar from '@/Components/Admin/Navbar.vue';
import { usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const page = usePage();

const flash = ref(page.props.flash.message);
watch(
    () => page.props.flash.message,
    (newFlash) => {
        flash.value = newFlash;

        if (flash.value) {
            setTimeout(() => {
                flash.value = null;
            }, 5000);
        }
    },
);

if (page.props.flash.message) {
    setTimeout(() => {
        flash.value = null;
    }, 5000);
}

const toastClasses = computed(() =>
    flash.value?.type === 'success'
        ? 'bg-green-500 text-white'
        : flash.value?.type === 'error'
          ? 'bg-red-500 text-white'
          : 'bg-gray-500 text-white',
);

function dismissToast() {
    flash.value = null;
}
</script>

<template>
    <div class="flex min-h-screen flex-col">
        <Navbar />
        <div v-if="flash" class="fixed right-4 top-4 z-50 mt-20">
            <div
                :class="toastClasses"
                class="flex items-center gap-4 rounded-md p-4 shadow-lg"
            >
                <span>{{ flash.name }}</span>
                <button @click="dismissToast" class="ml-4 text-xl font-bold">
                    &times;
                </button>
            </div>
        </div>
        <slot></slot>
    </div>
</template>
