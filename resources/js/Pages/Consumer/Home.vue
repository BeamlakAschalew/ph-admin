<script setup>
import { router, useForm, usePage } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import {
    MenuIcon,
    PackageXIcon,
    PillIcon,
    SearchIcon,
    ShoppingCartIcon,
    XIcon,
} from 'lucide-vue-next';
import { ref, watch } from 'vue';

// State
const cartCount = ref(0);
const isMenuOpen = ref(false);
const user = usePage().props.auth.user;

const props = defineProps({
    products: Object,
    filters: Object,
    units: Array,
});

const localProducts = ref(props.products?.data || []);

watch(
    () => props.products,
    (newProducts) => {
        localProducts.value = newProducts?.data || [];
    },
);

const searchQuery = ref(props.filters?.search || '');

const debouncedSearch = debounce((query) => {
    router.get(
        '/',
        { search: query },
        {
            preserveState: true,
            replace: true,
            except: ['units'],
        },
    );
}, 300);

watch(searchQuery, (newQuery) => {
    debouncedSearch(newQuery);
});

// Methods
const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};

const addToCart = (product) => {
    cartCount.value++;
    // Show a toast notification
    alert(`Added ${product.name} (${product.unit}) to cart!`);
};

const goToCheckout = () => {
    if (cartCount.value > 0) {
        alert('Proceeding to checkout...');
    } else {
        alert('Your cart is empty!');
    }
};

const logout = () => {
    let logout = useForm();
    logout.post('/logout');
};
</script>

