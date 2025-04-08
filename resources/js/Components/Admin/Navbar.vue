<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watchEffect } from 'vue';

const mobileMenuOpen = ref(false);

const logout = () => {
    let logout = useForm();
    logout.post('/logout');
};

const user = usePage().props.auth.user;

const currentPage = computed(() => usePage().component);

watchEffect(currentPage);

const dynamicNavClass = (page) =>
    currentPage.value === `Admin/${page}`
        ? 'font-bold text-white'
        : 'font-medium text-gray-300 hover:text-white';
</script>

<template>
    <!-- Preline UI Navbar -->
    <header
        class="z-50 flex w-full flex-wrap bg-blue-900 py-4 text-sm sm:flex-nowrap sm:justify-start"
    >
        <nav
            class="mx-auto w-full max-w-full px-4 sm:flex sm:items-center sm:justify-between"
            aria-label="Global"
        >
            <div class="flex items-center justify-between">
                <Link class="flex-none text-xl font-bold text-white" href="/"
                    >PH Admin</Link
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
                    <Link :class="dynamicNavClass('Dashboard')" href="/"
                        >Home</Link
                    >
                    <Link
                        :class="dynamicNavClass('Suppliers')"
                        href="/suppliers"
                        >Suppliers</Link
                    >
                    <Link :class="dynamicNavClass('Products')" href="/products"
                        >Products</Link
                    >

                    <Link
                        v-if="$page.props.auth.user_role === 'superadmin'"
                        :class="dynamicNavClass('Admins')"
                        href="/admins"
                        >Admins</Link
                    >

                    <div class="hs-dropdown relative inline-flex">
                        <button
                            id="hs-dropdown-default"
                            type="button"
                            class="hs-dropdown-toggle focus:outline-hidden flex w-full items-center font-medium text-gray-300 hover:text-white"
                            aria-haspopup="menu"
                            aria-expanded="false"
                            aria-label="Dropdown"
                        >
                            Pending
                            <svg
                                class="size-4 hs-dropdown-open:rotate-180"
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
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>

                        <div
                            class="hs-dropdown-menu duration mt-2 hidden min-w-60 rounded-lg bg-white opacity-0 shadow-md transition-[opacity,margin] before:absolute before:-top-4 before:start-0 before:h-4 before:w-full after:absolute after:-bottom-4 after:start-0 after:h-4 after:w-full hs-dropdown-open:opacity-100"
                            role="menu"
                            aria-orientation="vertical"
                            aria-labelledby="hs-dropdown-default"
                        >
                            <div class="space-y-0.5 p-1">
                                <Link
                                    class="focus:outline-hidden flex items-center gap-x-3.5 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100"
                                    href="/pending-consumers"
                                >
                                    Pending Consumers
                                </Link>
                                <Link
                                    class="focus:outline-hidden flex items-center gap-x-3.5 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100"
                                    href="/pending-suppliers"
                                >
                                    Pending Suppliers
                                </Link>
                            </div>
                        </div>
                    </div>
                    <!-- Dropdown -->

                    <div
                        v-if="$page.props.auth.user_role === 'superadmin'"
                        class="hs-dropdown relative inline-flex"
                    >
                        <button
                            id="hs-dropdown-default"
                            type="button"
                            class="hs-dropdown-toggle focus:outline-hidden flex w-full items-center font-medium text-gray-300 hover:text-white"
                            aria-haspopup="menu"
                            aria-expanded="false"
                            aria-label="Dropdown"
                        >
                            Users
                            <svg
                                class="size-4 hs-dropdown-open:rotate-180"
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
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>

                        <div
                            class="hs-dropdown-menu duration mt-2 hidden min-w-60 rounded-lg bg-white opacity-0 shadow-md transition-[opacity,margin] before:absolute before:-top-4 before:start-0 before:h-4 before:w-full after:absolute after:-bottom-4 after:start-0 after:h-4 after:w-full hs-dropdown-open:opacity-100"
                            role="menu"
                            aria-orientation="vertical"
                            aria-labelledby="hs-dropdown-default"
                        >
                            <div class="space-y-0.5 p-1">
                                <Link
                                    class="focus:outline-hidden flex items-center gap-x-3.5 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100"
                                    href="/consumers"
                                >
                                    Consumers
                                </Link>
                                <Link
                                    class="focus:outline-hidden flex items-center gap-x-3.5 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100"
                                    href="/suppliers"
                                >
                                    Suppliers
                                </Link>
                            </div>
                        </div>
                    </div>
                    <!-- Dropdown -->
                    <div
                        class="hs-dropdown relative inline-flex [--placement:bottom-right]"
                    >
                        <button
                            id="hs-dropdown-account"
                            type="button"
                            class="size-9.5 focus:outline-hidden inline-flex items-center justify-center gap-x-2 rounded-full border border-transparent text-sm font-semibold text-gray-800 disabled:pointer-events-none disabled:opacity-50 dark:text-white"
                            aria-haspopup="menu"
                            aria-expanded="false"
                            aria-label="Dropdown"
                        >
                            <span
                                class="inline-flex size-8 items-center justify-center rounded-full bg-red-500 font-semibold text-white"
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
                                    {{ user.first_name }} +251{{ user.phone }}
                                </p>
                            </div>
                            <div class="space-y-0.5 p-1.5">
                                <Link
                                    class="focus:outline-hidden flex items-center gap-x-3.5 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100"
                                    href="/profile"
                                    >Edit profile</Link
                                >
                                <form @submit.prevent="logout">
                                    <button
                                        class="focus:outline-hidden flex w-full items-center gap-x-3.5 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100"
                                        href="#"
                                    >
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Dropdown -->
                </div>
            </div>
        </nav>
    </header>
</template>
