<script setup>
import { computed, ref } from 'vue';
import MainLayout from './MainLayout.vue';

defineOptions({
    layout: MainLayout,
});

const props = defineProps({
    consumers: {
        type: Object,
    },
    suppliers: {
        type: Object,
    },
});

// Active tab state
const activeTab = ref('consumers');

// Use local copies for table data
const localConsumers = ref(props.consumers.data);
const localSuppliers = ref(props.suppliers.data);

// Search functionality using local data based on the active tab
const searchQuery = ref('');

const filteredUsers = computed(() => {
    const currentUsers =
        activeTab.value === 'consumers'
            ? localConsumers.value
            : localSuppliers.value;
    if (!searchQuery.value) return currentUsers;

    const query = searchQuery.value.toLowerCase();
    return currentUsers.filter(
        (user) =>
            user.institution.toLowerCase().includes(query) ||
            user.contact.toLowerCase().includes(query) ||
            user.location.toLowerCase().includes(query) ||
            user.phone.includes(query),
    );
});

// Dynamic pagination links based on active tab
const paginationLinks = computed(() => {
    return activeTab.value === 'consumers'
        ? props.consumers.links
        : props.suppliers.links;
});

// Approve and reject functionality using local copies
const approveUser = (id) => {
    console.log('Approve user', id);
    if (activeTab.value === 'consumers') {
        localCounsumers.value = localConsumers.value.filter(
            (user) => user.id !== id,
        );
    } else {
        localSuppliers.value = localSuppliers.value.filter(
            (user) => user.id !== id,
        );
    }
};

const rejectUser = (id) => {
    console.log('Reject user', id);
    if (activeTab.value === 'consumers') {
        localCounsumers.value = localConsumers.value.filter(
            (user) => user.id !== id,
        );
    } else {
        localSuppliers.value = localSuppliers.value.filter(
            (user) => user.id !== id,
        );
    }
};
</script>
<template>
    <div class="flex min-h-screen flex-col">
        <!-- Main Content -->
        <main class="flex-1">
            <div class="mx-auto w-full max-w-6xl px-4 py-6">
                <div class="mb-6 flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-gray-800">
                        Pending Users
                    </h1>
                </div>

                <!-- Search Bar -->
                <div class="mb-6">
                    <input
                        type="text"
                        v-model="searchQuery"
                        class="block w-full rounded-lg border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Search pending users..."
                    />
                </div>

                <!-- Tabs -->
                <div class="mb-5 border-b border-blue-200">
                    <nav class="flex space-x-12" aria-label="Tabs">
                        <button
                            @click="activeTab = 'consumers'"
                            class="inline-flex items-center gap-x-2 whitespace-nowrap border-b-2 px-4 py-4 text-sm font-medium"
                            :class="
                                activeTab === 'consumers'
                                    ? 'border-blue-600 text-blue-600'
                                    : 'border-transparent text-gray-500 hover:border-blue-300 hover:text-blue-600'
                            "
                        >
                            Consumers
                        </button>
                        <button
                            @click="activeTab = 'suppliers'"
                            class="inline-flex items-center gap-x-2 whitespace-nowrap border-b-2 px-4 py-4 text-sm font-medium"
                            :class="
                                activeTab === 'suppliers'
                                    ? 'border-blue-600 text-blue-600'
                                    : 'border-transparent text-gray-500 hover:border-blue-300 hover:text-blue-600'
                            "
                        >
                            Suppliers
                        </button>
                    </nav>
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
                                <tr v-if="filteredUsers.length === 0">
                                    <td
                                        colspan="4"
                                        class="px-6 py-8 text-center text-gray-500"
                                    >
                                        No pending {{ activeTab }} found
                                    </td>
                                </tr>
                                <tr
                                    v-for="user in filteredUsers"
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
                                            v-if="user.phoneAlt"
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
