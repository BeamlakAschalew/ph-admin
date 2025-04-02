<script setup>
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { computed, ref, watch } from 'vue';

import MainLayout from './MainLayout.vue';

defineOptions({
    layout: MainLayout,
});

const props = defineProps({
    suppliers: {
        type: Object,
    },
});

// Use local copy for consumers data
const localSuppliers = ref(props.suppliers.data);

watch(
    () => props.suppliers,
    (newSuppliers) => {
        localSuppliers.value = newSuppliers.data;
    },
);

// Search functionality for consumers only
const searchQuery = ref('');

const debouncedSearch = debounce((query) => {
    router.get(
        '/pending-suppliers',
        { search: query },
        {
            preserveState: true,
            replace: true,
        },
    );
}, 300);

watch(searchQuery, (newQuery) => {
    debouncedSearch(newQuery);
});

// Pagination links for consumers
const paginationLinks = computed(() => {
    return props.suppliers.links;
});

// Approve and reject functionality for consumers
const approveUser = (id) => {
    router.post(
        '/pending-suppliers',
        {
            id: id,
            action: 'approve',
        },
        {
            preserveScroll: true,
            preserveState: true,
        },
    );
};

const rejectUser = (id) => {
    router.post(
        '/pending-suppliers',
        {
            id: id,
            action: 'reject',
        },
        {
            preserveScroll: true,
            preserveState: true,
        },
    );
};
</script>
<template>
    <Head title="Pending suppliers" />
    <div class="flex min-h-screen flex-col">
        <!-- Main Content -->
        <main class="flex-1">
            <div class="mx-auto w-full max-w-6xl px-4 py-6">
                <div class="mb-6 flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-gray-800">
                        Pending Suppliers
                    </h1>
                </div>

                <!-- Search Bar -->
                <div class="mb-6">
                    <input
                        type="text"
                        v-model="searchQuery"
                        class="block w-full rounded-lg border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Search pending consumers..."
                    />
                </div>

                <!-- Pending Users Table -->
                <div
                    class="mt-6 overflow-hidden rounded-xl border bg-white shadow-sm"
                >
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                    >
                                        Institution
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                    >
                                        Location
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                    >
                                        Phone
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-end text-xs font-medium uppercase text-gray-500"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-if="localSuppliers.length === 0">
                                    <td
                                        colspan="4"
                                        class="px-6 py-8 text-center text-gray-500"
                                    >
                                        No pending consumers found
                                    </td>
                                </tr>
                                <tr
                                    v-for="user in localSuppliers"
                                    :key="user.id"
                                    class="hover:bg-gray-100"
                                >
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ user.institution_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ user.first_name }}
                                            {{ user.last_name }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ user.subcity.subcity_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Woreda {{ user.woreda }}
                                            {{ user.special_place }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            +251{{ user.primary_phone }}
                                        </div>
                                        <div
                                            v-if="user.secondary_phone"
                                            class="text-sm text-gray-500"
                                        >
                                            +251{{ user.secondary_phone }}
                                        </div>
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-end"
                                    >
                                        <div class="flex justify-end gap-2">
                                            <button
                                                @click="approveUser(user.id)"
                                                class="inline-flex items-center gap-x-1 rounded-lg border border-transparent bg-green-500 px-3 py-1 text-sm font-medium text-white hover:bg-green-600"
                                            >
                                                Approve
                                            </button>
                                            <button
                                                @click="rejectUser(user.id)"
                                                class="inline-flex items-center gap-x-1 rounded-lg border border-transparent bg-red-500 px-3 py-1 text-sm font-medium text-white hover:bg-red-600"
                                            >
                                                Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Pagination -->
                <nav
                    class="mt-5 flex items-center -space-x-px"
                    aria-label="Pagination"
                >
                    <template v-if="paginationLinks && paginationLinks.length">
                        <template
                            v-for="(link, index) in paginationLinks"
                            :key="index"
                        >
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                preserve-state
                                preserve-scroll
                                :class="[
                                    index === 0 ? 'rounded-l-md' : '',
                                    index === paginationLinks.length - 1
                                        ? 'rounded-r-md'
                                        : '',
                                    'min-h-9.5 min-w-9.5 flex items-center justify-center px-3 py-2 text-sm',
                                    link.active
                                        ? 'bg-gray-600 text-white'
                                        : 'border border-gray-200 text-gray-800 hover:bg-gray-100',
                                ]"
                            >
                                <span v-html="link.label"></span>
                            </Link>
                            <span
                                v-else
                                v-html="link.label"
                                :class="[
                                    index === 0 ? 'rounded-l-md' : '',
                                    index === paginationLinks.length - 1
                                        ? 'rounded-r-md'
                                        : '',
                                    'min-h-9.5 min-w-9.5 flex items-center justify-center border border-gray-200 px-3 py-2 text-gray-800',
                                ]"
                            ></span>
                        </template>
                    </template>
                </nav>
                <!-- End Pagination -->
            </div>
        </main>
    </div>
</template>
