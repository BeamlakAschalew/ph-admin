<script setup>
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import MainLayout from './MainLayout.vue';

defineOptions({
    layout: MainLayout,
});

const props = defineProps({
    orders: Object,
});

const localOrders = ref(props.orders.data);

const activeDropdown = ref(null);
const activeTab = ref('All');
const tabs = ref([
    { label: 'All Orders', value: 'All' },
    { label: 'Completed', value: 'Completed' },
    { label: 'Pending', value: 'Pending' },
    { label: 'Cancelled', value: 'Cancelled' },
]);

const filteredOrders = computed(() => {
    if (activeTab.value === 'All') {
        return localOrders.value;
    }
    return localOrders.value.filter(
        (order) => order.status === activeTab.value,
    );
});

const toggleExpand = (id) => {
    const order = localOrders.value.find((o) => o.id === id);
    if (order) {
        order.expanded = !order.expanded;
    }
};

const toggleStatusOptions = (id) => {
    activeDropdown.value = activeDropdown.value === id ? null : id;
};

const setOrderStatus = (id, status) => {
    const order = localOrders.value.find((o) => o.id === id);
    if (order) {
        router.put(
            `/admin/orders/${order.id}`,
            {
                status: status,
            },
            {
                preserveScroll: true,
                onSuccess: () => {
                    order.status = status;
                    order.expanded = false;
                    activeDropdown.value = null;
                },
            },
        );
    }
};

const getOrderCountByStatus = (status) => {
    if (status === 'All') {
        return localOrders.value.length;
    }
    return localOrders.value.filter((order) => order.status === status).length;
};

const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'Completed':
            return 'bg-green-100 text-green-800';
        case 'Pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'Cancelled':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};
</script>

<style>
/* Add any custom styles here if needed */
</style>

