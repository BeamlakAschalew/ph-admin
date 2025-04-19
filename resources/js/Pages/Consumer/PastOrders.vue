<script setup>
import { router, useForm, usePage } from '@inertiajs/vue3';
import { MenuIcon, PillIcon, XIcon } from 'lucide-vue-next';
import { ref } from 'vue';
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

function goToPage(page) {
    router.get('/past-orders', { page });
}

const isMenuOpen = ref(false);
const user = usePage().props.auth.user;

const logout = () => {
    let logout = useForm();
    logout.post('/logout');
};

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};
</script>

<template>
    <header
        class="sticky top-0 z-50 flex w-full flex-wrap border-b border-gray-200 bg-white text-sm sm:flex-nowrap sm:justify-start"
    >
        <nav
            class="relative mx-auto w-full max-w-[85rem] px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8"
            aria-label="Global"
        >
            <div class="mr-5 flex items-center justify-between">
                <Link
                    class="flex-none py-4 text-xl font-semibold text-blue-600"
                    href="/"
                    aria-label="PH"
                >
                    <PillIcon class="mr-2 inline-block h-8 w-8" />
                    PH
                </Link>
                <div class="sm:hidden">
                    <button
                        type="button"
                        class="inline-flex items-center justify-center gap-2 rounded-md border border-gray-200 p-2 text-sm text-gray-600 transition-all hover:bg-gray-100 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
                        @click="toggleMenu"
                        aria-controls="navbar-collapse"
                        :aria-expanded="isMenuOpen"
                    >
                        <span class="sr-only">Toggle navigation</span>
                        <MenuIcon v-if="!isMenuOpen" class="h-5 w-5" />
                        <XIcon v-else class="h-5 w-5" />
                    </button>
                </div>
            </div>
            <div
                id="navbar-collapse"
                class="grow basis-full overflow-hidden transition-all duration-300 sm:block"
                :class="isMenuOpen ? 'block h-auto' : 'hidden'"
            >
                <div
                    class="mt-5 flex flex-col gap-5 sm:mt-0 sm:flex-row sm:items-center sm:justify-end sm:pl-5"
                >
                    <!-- Removed search input and checkout button -->

                    <Link
                        href="/"
                        class="mb-6 inline-flex items-center gap-x-2 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-blue-600 transition-all duration-300 hover:bg-blue-50 hover:text-blue-800 sm:mb-0"
                    >
                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M15 18l-6-6 6-6" />
                        </svg>
                        Go back home
                    </Link>

                    <div
                        class="hs-dropdown relative inline-flex [--placement:bottom-right]"
                    >
                        <button
                            id="hs-dropdown-account"
                            type="button"
                            class="focus:outline-hidden inline-flex h-10 w-10 items-center justify-center gap-x-2 rounded-full border border-transparent text-sm font-semibold text-gray-800 disabled:pointer-events-none disabled:opacity-50 dark:text-white"
                            aria-haspopup="menu"
                            aria-expanded="false"
                            aria-label="Dropdown"
                        >
                            <span
                                class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-red-500 font-semibold text-white"
                            >
                                {{ user.first_name[0] }}{{ user.last_name[0] }}
                            </span>
                        </button>

                        <div
                            class="hs-dropdown-menu duration mt-2 hidden min-w-60 rounded-lg bg-white opacity-0 shadow-md transition-[opacity,margin] before:absolute before:-top-4 before:start-0 before:h-4 before:w-full after:absolute after:-bottom-4 after:start-0 after:h-4 after:w-full hs-dropdown-open:opacity-100"
                            role="menu"
                            aria-orientation="vertical"
                            aria-labelledby="hs-dropdown-account"
                        >
                            <div class="rounded-t-lg bg-gray-100 px-5 py-3">
                                <p class="text-sm text-gray-500">
                                    Signed in as
                                </p>
                                <p class="text-sm font-medium text-gray-800">
                                    {{ user.first_name }} +251{{
                                        user.primary_phone
                                    }}
                                </p>
                            </div>
                            <div class="space-y-0.5 p-1.5">
                                <a
                                    class="focus:outline-hidden flex items-center gap-x-3.5 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100"
                                >
                                    Edit profile
                                </a>
                                <form @submit.prevent="logout">
                                    <button
                                        href="#"
                                        class="focus:outline-hidden flex w-full items-center gap-x-3.5 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100"
                                    >
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Profile Dropdown -->
                </div>
            </div>
        </nav>
    </header>
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
