<script setup>
import { useForm } from '@inertiajs/vue3';
const form = useForm({
    phone: '',
    password: '',
});

const submit = () => {
    form.post('/login');
};
</script>

<template>
    <Head title="Login"></Head>
    <div class="flex h-screen items-center bg-gray-100 py-16">
        <main id="content" class="mx-auto w-full max-w-md p-6">
            <div
                class="shadow-2xs mt-7 rounded-xl border border-gray-200 bg-white"
            >
                <div class="p-4 sm:p-7">
                    <div class="text-center">
                        <h1 class="block text-2xl font-bold text-gray-800">
                            Sign in
                        </h1>
                        <p class="mt-2 text-sm text-gray-600">
                            Don't have an account yet?
                            <Link
                                class="focus:outline-hidden font-medium text-blue-600 decoration-2 hover:underline focus:underline"
                                href="/signup"
                            >
                                Sign up here
                            </Link>
                        </p>
                    </div>

                    <div class="mt-5">
                        <!-- Form -->
                        <form @submit.prevent="submit">
                            <div class="grid gap-y-4">
                                <!-- Form Group -->
                                <div>
                                    <label
                                        for="phone"
                                        class="mb-2 block text-sm"
                                        >Phone number</label
                                    >
                                    <div class="relative">
                                        <input
                                            type="text"
                                            id="phone"
                                            name="phone"
                                            v-model="form.phone"
                                            class="block w-full rounded-lg border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 sm:py-3 sm:text-sm"
                                            required
                                            aria-describedby="phone-error"
                                        />
                                        <div
                                            class="pointer-events-none absolute inset-y-0 end-0 hidden pe-3"
                                        >
                                            <svg
                                                class="size-5 text-red-500"
                                                width="16"
                                                height="16"
                                                fill="currentColor"
                                                viewBox="0 0 16 16"
                                                aria-hidden="true"
                                            >
                                                <path
                                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"
                                                />
                                            </svg>
                                        </div>
                                    </div>
                                    <p
                                        v-if="form.errors.phone"
                                        v-text="form.errors.phone"
                                        class="mt-2 text-xs text-red-600"
                                        id="phone-error"
                                    ></p>
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div>
                                    <div
                                        class="flex flex-wrap items-center justify-between gap-2"
                                    >
                                        <label
                                            for="password"
                                            class="mb-2 block text-sm"
                                            >Password</label
                                        >
                                    </div>
                                    <div class="relative">
                                        <input
                                            type="password"
                                            id="password"
                                            name="password"
                                            v-model="form.password"
                                            class="block w-full rounded-lg border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 sm:py-3 sm:text-sm"
                                            required
                                            aria-describedby="password-error"
                                        />
                                        <div
                                            class="pointer-events-none absolute inset-y-0 end-0 hidden pe-3"
                                        >
                                            <svg
                                                class="size-5 text-red-500"
                                                width="16"
                                                height="16"
                                                fill="currentColor"
                                                viewBox="0 0 16 16"
                                                aria-hidden="true"
                                            >
                                                <path
                                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"
                                                />
                                            </svg>
                                        </div>
                                    </div>
                                    <p
                                        v-if="form.errors.password"
                                        v-text="form.errors.password"
                                        class="mt-2 text-xs text-red-600"
                                        id="password-error"
                                    ></p>
                                </div>
                                <!-- End Form Group -->

                                <button
                                    type="submit"
                                    class="focus:outline-hidden inline-flex w-full items-center justify-center gap-x-2 rounded-lg border border-transparent bg-blue-600 px-4 py-3 text-sm font-medium text-white hover:bg-blue-700 focus:bg-blue-700 disabled:pointer-events-none disabled:opacity-50"
                                >
                                    Sign in
                                </button>
                            </div>
                        </form>
                        <!-- End Form -->
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
