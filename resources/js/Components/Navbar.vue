<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
const mobileMenuOpen = ref(false);
const usersDropdownOpen = ref(false);
const logout = () => {
    let logout = useForm();
    logout.post('/logout');
};
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
                <a class="flex-none text-xl font-bold text-amber-500" href="#"
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
                    <Link class="font-medium text-white" href="/">Home</Link>
                    <Link
                        class="font-medium text-gray-300 hover:text-white"
                        href="/users"
                        >Pending Users</Link
                    >
                    <Link
                        class="font-medium text-gray-300 hover:text-white"
                        href="/admins"
                        >Admins</Link
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
</template>
