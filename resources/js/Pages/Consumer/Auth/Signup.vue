<script setup>
import { useForm } from '@inertiajs/vue3';
import { defineProps } from 'vue';

defineProps(['subcities']); // Subcity data passed via props

let form = useForm({
    first_name: '',
    last_name: '',
    institution_name: '',
    woreda: '',
    primary_phone: '',
    secondary_phone: '',
    license_number: '',
    subcity_id: '',
    special_place: '',
    password: '',
    password_confirmation: '',
    superadmin_secret: '',
});

let submit = () => {
    form.post('/admin/signup');
};
</script>

<template>
    <Head title="Signup"></Head>
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
                                href="/admin/login"
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

                                <!-- Institution Name -->
                                <div>
                                    <label
                                        for="institution_name"
                                        class="mb-2 block text-sm"
                                        >Institution Name</label
                                    >
                                    <input
                                        type="text"
                                        id="institution_name"
                                        v-model="form.institution_name"
                                        required
                                        class="block w-full rounded-lg border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500 sm:py-3 sm:text-sm"
                                    />
                                    <p
                                        v-if="form.errors.institution_name"
                                        v-text="form.errors.institution_name"
                                        class="mt-2 text-xs text-red-600"
                                    ></p>
                                </div>

                                <!-- Woreda -->
                                <div>
                                    <label
                                        for="woreda"
                                        class="mb-2 block text-sm"
                                        >Woreda</label
                                    >
                                    <input
                                        type="number"
                                        id="woreda"
                                        v-model="form.woreda"
                                        min="1"
                                        max="14"
                                        required
                                        class="block w-full rounded-lg border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500 sm:py-3 sm:text-sm"
                                    />
                                    <p
                                        v-if="form.errors.woreda"
                                        v-text="form.errors.woreda"
                                        class="mt-2 text-xs text-red-600"
                                    ></p>
                                </div>

                                <!-- Primary Phone -->
                                <div>
                                    <label
                                        for="primary_phone"
                                        class="mb-2 block text-sm"
                                        >Primary Phone</label
                                    >
                                    <input
                                        type="text"
                                        id="primary_phone"
                                        v-model="form.primary_phone"
                                        required
                                        class="block w-full rounded-lg border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500 sm:py-3 sm:text-sm"
                                    />
                                    <p
                                        v-if="form.errors.primary_phone"
                                        v-text="form.errors.primary_phone"
                                        class="mt-2 text-xs text-red-600"
                                    ></p>
                                </div>

                                <!-- Secondary Phone -->
                                <div>
                                    <label
                                        for="secondary_phone"
                                        class="mb-2 block text-sm"
                                        >Secondary Phone</label
                                    >
                                    <input
                                        type="text"
                                        id="secondary_phone"
                                        v-model="form.secondary_phone"
                                        class="block w-full rounded-lg border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500 sm:py-3 sm:text-sm"
                                    />
                                    <p
                                        v-if="form.errors.secondary_phone"
                                        v-text="form.errors.secondary_phone"
                                        class="mt-2 text-xs text-red-600"
                                    ></p>
                                </div>

                                <!-- License Number -->
                                <div>
                                    <label
                                        for="license_number"
                                        class="mb-2 block text-sm"
                                        >License Number</label
                                    >
                                    <input
                                        type="text"
                                        id="license_number"
                                        v-model="form.license_number"
                                        required
                                        class="block w-full rounded-lg border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500 sm:py-3 sm:text-sm"
                                    />
                                    <p
                                        v-if="form.errors.license_number"
                                        v-text="form.errors.license_number"
                                        class="mt-2 text-xs text-red-600"
                                    ></p>
                                </div>

                                <!-- Subcity -->
                                <div>
                                    <label
                                        for="subcity"
                                        class="mb-2 block text-sm"
                                        >Subcity</label
                                    >
                                    <select
                                        id="subcity"
                                        v-model="form.subcity_id"
                                        required
                                        class="block w-full rounded-lg border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500 sm:py-3 sm:text-sm"
                                    >
                                        <option value="" disabled>
                                            Select Subcity
                                        </option>
                                        <option
                                            v-for="subcity in subcities"
                                            :key="subcity.id"
                                            :value="subcity.id"
                                        >
                                            {{ subcity.name }}
                                        </option>
                                    </select>
                                    <p
                                        v-if="form.errors.subcity_id"
                                        v-text="form.errors.subcity_id"
                                        class="mt-2 text-xs text-red-600"
                                    ></p>
                                </div>

                                <!-- Special Place -->
                                <div>
                                    <label
                                        for="special_place"
                                        class="mb-2 block text-sm"
                                        >Special Place</label
                                    >
                                    <input
                                        type="text"
                                        id="special_place"
                                        v-model="form.special_place"
                                        class="block w-full rounded-lg border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500 sm:py-3 sm:text-sm"
                                    />
                                    <p
                                        v-if="form.errors.special_place"
                                        v-text="form.errors.special_place"
                                        class="mt-2 text-xs text-red-600"
                                    ></p>
                                </div>

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

                                <!-- Form Group -->
                                <div>
                                    <label
                                        for="superadmin_secret"
                                        class="mb-2 block text-sm"
                                        >Superadmin secret</label
                                    >
                                    <div class="relative">
                                        <input
                                            type="text"
                                            id="superadmin_secret"
                                            name="superadmin_secret"
                                            v-model="form.superadmin_secret"
                                            class="block w-full rounded-lg border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 sm:py-3 sm:text-sm"
                                            aria-describedby="superAdmin-error"
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
                                        v-if="form.errors.superadmin_secret"
                                        v-text="form.errors.superadmin_secret"
                                        class="mt-2 text-xs text-red-600"
                                        id="firstName-error"
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