<template>
    <Head title="Dashboard" />
    <div class="flex min-h-screen flex-col">
        <!-- Main Content -->
        <main class="bg-white-50 flex-1">
            <div class="mx-auto w-full px-2 py-6 sm:px-4">
                <div
                    class="flex flex-col items-center justify-center gap-6 2xl:flex-row"
                >
                    <!-- Orders List -->
                    <div class="w-full max-w-7xl px-4 py-6">
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
                                                Ordered At
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
                                            v-if="localOrders.length === 0"
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
                                                        {{
                                                            order.consumer
                                                                .institution_name
                                                        }}
                                                    </div>
                                                    <div
                                                        class="text-sm text-gray-500"
                                                    >
                                                        {{
                                                            order.consumer
                                                                .first_name
                                                        }}
                                                        {{
                                                            order.consumer
                                                                .last_name
                                                        }}
                                                    </div>
                                                </td>
                                                <td
                                                    class="whitespace-normal break-words px-6 py-4"
                                                >
                                                    <div
                                                        class="text-sm text-gray-900"
                                                    >
                                                        {{
                                                            order.consumer
                                                                .subcity
                                                                .subcity_name
                                                        }}
                                                    </div>
                                                    <div
                                                        class="text-sm text-gray-500"
                                                    >
                                                        Woreda
                                                        {{
                                                            order.consumer
                                                                .woreda
                                                        }},
                                                        {{
                                                            order.consumer
                                                                .special_place
                                                        }}
                                                    </div>
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-6 py-4"
                                                >
                                                    <div
                                                        class="text-sm text-gray-900"
                                                    >
                                                        {{
                                                            new Date(
                                                                order.created_at,
                                                            ).toLocaleString()
                                                        }}
                                                    </div>
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-6 py-4"
                                                >
                                                    <div
                                                        class="text-sm text-gray-900"
                                                    >
                                                        +251{{
                                                            order.consumer
                                                                .primary_phone
                                                        }}
                                                    </div>
                                                    <div
                                                        class="text-sm text-gray-500"
                                                    >
                                                        +251{{
                                                            order.consumer
                                                                .secondary_phone
                                                        }}
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
                                                                    'Completed',
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
                                                                    'Completed'
                                                                "
                                                                @click="
                                                                    setOrderStatus(
                                                                        order.id,
                                                                        'Completed',
                                                                    )
                                                                "
                                                                class="rounded bg-green-500 px-2 py-1 text-xs font-medium text-white hover:bg-green-600"
                                                            >
                                                                Set Completed
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
                                                    colspan="6"
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
                                                                        Product
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
                                                                <template
                                                                    v-if="
                                                                        order
                                                                            .items
                                                                            .length ===
                                                                        0
                                                                    "
                                                                >
                                                                    <tr>
                                                                        <td
                                                                            colspan="3"
                                                                            class="px-6 py-2 text-center text-sm text-gray-500"
                                                                        >
                                                                            No
                                                                            products
                                                                            found.
                                                                        </td>
                                                                    </tr>
                                                                </template>
                                                                <template
                                                                    v-else
                                                                >
                                                                    <tr
                                                                        v-for="product in order.items"
                                                                        :key="
                                                                            product.product_id
                                                                        "
                                                                        class="hover:bg-gray-100"
                                                                    >
                                                                        <td
                                                                            class="whitespace-nowrap px-6 py-2 text-sm text-gray-900"
                                                                        >
                                                                            {{
                                                                                product
                                                                                    .product
                                                                                    .product_name
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="whitespace-nowrap px-6 py-2 text-sm text-gray-900"
                                                                        >
                                                                            {{
                                                                                product
                                                                                    .product
                                                                                    .unit
                                                                                    ? product
                                                                                          .product
                                                                                          .unit
                                                                                          .unit_name
                                                                                    : 'None'
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="whitespace-nowrap px-6 py-2 text-sm text-gray-900"
                                                                        >
                                                                            {{
                                                                                product.quantity
                                                                            }}
                                                                        </td>
                                                                    </tr>
                                                                </template>
                                                            </tbody>

                                                            <!-- Divider -->
                                                            <tr>
                                                                <td
                                                                    colspan="3"
                                                                    class="bg-gray-200 px-6 py-2 text-sm font-semibold text-gray-700"
                                                                >
                                                                    Custom
                                                                    Products
                                                                </td>
                                                            </tr>

                                                            <tbody
                                                                class="divide-y divide-gray-200"
                                                            >
                                                                <template
                                                                    v-if="
                                                                        order
                                                                            .custom_items
                                                                            .length ===
                                                                        0
                                                                    "
                                                                >
                                                                    <tr>
                                                                        <td
                                                                            colspan="3"
                                                                            class="px-6 py-2 text-center text-sm text-gray-500"
                                                                        >
                                                                            No
                                                                            custom
                                                                            products
                                                                            found.
                                                                        </td>
                                                                    </tr>
                                                                </template>
                                                                <template
                                                                    v-else
                                                                >
                                                                    <tr
                                                                        v-for="product in order.custom_items"
                                                                        :key="
                                                                            product.id
                                                                        "
                                                                        class="hover:bg-gray-100"
                                                                    >
                                                                        <td
                                                                            class="whitespace-nowrap px-6 py-2 text-sm text-gray-900"
                                                                        >
                                                                            {{
                                                                                product.product_name
                                                                            }}
                                                                        </td>
                                                                        <td
                                                                            class="whitespace-nowrap px-6 py-2 text-sm text-gray-900"
                                                                        >
                                                                            None
                                                                        </td>
                                                                        <td
                                                                            class="whitespace-nowrap px-6 py-2 text-sm text-gray-900"
                                                                        >
                                                                            {{
                                                                                product.quantity
                                                                            }}
                                                                        </td>
                                                                    </tr>
                                                                </template>
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
                        <!-- Pagination -->
                        <nav
                            class="mt-5 flex items-center -space-x-px"
                            aria-label="Pagination"
                        >
                            <template
                                v-if="orders.links && orders.links.length"
                            >
                                <template
                                    v-for="(link, index) in orders.links"
                                    :key="index"
                                >
                                    <Link
                                        v-if="link.url"
                                        prserve-scroll
                                        :href="link.url"
                                        :class="[
                                            index === 0 ? 'rounded-l-md' : '',
                                            index === orders.links.length - 1
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
                                            index === orders.links.length - 1
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
                </div>
            </div>
        </main>
    </div>
</template>
