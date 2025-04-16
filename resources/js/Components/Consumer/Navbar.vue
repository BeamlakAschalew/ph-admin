<template>
    <header
        class="sticky top-0 z-50 flex w-full flex-wrap border-b border-gray-200 bg-white text-sm sm:flex-nowrap sm:justify-start"
    >
        <nav
            class="relative mx-auto w-full max-w-[85rem] px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8"
            aria-label="Global"
        >
            <div class="mr-5 flex items-center justify-between">
                <a
                    class="flex-none py-4 text-xl font-semibold text-blue-600"
                    href="#"
                    aria-label="PH"
                >
                    <PillIcon class="mr-2 inline-block h-8 w-8" />
                    PH
                </a>
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
                    <div class="relative max-w-xl flex-1">
                        <div
                            class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3"
                        >
                            <SearchIcon class="h-4 w-4 text-gray-400" />
                        </div>
                        <input
                            v-model="consumerStore.searchQuery"
                            type="search"
                            class="block w-full rounded-full border border-gray-300 bg-gray-50 p-2.5 ps-10 text-sm text-gray-900 transition-all duration-300 focus:border-blue-600 focus:ring-blue-600"
                            placeholder="Search medications..."
                        />
                    </div>

                    <button
                        class="mb-6 inline-flex items-center gap-x-2 rounded-full border border-transparent bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition-all duration-300 hover:bg-blue-700 disabled:pointer-events-none disabled:opacity-50 sm:mb-0"
                        @click="emitGoToCheckout"
                    >
                        <ShoppingCartIcon class="h-4 w-4" />
                        Checkout ({{ consumerStore.cartCount }})
                    </button>

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
                                <p
                                    class="text-sm font-medium text-gray-800"
                                >
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
</template>

<script setup>
import { ref } from 'vue';
import { useConsumerStore } from '@/stores/consumerStore';
import { usePage, useForm } from '@inertiajs/vue3';
import {
    MenuIcon,
    XIcon,
    PillIcon,
    SearchIcon,
    ShoppingCartIcon,
} from 'lucide-vue-next';

const consumerStore = useConsumerStore();
const isMenuOpen = ref(false);
const user = usePage().props.auth.user;

const emit = defineEmits(['goToCheckout']);
const emitGoToCheckout = () => emit('goToCheckout');

const logout = () => {
    let logout = useForm();
    logout.post('/logout');
};

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};
</script>
