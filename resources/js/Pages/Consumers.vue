<script setup>
import { computed, ref } from 'vue';
import MainLayout from './MainLayout.vue';

defineOptions({
    layout: MainLayout,
});

// Consumers data
const consumers = ref([
    {
        id: 1,
        institution: "St. Paul's Hospital",
        contact: 'Hana Gebremichael',
        location: 'BOLE',
        locationDetail: 'Woreda 3, CMC Michael',
        phone: '+251987654321',
        phoneAlt: '+251911223344',
    },
    {
        id: 2,
        institution: 'Moges Pharmacy',
        contact: 'Dereje Moges',
        location: 'BOLE',
        locationDetail: 'Woreda 5, Edna Mall Area',
        phone: '+251966778899',
        phoneAlt: '+251911112233',
    },
    {
        id: 3,
        institution: 'Tadesse Pharmacy',
        contact: 'Getachew Tadesse',
        location: 'ADDIS KETEMA',
        locationDetail: 'Woreda 2, Piassa',
        phone: '+251977889900',
        phoneAlt: '+251912345678',
    },
    {
        id: 4,
        institution: 'Aster Pharmacy',
        contact: 'Aster Abebe',
        location: 'YEKKA',
        locationDetail: 'Woreda 4, Summit Area',
        phone: '+251911223344',
        phoneAlt: '+251922334455',
    },
    {
        id: 5,
        institution: 'Amanu Hospital',
        contact: 'Amanuel Teshome',
        location: 'ARADA',
        locationDetail: 'Woreda 4, Bole Medhanialem',
        phone: '+251922334455',
        phoneAlt: '+251933445566',
    },
    {
        id: 6,
        institution: 'Hana Pharmacy',
        contact: 'Hana Gebremariam',
        location: 'KIRKOS',
        locationDetail: 'Woreda 3, Near Dembel City Center',
        phone: '+251955667788',
        phoneAlt: '',
    },
]);

const searchQuery = ref('');

const filteredConsumers = computed(() => {
    if (!searchQuery.value) return consumers.value;

    const query = searchQuery.value.toLowerCase();
    return consumers.value.filter(
        (consumer) =>
            consumer.institution.toLowerCase().includes(query) ||
            consumer.contact.toLowerCase().includes(query) ||
            consumer.location.toLowerCase().includes(query) ||
            consumer.phone.includes(query),
    );
});

const editConsumer = (id) => {
    // Implement edit functionality
    console.log('Edit consumer', id);
};

const deleteConsumer = (id) => {
    // Implement delete functionality
    console.log('Delete consumer', id);
};
</script>

<template>
    <!-- Main Content -->
    <main class="flex-1">
        <div class="mx-auto w-full max-w-4xl px-4 py-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-800">Consumers List</h1>
                <button
                    class="inline-flex items-center justify-center rounded-full bg-teal-600 p-2 text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="h-6 w-6"
                    >
                        <path d="M5 12h14"></path>
                        <path d="M12 5v14"></path>
                    </svg>
                </button>
            </div>

            <!-- Search Bar -->
            <div class="mb-6">
                <input
                    type="text"
                    v-model="searchQuery"
                    class="block w-full rounded-lg border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Search users..."
                />
            </div>

            <!-- Consumers Table -->
            <div class="overflow-hidden rounded-xl border bg-white shadow-sm">
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
                            <tr
                                v-for="consumer in filteredConsumers"
                                :key="consumer.id"
                                class="hover:bg-gray-100"
                            >
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ consumer.institution }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ consumer.contact }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ consumer.location }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ consumer.locationDetail }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ consumer.phone }}
                                    </div>
                                    <div
                                        v-if="consumer.phoneAlt"
                                        class="text-sm text-gray-500"
                                    >
                                        {{ consumer.phoneAlt }}
                                    </div>
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 text-end"
                                >
                                    <div class="flex justify-end gap-2">
                                        <button
                                            @click="editConsumer(consumer.id)"
                                            class="inline-flex items-center gap-x-1 rounded-lg border border-transparent bg-blue-500 px-3 py-1 text-sm font-medium text-white hover:bg-blue-600"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            @click="deleteConsumer(consumer.id)"
                                            class="inline-flex items-center gap-x-1 rounded-lg border border-gray-300 bg-red-500 px-3 py-1 text-sm font-medium text-white hover:bg-gray-500"
                                        >
                                            Delete
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
</template>
