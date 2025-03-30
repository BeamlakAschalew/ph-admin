<script setup>
import { Link, useForm } from '@inertiajs/vue3';

let form = useForm({
    first_name: '',
    last_name: '',
    phone_number: '',
    password: '',
    password_confirmation: '',
});

let submit = () => {
    form.post('/signup');
};
</script>

<template>
    <div class="flex h-screen items-center bg-gray-100 py-16">
        <main id="content" class="mx-auto min-h-screen w-full max-w-md p-6">
            <div
                class="shadow-2xs mt-7 rounded-xl border border-gray-200 bg-white"
            >
                <div class="p-4 sm:p-7">
                    <div class="text-center">
                        <h1 class="block text-2xl font-bold text-gray-800">
                            Sign up
                        </h1>
                        <p class="mt-2 text-sm text-gray-600">
                            Already have an account?
                            <Link
                                class="focus:outline-hidden font-medium text-blue-600 decoration-2 hover:underline focus:underline"
                                href="/login"
                            >
                                Sign in here
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
                                        for="first_name"
                                        class="mb-2 block text-sm"
                                        >First name</label
                                    >
                                    <div class="relative">
                                        <input
                                            type="text"
                                            id="first_name"
                                            name="first_name"
                                            v-model="form.first_name"
                                            required
                                            class="block w-full rounded-lg border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 sm:py-3 sm:text-sm"
                                            aria-describedby="fullName-error"
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
                                        v-if="form.errors.first_name"
                                        v-text="form.errors.first_name"
                                        class="mt-2 text-xs text-red-600"
                                        id="firstName-error"
                                    ></p>
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div>
                                    <label
                                        for="last_name"
                                        class="mb-2 block text-sm"
                                        >Last name</label
                                    >
                                    <div class="relative">
                                        <input
                                            type="text"
                                            id="last_name"
                                            name="last_name"
                                            v-model="form.last_name"
                                            required
                                            class="block w-full rounded-lg border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 sm:py-3 sm:text-sm"
                                            aria-describedby="lastName-error"
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
                                        v-if="form.errors.last_name"
                                        v-text="form.errors.last_name"
                                        class="mt-2 text-xs text-red-600"
                                        id="firstName-error"
                                    ></p>
                                </div>
                                <!-- End Form Group -->

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
                                            v-model="form.phone_number"
                                            required
                                            class="block w-full rounded-lg border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 sm:py-3 sm:text-sm"
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
                                        v-if="form.errors.phone_number"
                                        v-text="form.errors.phone_number"
                                        class="mt-2 text-xs text-red-600"
                                        id="phone-error"
                                    ></p>
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div>
                                    <label
                                        for="password"
                                        class="mb-2 block text-sm"
                                        >Password</label
                                    >
                                    <div class="relative">
                                        <input
                                            type="password"
                                            id="password"
                                            name="password"
                                            v-model="form.password"
                                            required
                                            class="block w-full rounded-lg border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 sm:py-3 sm:text-sm"
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

                                <!-- Form Group -->
                                <div>
                                    <label
                                        for="confirm-password"
                                        class="mb-2 block text-sm"
                                        >Confirm Password</label
                                    >
                                    <div class="relative">
                                        <input
                                            type="password"
                                            id="confirm-password"
                                            name="password_confirmation"
                                            v-model="form.password_confirmation"
                                            required
                                            class="block w-full rounded-lg border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 sm:py-3 sm:text-sm"
                                            aria-describedby="password_confirm-error"
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
                                        v-if="form.errors.password_confirmation"
                                        v-text="
                                            form.errors.password_confirmation
                                        "
                                        class="mt-2 text-xs text-red-600"
                                        id="confirm-password-error"
                                    ></p>
                                </div>
                                <!-- End Form Group -->

                                <button
                                    type="submit"
                                    class="focus:outline-hidden inline-flex w-full items-center justify-center gap-x-2 rounded-lg border border-transparent bg-blue-600 px-4 py-3 text-sm font-medium text-white hover:bg-blue-700 focus:bg-blue-700 disabled:pointer-events-none disabled:opacity-50"
                                >
                                    Sign up
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
