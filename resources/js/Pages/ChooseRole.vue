<template>
    <div
        class="flex h-screen items-center justify-center bg-gradient-to-r from-blue-500 to-green-500"
    >
        <div class="rounded-lg bg-white p-10 text-center shadow-lg">
            <h1 class="mb-6 text-3xl font-extrabold text-gray-800">
                Choose Your Role
            </h1>
            <p class="mb-8 text-gray-600">
                Please select whether you are a Supplier or a Consumer to
                proceed.
            </p>
            <div class="space-x-4">
                <router-link
                    to="/supplier"
                    class="rounded-lg bg-blue-600 px-8 py-4 font-semibold text-white shadow-md transition duration-300 hover:bg-blue-700"
                >
                    Supplier
                </router-link>
                <router-link
                    to="/consumer"
                    class="rounded-lg bg-green-600 px-8 py-4 font-semibold text-white shadow-md transition duration-300 hover:bg-green-700"
                >
                    Consumer
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
// No additional logic needed for this simple page
</script>

<style scoped>
/* Add any custom styles here if needed */
</style>
