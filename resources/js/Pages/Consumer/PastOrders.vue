<template>
    <div class="mx-auto max-w-4xl px-4 py-10">
        <h1
            class="mb-8 flex items-center gap-2 text-3xl font-bold text-blue-700"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-7 w-7 text-blue-500"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 7v4a1 1 0 001 1h3m10 0h3a1 1 0 001-1V7a1 1 0 00-1-1h-3m-10 0H4a1 1 0 00-1 1zm0 0V5a2 2 0 012-2h10a2 2 0 012 2v2m-2 4v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"
                />
            </svg>
            Past Orders
        </h1>
        <div
            v-if="orders.data.length === 0"
            class="rounded-lg bg-white py-16 text-center text-gray-500 shadow"
        >
            No past orders found.
        </div>
        <div v-else class="space-y-8">
            <div
                v-for="order in orders.data"
                :key="order.id"
                class="rounded-xl border bg-white p-6 shadow transition hover:shadow-lg"
            >
                <div
                    class="mb-3 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between"
                >
                    <div>
                        <span class="text-lg font-semibold text-blue-700"
                            >Order #{{ order.id }}</span
                        >
                        <span class="ml-2 text-xs text-gray-500">{{
                            formatDate(order.created_at)
                        }}</span>
                    </div>
                    <span
                        class="rounded-full border px-3 py-1 text-xs font-medium"
                        :class="statusClass(order.status)"
                        >{{ order.status }}</span
                    >
                </div>
                <div class="mt-4 overflow-x-auto">
                    <table
                        class="min-w-full divide-y divide-gray-200 rounded-lg border"
                    >
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-2 text-left text-xs font-semibold uppercase text-gray-600"
                                >
                                    Type
                                </th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-semibold uppercase text-gray-600"
                                >
                                    Name
                                </th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-semibold uppercase text-gray-600"
                                >
                                    Quantity
                                </th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-semibold uppercase text-gray-600"
                                >
                                    Unit
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr
                                v-for="item in order.items"
                                :key="'item-' + item.id"
                            >
                                <td class="px-4 py-2 font-medium text-blue-600">
                                    Regular
                                </td>
                                <td class="px-4 py-2">
                                    {{ item.product.product_name }}
                                </td>
                                <td class="px-4 py-2">{{ item.quantity }}</td>
                                <td class="px-4 py-2">
                                    {{
                                        item.product.unit?.unit_name ||
                                        'No unit'
                                    }}
                                </td>
                            </tr>
                            <tr
                                v-for="citem in order.custom_items"
                                :key="'custom-' + citem.id"
                            >
                                <td
                                    class="px-4 py-2 font-medium text-green-600"
                                >
                                    Custom
                                </td>
                                <td class="px-4 py-2">
                                    {{ citem.product_name }}
                                </td>
                                <td class="px-4 py-2">{{ citem.quantity }}</td>
                                <td class="px-4 py-2">
                                    {{ citem.unit?.unit_name || 'No unit' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div
            v-if="orders.last_page > 1"
            class="mt-10 flex justify-center gap-2"
        >
            <button
                v-for="page in orders.last_page"
                :key="page"
                @click="goToPage(page)"
                :class="[
                    'rounded-lg border px-4 py-2',
                    page === orders.current_page
                        ? 'border-blue-600 bg-blue-600 text-white'
                        : 'border-gray-300 bg-gray-100 text-gray-700 hover:bg-blue-50',
                ]"
            >
                {{ page }}
            </button>
        </div>
    </div>
</template>

<script setup>
defineProps({
    orders: Object,
});

function formatDate(dateStr) {
    const d = new Date(dateStr);
    return d.toLocaleString();
}

function statusClass(status) {
    switch (status) {
        case 'Pending':
            return 'bg-yellow-50 text-yellow-800 border-yellow-300';
        case 'Completed':
            return 'bg-green-50 text-green-800 border-green-300';
        case 'Cancelled':
            return 'bg-red-50 text-red-800 border-red-300';
        default:
            return 'bg-gray-50 text-gray-800 border-gray-300';
    }
}

import { router } from '@inertiajs/vue3';
function goToPage(page) {
    router.get('/consumer/past-orders', { page });
}
</script>
