<script>
import { useForm } from '@inertiajs/vue3';

export default {
    setup() {
        const form = useForm({
            phone: '',
            password: '',
        });

        const submit = () => {
            form.post('/login');
        };

        return {
            form,
            submit,
            errors: form.errors,
        };
    },
};
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-gray-100">
        <div class="w-full max-w-md rounded bg-white px-8 py-6 shadow-md">
            <h2 class="mb-6 text-center text-2xl font-bold">Login</h2>
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label
                        for="phone"
                        class="mb-2 block text-sm font-bold text-gray-700"
                        >Phone Number</label
                    >
                    <input
                        type="text"
                        id="phone"
                        v-model="form.phone"
                        class="w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none focus:ring"
                        placeholder="Enter your phone number"
                    />
                    <span v-if="errors.phone" class="text-sm text-red-500">{{
                        errors.phone
                    }}</span>
                </div>
                <div class="mb-4">
                    <label
                        for="password"
                        class="mb-2 block text-sm font-bold text-gray-700"
                        >Password</label
                    >
                    <input
                        type="password"
                        id="password"
                        v-model="form.password"
                        class="w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none focus:ring"
                        placeholder="Enter your password"
                    />
                    <span v-if="errors.password" class="text-sm text-red-500">{{
                        errors.password
                    }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <button
                        type="submit"
                        class="rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700 focus:outline-none focus:ring"
                    >
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
