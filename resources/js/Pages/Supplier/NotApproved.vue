<script setup>
import MainLayout from './MainLayout.vue';

defineOptions({
    layout: MainLayout,
});
</script>

<template>
    <Head title="Not approved" />
    <div class="flex h-full flex-1 items-center justify-center">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-800">Not Approved</h1>
            <p class="mt-4 text-lg text-gray-600">
                Your account is not approved yet. Please contact support for
                more information.
            </p>
            <div class="mt-6 flex items-center justify-center gap-3">
                <Link
                    href="/supplier"
                    class="mt-6 inline-block rounded-md bg-blue-500 px-4 py-2 text-white hover:bg-blue-600"
                >
                    Go to Home
                </Link>
                <Link
                    href="/supplier/logout"
                    method="post"
                    as="button"
                    class="mt-6 inline-block rounded-md bg-red-500 px-4 py-2 text-white hover:bg-red-600"
                >
                    Logout
                </Link>
            </div>
        </div>
    </div>
</template>
