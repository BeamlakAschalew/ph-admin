<script setup>
import { computed, ref } from 'vue';
import MainLayout from './MainLayout.vue';

defineOptions({
    layout: MainLayout,
});

// Active tab state
const activeTab = ref('consumers');

// Pending users data
const pendingUsers = ref({
    consumers: [
        {
            id: 1,
            institution: 'Tikur Anbessa Hospital',
            contact: 'Amanuel Tadesse',
            location: 'ARADA',
            locationDetail: 'Woreda 4, Bole Medhanialem',
            phone: '+251922334455',
            phoneAlt: '+251933445566',
        },
        {
            id: 2,
            institution: 'Example Inc',
            contact: 'Supplier Test',
            location: 'ARADA',
            locationDetail: 'Woreda 7, Lideta area street',
            phone: '+251911223345',
            phoneAlt: '0911223345',
        },
    ],
    suppliers: [
        {
            id: 3,
            institution: 'Teshome Med Supplies',
            contact: 'Meles Teshome',
            location: 'AKAKI_KALITY',
            locationDetail: 'Woreda 2, Kality Market',
            phone: '+251966778899',
            phoneAlt: '',
        },
        {
            id: 4,
            institution: 'Abyssinia Pharmaceuticals',
            contact: 'Selam Haile',
            location: 'BOLE',
            locationDetail: 'Woreda 5, Gerji Area',
            phone: '+251911334455',
            phoneAlt: '+251922445566',
        },
    ],
});

// Search functionality
const searchQuery = ref('');

const filteredUsers = computed(() => {
    if (!searchQuery.value) return pendingUsers.value[activeTab.value];

    const query = searchQuery.value.toLowerCase();
    return pendingUsers.value[activeTab.value].filter(
        (user) =>
            user.institution.toLowerCase().includes(query) ||
            user.contact.toLowerCase().includes(query) ||
            user.location.toLowerCase().includes(query) ||
            user.phone.includes(query),
    );
});

// Approve and reject functionality
const approveUser = (id) => {
    // Implement approve functionality
    console.log('Approve user', id);
    // Remove from pending list
    pendingUsers.value[activeTab.value] = pendingUsers.value[
        activeTab.value
    ].filter((user) => user.id !== id);
};

const rejectUser = (id) => {
    // Implement reject functionality
    console.log('Reject user', id);
    // Remove from pending list
    pendingUsers.value[activeTab.value] = pendingUsers.value[
        activeTab.value
    ].filter((user) => user.id !== id);
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
                                            {{ user.institution }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ user.contact }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ user.location }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ user.locationDetail }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ user.phone }}
                                        </div>
                                        <div
                                            v-if="user.phoneAlt"
                                            class="text-sm text-gray-500"
                                        >
                                            {{ user.phoneAlt }}
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
            </div>
        </main>
    </div>
</template>
