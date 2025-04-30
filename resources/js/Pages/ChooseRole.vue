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
            <div
                class="flex flex-col items-center justify-center space-y-6 md:flex-row md:space-x-6 md:space-y-0"
            >
                <div class="text-center">
                    <div
                        class="block rounded-lg bg-blue-600 px-8 py-4 font-semibold text-white shadow-md"
                    >
                        Supplier (Imorter/Wholesaler)
                    </div>
                    <div class="mt-4 space-y-2">
                        <Link
                            href="/supplier/login"
                            class="block rounded-lg bg-gray-200 px-6 py-2 font-medium text-gray-800 transition duration-300 hover:bg-gray-300"
                        >
                            Login as Supplier
                        </Link>
                        <Link
                            href="/supplier/signup"
                            class="block rounded-lg bg-gray-200 px-6 py-2 font-medium text-gray-800 transition duration-300 hover:bg-gray-300"
                        >
                            Signup as Supplier
                        </Link>
                    </div>
                </div>
                <div class="text-center">
                    <div
                        class="block rounded-lg bg-green-600 px-8 py-4 font-semibold text-white shadow-md"
                    >
                        Consumer (Wholesaler/Pharmacy/Hospital)
                    </div>
                    <div class="mt-4 space-y-2">
                        <Link
                            href="/login"
                            class="block rounded-lg bg-gray-200 px-6 py-2 font-medium text-gray-800 transition duration-300 hover:bg-gray-300"
                        >
                            Login as Consumer
                        </Link>
                        <Link
                            href="/signup"
                            class="block rounded-lg bg-gray-200 px-6 py-2 font-medium text-gray-800 transition duration-300 hover:bg-gray-300"
                        >
                            Signup as Consumer
                        </Link>
                    </div>
                </div>
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
