<script setup>
import Navbar from '@/Components/Supplier/Navbar.vue';
import { useForm } from '@inertiajs/vue3';
import MainLayout from './MainLayout.vue';

defineProps({ subcities: Array });

defineOptions({
    layout: MainLayout,
});

const contactForm = useForm({
    name: '',
    phone: '',
    message: '',
    user: 'Supplier',
});

const submitContactForm = () => {
    contactForm.post('/contact', {
        onSuccess: () => {
            contactForm.reset();
        },
        onError: (errors) => {
            console.error('Form submission errors:', errors);
        },
    });
};
</script>

<template>
    <div class="flex min-h-screen flex-col bg-gray-50">
        <Navbar :subcities="subcities" />
        <!-- Hero Section -->
        <div class="flex flex-1 flex-col items-center justify-center gap-5">
            <div class="text-center text-4xl">
                The supplier feature is not developed yet
            </div>
            <div class="text-3xl">Contact +2519111111 for more</div>

            <div class="mx-2 my-10 w-full">
                <h2 class="mb-4 text-center text-2xl font-medium text-gray-800">
                    Contact Us
                </h2>
                <form
                    @submit.prevent="submitContactForm"
                    class="mx-auto max-w-3xl rounded-lg bg-white p-6 shadow-md"
                >
                    <div class="mb-4">
                        <label
                            for="name"
                            class="block text-sm font-medium text-gray-700"
                            >Name</label
                        >
                        <input
                            v-model="contactForm.name"
                            type="text"
                            id="name"
                            required
                            placeholder="Your name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600"
                        />
                    </div>
                    <div class="mb-4">
                        <label
                            for="phone"
                            class="block text-sm font-medium text-gray-700"
                            >Phone</label
                        >
                        <input
                            v-model="contactForm.phone"
                            type="text"
                            id="phone"
                            required
                            placeholder="Your Phone Number"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600"
                        />
                    </div>
                    <div class="mb-4">
                        <label
                            for="message"
                            class="block text-sm font-medium text-gray-700"
                            >Message</label
                        >
                        <textarea
                            v-model="contactForm.message"
                            required
                            id="message"
                            placeholder="Your Message"
                            rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600"
                        ></textarea>
                    </div>
                    <button
                        type="submit"
                        class="w-full rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
                        :disabled="contactForm.processing"
                    >
                        Send
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