<template>
    <div class="flex min-h-screen flex-col bg-gray-50">
        <!-- Header -->
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
                                v-model="searchQuery"
                                type="search"
                                class="block w-full rounded-full border border-gray-300 bg-gray-50 p-2.5 ps-10 text-sm text-gray-900 transition-all duration-300 focus:border-blue-600 focus:ring-blue-600"
                                placeholder="Search medications..."
                            />
                        </div>

                        <button
                            class="mb-6 inline-flex items-center gap-x-2 rounded-full border border-transparent bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition-all duration-300 hover:bg-blue-700 disabled:pointer-events-none disabled:opacity-50 sm:mb-0"
                            @click="goToCheckout"
                        >
                            <ShoppingCartIcon class="h-4 w-4" />
                            Checkout ({{ cartCount }})
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
                                    {{ user.first_name[0]
                                    }}{{ user.last_name[0] }}
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
                                            class="focus:outline-hidden</form> flex w-full items-center gap-x-3.5 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100"
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

        <!-- Hero Section -->
        <div
            class="relative overflow-hidden before:absolute before:start-1/2 before:top-0 before:-z-[1] before:h-full before:w-full before:-translate-x-1/2 before:transform before:bg-[url('https://preline.co/assets/svg/component/polygon-bg-element.svg')] before:bg-top before:bg-no-repeat"
        >
            <div
                class="mx-auto max-w-[85rem] px-4 pb-2 pt-1 sm:px-6 lg:px-8 lg:pb-10 lg:pt-16"
            >
                <!-- Title -->
                <div class="mx-auto mt-5 hidden max-w-2xl text-center lg:block">
                    <h1
                        class="block text-4xl font-bold text-gray-800 md:text-5xl lg:text-6xl"
                    >
                        Your Health, Our Priority
                    </h1>
                </div>
                <!-- End Title -->

                <div class="mx-auto mt-5 hidden max-w-3xl text-center lg:block">
                    <p class="text-lg text-gray-600">
                        Find all your pharmaceutical needs in one place.
                    </p>
                </div>

                <!-- Search -->
                <div class="mx-auto mt-8 max-w-2xl">
                    <div
                        class="flex flex-col items-center gap-2 sm:flex-row sm:gap-3"
                    >
                        <div class="w-full">
                            <label for="hero-search" class="sr-only"
                                >Search</label
                            >
                            <input
                                v-model="searchQuery"
                                type="text"
                                id="hero-search"
                                class="block w-full rounded-full border-gray-200 px-4 py-3 text-sm shadow-sm transition-all duration-300 focus:border-blue-600 focus:ring-blue-600 disabled:pointer-events-none disabled:opacity-50"
                                placeholder="Search for medications..."
                            />
                        </div>
                        <button
                            class="hidden w-full items-center justify-center gap-x-2 rounded-full border border-transparent bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition-all duration-300 hover:bg-blue-700 disabled:pointer-events-none disabled:opacity-50 sm:w-auto lg:inline-flex"
                        >
                            <SearchIcon class="h-4 w-4" />
                            Search
                        </button>
                    </div>
                </div>
                <!-- End Search -->
            </div>
        </div>

        <!-- Products Section -->
        <div
            class="mx-auto w-full flex-grow px-4 py-1 sm:w-auto sm:px-6 lg:px-8 lg:py-14"
        >
            <!-- Search Results -->
            <div v-if="searchQuery.trim()">
                <div
                    class="mx-auto mb-10 hidden max-w-2xl text-center lg:block"
                >
                    <h2
                        class="text-2xl font-bold text-gray-800 md:text-3xl md:leading-tight"
                    >
                        Search Results for "{{ searchQuery }}"
                    </h2>
                </div>

                <div
                    v-if="localProducts.length === 0"
                    class="mx-auto max-w-sm py-12 text-center"
                >
                    <PackageXIcon
                        class="mx-auto mb-4 h-16 w-16 text-gray-400"
                    />
                    <h3 class="text-xl font-medium text-gray-800">
                        No products found
                    </h3>
                    <p class="mt-2 text-gray-500">
                        Try a different search term
                    </p>
                </div>

                <div v-else>
                    <div
                        class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-3 lg:gap-6 xl:grid-cols-4"
                    >
                        <div
                            v-for="product in localProducts"
                            :key="product.id"
                            class="group flex h-full w-full flex-col rounded-xl border border-gray-200 bg-white shadow-sm transition duration-300 hover:shadow-md"
                        >
                            <div
                                class="flex items-center justify-between gap-5 px-6 py-2 md:px-8 md:py-3"
                            >
                                <div>
                                    <h3
                                        class="group-hover:text-primary text-xl font-semibold text-gray-800 transition-colors duration-300"
                                    >
                                        {{ product.product_name }}
                                    </h3>
                                    <p class="mt-2 text-gray-500">
                                        {{ product.unit?.unit_name ?? 'None' }}
                                    </p>
                                </div>
                                <button
                                    @click="addToCart(product)"
                                    class="hover:bg-primary-dark inline-flex items-center justify-center rounded-lg border border-transparent bg-blue-700 p-3 text-white transition-all duration-300 disabled:pointer-events-none disabled:opacity-50"
                                >
                                    <ShoppingCartIcon class="h-5 w-5" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <nav
                        class="mt-5 flex items-center justify-center space-x-2"
                        aria-label="Pagination"
                    >
                        <template
                            v-if="products.links && products.links.length"
                        >
                            <template
                                v-for="(link, index) in products.links"
                                :key="index"
                            >
                                <Link
                                    v-if="link.url"
                                    :href="link.url"
                                    preserve-state
                                    preserve-scroll
                                    :class="[
                                        'flex items-center justify-center rounded-md px-4 py-2 text-sm font-medium transition-all',
                                        link.active
                                            ? 'bg-blue-600 text-white shadow-md'
                                            : 'bg-gray-100 text-gray-800 hover:bg-blue-100 hover:text-blue-600',
                                    ]"
                                >
                                    <span v-html="link.label"></span>
                                </Link>
                                <span
                                    v-else
                                    v-html="link.label"
                                    :class="[
                                        'flex items-center justify-center rounded-md bg-gray-200 px-4 py-2 text-sm font-medium text-gray-500',
                                    ]"
                                ></span>
                            </template>
                        </template>
                    </nav>
                    <!-- End Pagination -->
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="mx-auto max-w-sm py-16 text-center">
                <div
                    class="mx-auto mb-4 flex h-24 w-24 items-center justify-center rounded-full bg-blue-50 p-6"
                >
                    <SearchIcon class="text-primary h-12 w-12" />
                </div>
                <h2 class="text-2xl font-medium text-gray-800">
                    Find your health products
                </h2>
                <p class="mt-2 text-gray-500">
                    Search for medications to see results
                </p>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-auto border-t border-gray-200 bg-white">
            <div
                class="mx-auto max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:pt-20"
            >
                <!-- Grid -->
                <div
                    class="flex flex-col items-start justify-between gap-6 sm:flex-row"
                >
                    <div class="flex-shrink-0">
                        <a
                            class="text-primary text-xl font-semibold"
                            href="#"
                            aria-label="PH"
                        >
                            <PillIcon class="mr-2 h-8 w-8" />
                            PH
                        </a>
                        <p class="mt-3 text-xs text-gray-600 sm:text-sm">
                            Your trusted source for quality medications and
                            health products.
                        </p>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900">Email</h4>
                        <div class="my-3">
                            <p class="text-sm text-gray-600">contact@ph.com</p>
                        </div>
                        <h4 class="font-semibold text-gray-900">Phone</h4>
                        <div class="mt-3">
                            <p class="text-sm text-gray-600">+251911234567</p>
                        </div>
                    </div>
                </div>
                <!-- End Grid -->

                <div
                    class="mt-12 grid gap-y-2 sm:flex sm:items-center sm:justify-between sm:gap-y-0"
                >
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600">
                            © 2025 PH. All rights reserved.
                        </p>
                    </div>
                    <!-- End Col -->

                    <!-- Social Brands -->
                    <div>
                        <a
                            class="inline-flex size-10 items-center justify-center gap-x-2 rounded-lg border border-transparent text-sm font-semibold text-gray-500 hover:bg-gray-100 disabled:pointer-events-none disabled:opacity-50"
                            href="#"
                        >
                            <svg
                                class="size-4 flex-shrink-0"
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                fill="currentColor"
                                viewBox="0 0 16 16"
                            >
                                <path
                                    d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"
                                />
                            </svg>
                        </a>
                        <a
                            class="inline-flex size-10 items-center justify-center gap-x-2 rounded-lg border border-transparent text-sm font-semibold text-gray-500 hover:bg-gray-100 disabled:pointer-events-none disabled:opacity-50"
                            href="#"
                        >
                            <svg
                                class="size-4 flex-shrink-0"
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                fill="currentColor"
                                viewBox="0 0 16 16"
                            >
                                <path
                                    d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"
                                />
                            </svg>
                        </a>
                        <a
                            class="inline-flex size-10 items-center justify-center gap-x-2 rounded-lg border border-transparent text-sm font-semibold text-gray-500 hover:bg-gray-100 disabled:pointer-events-none disabled:opacity-50"
                            href="#"
                        >
                            <svg
                                class="size-4 flex-shrink-0"
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                fill="currentColor"
                                viewBox="0 0 16 16"
                            >
                                <path
                                    d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"
                                />
                            </svg>
                        </a>
                        <a
                            class="inline-flex size-10 items-center justify-center gap-x-2 rounded-lg border border-transparent text-sm font-semibold text-gray-500 hover:bg-gray-100 disabled:pointer-events-none disabled:opacity-50"
                            href="#"
                        >
                            <svg
                                class="size-4 flex-shrink-0"
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                fill="currentColor"
                                viewBox="0 0 16 16"
                            >
                                <path
                                    d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z"
                                />
                            </svg>
                        </a>
                    </div>
                    <!-- End Social Brands -->
                </div>
            </div>
        </footer>
    </div>
</template>
