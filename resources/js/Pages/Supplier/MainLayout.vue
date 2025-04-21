<script setup>
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
        <!-- Footer -->
        <footer class="mt-auto border-t border-gray-200 bg-white">
            <div
                class="mx-auto max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:pt-20"
            >
                <!-- Grid -->
                <div
                    class="flex flex-col items-start justify-between gap-6 sm:flex-row"
                >
                    <div class="flex-shrink-0">
                        <a
                            class="text-primary text-xl font-semibold"
                            href="#"
                            aria-label="PH"
                        >
                            <img src="/logo.png" class="mr-2 h-16 w-16" />
                            PH
                        </a>
                        <p class="mt-3 text-xs text-gray-600 sm:text-sm">
                            Your trusted source for quality medications,
                            cosmetics and health products.
                        </p>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900">Email</h4>
                        <div class="my-3">
                            <p class="text-sm text-gray-600">
                                ph1holders@gmail.com
                            </p>
                        </div>
                        <h4 class="font-semibold text-gray-900">Phone</h4>
                        <div class="mt-3">
                            <p class="text-sm text-gray-600">0950096667</p>
                            <p class="text-sm text-gray-600">0950116667</p>
                        </div>
                    </div>
                </div>
                <!-- End Grid -->

                <div
                    class="mt-12 grid gap-y-2 sm:flex sm:items-center sm:justify-between sm:gap-y-0"
                >
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600">
                            © 2025 PH. All rights reserved.
                        </p>
                    </div>
                    <!-- End Col -->

                    <div class="flex items-center">
                        <a
                            href="https://beamlak.dev"
                            target="_blank"
                            class="text-sm text-gray-600 hover:underline"
                        >
                            Website developed by Beamlak Aschalew - Contact me:
                            0936648802
                        </a>
                    </div>

                    <!-- Social Brands -->
                    <div>
                        <a
                            class="inline-flex size-36 items-center justify-center gap-x-2 rounded-lg border border-transparent text-sm font-semibold text-gray-500 hover:bg-gray-100 disabled:pointer-events-none disabled:opacity-50"
                            href="https://t.me/ph10101"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="36"
                                height="36"
                                fill="currentColor"
                                class="bi bi-telegram"
                                viewBox="0 0 16 16"
                            >
                                <path
                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.287 5.906q-1.168.486-4.666 2.01-.567.225-.595.442c-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294q.39.01.868-.32 3.269-2.206 3.374-2.23c.05-.012.12-.026.166.016s.042.12.037.141c-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8 8 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629q.14.092.27.187c.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.4 1.4 0 0 0-.013-.315.34.34 0 0 0-.114-.217.53.53 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09"
                                />
                            </svg>
                        </a>
                    </div>
                    <!-- End Social Brands -->
                </div>
            </div>
        </footer>
    </div>
</template>
