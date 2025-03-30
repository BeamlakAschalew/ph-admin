<template>
    <Head title="Dashboard"></Head>
    <div class="flex min-h-screen flex-col">
        <!-- Preline UI Navbar -->
        <header
            class="z-50 flex w-full flex-wrap bg-blue-900 py-4 text-sm sm:flex-nowrap sm:justify-start"
        >
            <nav
                class="mx-auto w-full max-w-full px-4 sm:flex sm:items-center sm:justify-between"
                aria-label="Global"
            >
                <div class="flex items-center justify-between">
                    <a
                        class="flex-none text-xl font-bold text-amber-500"
                        href="#"
                        >PH</a
                    >
                    <div class="sm:hidden">
                        <button
                            type="button"
                            class="inline-flex items-center justify-center gap-2 rounded-md border border-gray-700 p-2 text-gray-200 transition-all hover:bg-blue-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 focus:ring-offset-blue-800"
                            @click="mobileMenuOpen = !mobileMenuOpen"
                        >
                            <svg
                                v-if="!mobileMenuOpen"
                                class="h-4 w-4"
                                width="16"
                                height="16"
                                fill="currentColor"
                                viewBox="0 0 16 16"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"
                                />
                            </svg>
                            <svg
                                v-else
                                class="h-4 w-4"
                                width="16"
                                height="16"
                                fill="currentColor"
                                viewBox="0 0 16 16"
                            >
                                <path
                                    d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
                <div
                    :class="{ hidden: !mobileMenuOpen }"
                    class="grow basis-full overflow-hidden transition-all duration-300 sm:block"
                >
                    <div
                        class="mt-5 flex flex-col gap-5 sm:mt-0 sm:flex-row sm:items-center sm:justify-end sm:pl-5"
                    >
                        <a
                            class="font-medium text-white"
                            href="#"
                            aria-current="page"
                            >Home</a
                        >
                        <a
                            class="font-medium text-gray-300 hover:text-white"
                            href="#"
                            >Pending Users</a
                        >
                        <div class="relative">
                            <button
                                type="button"
                                @click="usersDropdownOpen = !usersDropdownOpen"
                                class="flex w-full items-center font-medium text-gray-300 hover:text-white"
                            >
                                Users
                                <svg
                                    class="ml-2 h-2.5 w-2.5"
                                    width="16"
                                    height="16"
                                    viewBox="0 0 16 16"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M2 5L8.16086 10.6869C8.35239 10.8637 8.64761 10.8637 8.83914 10.6869L15 5"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                    ></path>
                                </svg>
                            </button>
                            <div
                                v-show="usersDropdownOpen"
                                class="absolute left-0 z-10 mt-2 w-48 rounded-lg bg-white p-2 shadow-md"
                            >
                                <a
                                    class="flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500"
                                    href="#"
                                >
                                    Manage Users
                                </a>
                                <a
                                    class="flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500"
                                    href="#"
                                >
                                    Add User
                                </a>
                                <a
                                    class="flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500"
                                    href="#"
                                >
                                    User Roles
                                </a>
                            </div>
                        </div>
                        <button
                            type="button"
                            class="text-gray-300 hover:text-white"
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
                                class="h-5 w-5"
                            >
                                <circle cx="12" cy="12" r="4"></circle>
                                <path d="M12 2v2"></path>
                                <path d="M12 20v2"></path>
                                <path d="m4.93 4.93 1.41 1.41"></path>
                                <path d="m17.66 17.66 1.41 1.41"></path>
                                <path d="M2 12h2"></path>
                                <path d="M20 12h2"></path>
                                <path d="m6.34 17.66-1.41 1.41"></path>
                                <path d="m19.07 4.93-1.41 1.41"></path>
                            </svg>
                        </button>
                        <form @submit.prevent="logout">
                            <button
                                type="submit"
                                class="font-medium text-gray-300 hover:text-white"
                            >
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="bg-white-50 flex-1">
            <div class="mx-auto w-full px-2 py-6 sm:px-4">
                <div class="flex flex-col gap-6 2xl:flex-row">
                    <!-- Orders List -->
                    <div class="w-full 2xl:w-1/2">
                        <h2 class="mb-4 text-xl font-bold text-gray-800">
                            Orders List
                        </h2>

                        <!-- Tabs -->
                        <div class="mb-4 border-b border-gray-200">
                            <nav class="flex space-x-2" aria-label="Tabs">
                                <button
                                    v-for="tab in tabs"
                                    :key="tab.value"
                                    @click="activeTab = tab.value"
                                    class="inline-flex items-center gap-x-2 whitespace-nowrap border-b-2 px-1 py-4 text-sm"
                                    :class="
                                        activeTab === tab.value
                                            ? 'border-blue-600 font-semibold text-blue-600'
                                            : 'border-transparent text-gray-500 hover:border-blue-600 hover:text-blue-600'
                                    "
                                >
                                    {{ tab.label }}
                                    <span
                                        class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium"
                                        :class="getStatusBadgeClass(tab.value)"
                                    >
                                        {{ getOrderCountByStatus(tab.value) }}
                                    </span>
                                </button>
                            </nav>
                        </div>

                        <div class="rounded-xl border bg-white shadow-sm">
                            <div class="overflow-x-auto">
                                <table
                                    class="min-w-full divide-y divide-gray-200"
                                >
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                scope="col"
                                                class="px-6 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                            >
                                                Consumer
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
                                                class="px-6 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                            >
                                                Status
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-3 text-end text-xs font-medium uppercase text-gray-500"
                                            >
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <template
                                            v-if="filteredOrders.length === 0"
                                        >
                                            <tr>
                                                <td
                                                    colspan="5"
                                                    class="px-6 py-8 text-center text-gray-500"
                                                >
                                                    No
                                                    {{
                                                        activeTab.toLowerCase()
                                                    }}
                                                    orders found
                                                </td>
                                            </tr>
                                        </template>
                                        <template
                                            v-for="order in filteredOrders"
                                            :key="order.id"
                                        >
                                            <!-- Main Row -->
                                            <tr class="hover:bg-gray-100">
                                                <td
                                                    class="whitespace-nowrap px-6 py-4"
                                                >
                                                    <div
                                                        class="text-sm font-medium text-gray-900"
                                                    >
                                                        {{ order.consumer }}
                                                    </div>
                                                    <div
                                                        class="text-sm text-gray-500"
                                                    >
                                                        {{
                                                            order.consumerDetail
                                                        }}
                                                    </div>
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-6 py-4"
                                                >
                                                    <div
                                                        class="text-sm text-gray-900"
                                                    >
                                                        {{ order.location }}
                                                    </div>
                                                    <div
                                                        class="text-sm text-gray-500"
                                                    >
                                                        {{
                                                            order.locationDetail
                                                        }}
                                                    </div>
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-6 py-4"
                                                >
                                                    <div
                                                        class="text-sm text-gray-900"
                                                    >
                                                        {{ order.phone }}
                                                    </div>
                                                    <div
                                                        class="text-sm text-gray-500"
                                                    >
                                                        {{ order.phoneAlt }}
                                                    </div>
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-6 py-4"
                                                >
                                                    <!-- Simple Status Buttons -->
                                                    <div
                                                        class="flex flex-col gap-2"
                                                    >
                                                        <button
                                                            class="inline-flex items-center gap-x-2 rounded-lg border border-gray-200 px-3 py-1 text-sm font-medium text-white"
                                                            :class="{
                                                                'bg-green-500 hover:bg-green-600':
                                                                    order.status ===
                                                                    'Delivered',
                                                                'bg-yellow-500 hover:bg-yellow-600':
                                                                    order.status ===
                                                                    'Pending',
                                                                'bg-red-500 hover:bg-red-600':
                                                                    order.status ===
                                                                    'Cancelled',
                                                            }"
                                                        >
                                                            {{ order.status }}
                                                        </button>

                                                        <!-- Simple Status Options -->
                                                        <div
                                                            class="mt-1 flex flex-col gap-1"
                                                            v-show="
                                                                order.id ===
                                                                activeDropdown
                                                            "
                                                        >
                                                            <button
                                                                v-if="
                                                                    order.status !==
                                                                    'Delivered'
                                                                "
                                                                @click="
                                                                    setOrderStatus(
                                                                        order.id,
                                                                        'Delivered',
                                                                    )
                                                                "
                                                                class="rounded bg-green-500 px-2 py-1 text-xs font-medium text-white hover:bg-green-600"
                                                            >
                                                                Set Delivered
                                                            </button>
                                                            <button
                                                                v-if="
                                                                    order.status !==
                                                                    'Pending'
                                                                "
                                                                @click="
                                                                    setOrderStatus(
                                                                        order.id,
                                                                        'Pending',
                                                                    )
                                                                "
                                                                class="rounded bg-yellow-500 px-2 py-1 text-xs font-medium text-white hover:bg-yellow-600"
                                                            >
                                                                Set Pending
                                                            </button>
                                                            <button
                                                                v-if="
                                                                    order.status !==
                                                                    'Cancelled'
                                                                "
                                                                @click="
                                                                    setOrderStatus(
                                                                        order.id,
                                                                        'Cancelled',
                                                                    )
                                                                "
                                                                class="rounded bg-red-500 px-2 py-1 text-xs font-medium text-white hover:bg-red-600"
                                                            >
                                                                Set Cancelled
                                                            </button>
                                                        </div>

                                                        <!-- Toggle Button -->
                                                        <button
                                                            @click="
                                                                toggleStatusOptions(
                                                                    order.id,
                                                                )
                                                            "
                                                            class="rounded bg-gray-200 px-2 py-1 text-xs font-medium text-gray-700 hover:bg-gray-300"
                                                        >
                                                            {{
                                                                order.id ===
                                                                activeDropdown
                                                                    ? 'Hide Options'
                                                                    : 'Change Status'
                                                            }}
                                                        </button>
                                                    </div>
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-6 py-4 text-end"
                                                >
                                                    <button
                                                        @click="
                                                            toggleExpand(
                                                                order.id,
                                                            )
                                                        "
                                                        class="inline-flex items-center justify-center rounded-full border border-gray-200 bg-white p-1 text-gray-500 hover:bg-gray-100"
                                                    >
                                                        <svg
                                                            class="h-5 w-5 transition-transform duration-200"
                                                            :class="{
                                                                'rotate-180':
                                                                    order.expanded,
                                                            }"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            width="24"
                                                            height="24"
                                                            viewBox="0 0 24 24"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            stroke-width="2"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                        >
                                                            <path
                                                                d="m6 9 6 6 6-6"
                                                            />
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Expanded Details Row -->
                                            <tr
                                                v-if="order.expanded"
                                                class="bg-gray-50"
                                            >
                                                <td
                                                    colspan="5"
                                                    class="px-6 py-4"
                                                >
                                                    <div
                                                        class="overflow-x-auto"
                                                    >
                                                        <table
                                                            class="min-w-full divide-y divide-gray-200"
                                                        >
                                                            <thead
                                                                class="bg-gray-100"
                                                            >
                                                                <tr>
                                                                    <th
                                                                        scope="col"
                                                                        class="px-6 py-2 text-start text-xs font-medium uppercase text-gray-500"
                                                                    >
                                                                        Medicine
                                                                        Name
                                                                    </th>
                                                                    <th
                                                                        scope="col"
                                                                        class="px-6 py-2 text-start text-xs font-medium uppercase text-gray-500"
                                                                    >
                                                                        Unit
                                                                    </th>
                                                                    <th
                                                                        scope="col"
                                                                        class="px-6 py-2 text-start text-xs font-medium uppercase text-gray-500"
                                                                    >
                                                                        Quantity
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody
                                                                class="divide-y divide-gray-200"
                                                            >
                                                                <tr
                                                                    v-for="(
                                                                        medicine,
                                                                        medIndex
                                                                    ) in order.medicines"
                                                                    :key="
                                                                        medIndex
                                                                    "
                                                                    class="hover:bg-gray-100"
                                                                >
                                                                    <td
                                                                        class="whitespace-nowrap px-6 py-2 text-sm text-gray-900"
                                                                    >
                                                                        {{
                                                                            medicine.name
                                                                        }}
                                                                    </td>
                                                                    <td
                                                                        class="whitespace-nowrap px-6 py-2 text-sm text-gray-900"
                                                                    >
                                                                        {{
                                                                            medicine.unit
                                                                        }}
                                                                    </td>
                                                                    <td
                                                                        class="whitespace-nowrap px-6 py-2 text-sm text-gray-900"
                                                                    >
                                                                        {{
                                                                            medicine.quantity
                                                                        }}
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Suppliers List -->
                    <div class="mt-8 w-full 2xl:mt-0 2xl:w-1/2">
                        <h2 class="mb-4 text-xl font-bold text-gray-800">
                            Suppliers List
                        </h2>
                        <div class="mb-4">
                            <input
                                type="text"
                                class="block w-full rounded-lg border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Search users"
                            />
                        </div>
                        <div class="rounded-xl border bg-white shadow-sm">
                            <div class="overflow-x-auto">
                                <table
                                    class="min-w-full divide-y divide-gray-200"
                                >
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
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr
                                            v-for="(
                                                supplier, index
                                            ) in suppliers"
                                            :key="index"
                                            class="hover:bg-gray-100"
                                        >
                                            <td
                                                class="whitespace-nowrap px-6 py-4"
                                            >
                                                <div
                                                    class="text-sm font-medium text-gray-900"
                                                >
                                                    {{ supplier.institution }}
                                                </div>
                                                <div
                                                    class="text-sm text-gray-500"
                                                >
                                                    {{
                                                        supplier.institutionDetail
                                                    }}
                                                </div>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4"
                                            >
                                                <div
                                                    class="text-sm text-gray-900"
                                                >
                                                    {{ supplier.location }}
                                                </div>
                                                <div
                                                    class="text-sm text-gray-500"
                                                >
                                                    {{
                                                        supplier.locationDetail
                                                    }}
                                                </div>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4"
                                            >
                                                <div
                                                    class="text-sm text-gray-900"
                                                >
                                                    {{ supplier.phone }}
                                                </div>
                                                <div
                                                    class="text-sm text-gray-500"
                                                >
                                                    {{ supplier.phoneAlt }}
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
const mobileMenuOpen = ref(false);
const usersDropdownOpen = ref(false);
const activeDropdown = ref(null);
const activeTab = ref('All');
const tabs = ref([
    { label: 'All Orders', value: 'All' },
    { label: 'Delivered', value: 'Delivered' },
    { label: 'Pending', value: 'Pending' },
    { label: 'Cancelled', value: 'Cancelled' },
]);
const orders = ref([
    {
        id: 1,
        consumer: "St. Paul's Hospital",
        consumerDetail: 'Hana Gebremichael',
        location: 'BOLE',
        locationDetail: 'Woreda 3, CMC Michael',
        phone: '+251987654321',
        phoneAlt: '+251911223344',
        status: 'Delivered',
        expanded: true,
        medicines: [
            { name: 'Azithromycin', unit: 'g', quantity: 2 },
            { name: 'Hydrocortisone', unit: 'ml', quantity: 50 },
            { name: 'Furosemide', unit: 'ampoule', quantity: 15 },
        ],
    },
    {
        id: 2,
        consumer: "St. Paul's Hospital",
        consumerDetail: 'Hana Gebremichael',
        location: 'BOLE',
        locationDetail: 'Woreda 3, CMC Michael',
        phone: '+251987654321',
        phoneAlt: '+251911223344',
        status: 'Pending',
        expanded: false,
        medicines: [
            { name: 'Paracetamol', unit: 'tablet', quantity: 100 },
            { name: 'Amoxicillin', unit: 'capsule', quantity: 30 },
        ],
    },
    {
        id: 3,
        consumer: 'Hana Pharmacy',
        consumerDetail: 'Hana Gebremariam',
        location: 'KIRKOS',
        locationDetail: 'Woreda 3, Near Dembel City Center',
        phone: '+251966677788',
        phoneAlt: '',
        status: 'Delivered',
        expanded: false,
        medicines: [
            { name: 'Metformin', unit: 'tablet', quantity: 60 },
            { name: 'Insulin', unit: 'vial', quantity: 5 },
        ],
    },
    {
        id: 4,
        consumer: 'Amanu Hospital',
        consumerDetail: 'Amanuel Teshome',
        location: 'ARADA',
        locationDetail: 'Woreda 4, Bole Medhanialem',
        phone: '+251923334455',
        phoneAlt: '+251933445566',
        status: 'Cancelled',
        expanded: false,
        medicines: [
            { name: 'Ceftriaxone', unit: 'vial', quantity: 20 },
            { name: 'Diazepam', unit: 'ampoule', quantity: 10 },
        ],
    },
    {
        id: 5,
        consumer: 'Aster Pharmacy',
        consumerDetail: 'Aster Abebe',
        location: 'YEKKA',
        locationDetail: 'Woreda 4, Summit Area',
        phone: '+251911223344',
        phoneAlt: '+251922334455',
        status: 'Pending',
        expanded: false,
        medicines: [
            { name: 'Omeprazole', unit: 'capsule', quantity: 40 },
            { name: 'Ciprofloxacin', unit: 'tablet', quantity: 20 },
        ],
    },
]);
const suppliers = ref([
    {
        institution: 'Meskel Pharmacy',
        institutionDetail: 'Teodros Mengitsu',
        location: 'KIRKOS',
        locationDetail: 'Woreda 2, Sarhot Roundabout',
        phone: '+251944556677',
        phoneAlt: '+251955667788',
    },
    {
        institution: 'Ethiomed',
        institutionDetail: 'Abdi Girma',
        location: 'BOLE',
        locationDetail: 'Woreda 3, Bole Medhanialem',
        phone: '+251911112233',
        phoneAlt: '+251922334455',
    },
    {
        institution: 'MedTech Supplies',
        institutionDetail: 'Samuel Haile',
        location: 'KOLFE KERANIO',
        locationDetail: 'Woreda 11, Kolfe, Idi Mazoria',
        phone: '+251933445566',
        phoneAlt: '+251944556677',
    },
    {
        institution: 'Mekdes Pharmacy',
        institutionDetail: 'Mekdes Alemayehu',
        location: 'LIDETA',
        locationDetail: 'Woreda 5, Mehanna',
        phone: '+251988776655',
        phoneAlt: '+251977665544',
    },
    {
        institution: 'Daniel Pharma',
        institutionDetail: 'Daniel Tadesse',
        location: 'ARADA',
        locationDetail: 'Woreda 6, Near Addis Ababa University',
        phone: '+251988776655',
        phoneAlt: '+251911223344',
    },
]);

const filteredOrders = computed(() => {
    if (activeTab.value === 'All') {
        return orders.value;
    }
    return orders.value.filter((order) => order.status === activeTab.value);
});

const toggleExpand = (id) => {
    const order = orders.value.find((o) => o.id === id);
    if (order) {
        order.expanded = !order.expanded;
    }
};

const toggleStatusOptions = (id) => {
    activeDropdown.value = activeDropdown.value === id ? null : id;
};

const setOrderStatus = (id, status) => {
    const order = orders.value.find((o) => o.id === id);
    if (order) {
        order.status = status;
    }
};

const getOrderCountByStatus = (status) => {
    if (status === 'All') {
        return orders.value.length;
    }
    return orders.value.filter((order) => order.status === status).length;
};

const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'Delivered':
            return 'bg-green-100 text-green-800';
        case 'Pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'Cancelled':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const logout = () => {
    let logout = useForm();
    logout.post('/logout');
};
</script>

<style>
/* Add any custom styles here if needed */
</style>
