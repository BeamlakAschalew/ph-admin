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
import MainLayout from './MainLayout.vue';

defineOptions({
    layout: MainLayout,
});

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

const isCheckoutModalOpen = ref(false);

const cartItems = ref({
    products: [],
    customProducts: [],
});

const toggleCheckoutModal = () => {
    isCheckoutModalOpen.value = !isCheckoutModalOpen.value;
};

const addToCart = (product) => {
    cartCount.value++;
    cartItems.value.products.push(product);
};

const deleteCustomProduct = (id) => {
    cartItems.value.customProducts = cartItems.value.customProducts.filter(
        (item) => item.id !== id,
    );
    cartCount.value = Math.max(0, cartCount.value - 1);
};

const deleteProduct = (id) => {
    cartItems.value.products = cartItems.value.products.filter(
        (item) => item.id !== id,
    );
    cartCount.value = Math.max(0, cartCount.value - 1);
};

const placeOrder = () => {
    console.log(cartItems.value);
    if (
        cartItems.value.customProducts.length > 0 ||
        cartItems.value.products.length > 0
    ) {
        router.post(
            '/checkout',
            {
                products: cartItems.value.products,
                customProducts: cartItems.value.customProducts.map((item) => ({
                    ...item,
                    unit: item.unit?.id || null,
                })),
            },
            {
                onSuccess: () => {
                    cartItems.value.products = [];
                    cartItems.value.customProducts = [];
                    isCheckoutModalOpen.value = false;
                    cartCount.value = 0;
                },
            },
        );
        isCheckoutModalOpen.value = false;
    } else {
        alert('Your cart is empty!');
    }
};

const newProduct = ref({ name: '', unit: '', quantity: '' });

const addCustomProductToCart = () => {
    if (newProduct.value.name && newProduct.value.quantity !== '') {
        cartCount.value++;
        cartItems.value.customProducts.push({
            ...newProduct.value,
            id: Date.now(),
            unit:
                newProduct.value.unit !== ''
                    ? props.units.find(
                          (unit) => unit.id === newProduct.value.unit,
                      )
                    : null,
        });
        newProduct.value = { name: '', unit: '', quantity: '' };
    } else {
        alert('Please fill in all fields.');
    }
};

const goToCheckout = () => {
    if (cartCount.value > 0) {
        toggleCheckoutModal();
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
                                    @click="
                                        addToCart({ ...product, quantity: '' })
                                    "
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
                </div>
                <!-- End Pagination -->
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

        <div class="flex flex-col items-center justify-center px-3 py-5">
            <p class="mt-2 text-gray-500">Custom order it instead</p>
            <!-- Add Product Form -->
            <div class="mt-6">
                <input
                    v-model="newProduct.name"
                    type="text"
                    placeholder="Product Name"
                    class="mb-2 w-full rounded-md border-gray-300 p-2 text-sm"
                />
                <select
                    v-model="newProduct.unit"
                    class="mb-2 w-full rounded-md border-gray-300 p-2 text-sm"
                >
                    <option value="" disabled>Select Unit</option>
                    <option
                        v-for="unit in units"
                        :key="unit.id"
                        :value="unit.id"
                    >
                        {{ unit.unit_name }}
                    </option>
                </select>
                <input
                    v-model="newProduct.quantity"
                    type="text"
                    placeholder="Quantity"
                    class="mb-2 w-full rounded-md border-gray-300 p-2 text-sm"
                />
                <button
                    class="w-full rounded-md bg-blue-600 px-4 py-2 text-sm text-white"
                    @click="addCustomProductToCart"
                >
                    Add to Cart
                </button>
            </div>
        </div>

        <!-- Checkout Modal -->
        <div
            v-if="isCheckoutModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        >
            <div class="w-full max-w-md rounded-lg bg-white p-6">
                <h2 class="text-lg font-bold text-gray-800">Checkout</h2>
                <ul class="mt-4 space-y-2">
                    <li
                        v-for="(item, index) in cartItems.products"
                        :key="index"
                        class="flex justify-between border-b pb-2"
                    >
                        <div>
                            <span
                                >{{ item.product_name }} ({{
                                    item.unit?.unit_name || 'No unit'
                                }})</span
                            >
                        </div>
                        <div class="flex items-center gap-2">
                            <input
                                type="text"
                                class="w-16 rounded-md border-gray-300 px-2 py-1 text-sm text-gray-800"
                                v-model="item.quantity"
                            />
                            <button
                                class="rounded-md bg-red-500 px-2 py-1 text-sm text-white"
                                @click="deleteProduct(item.id)"
                            >
                                Delete
                            </button>
                        </div>
                    </li>
                    <li
                        v-for="item in cartItems.customProducts"
                        :key="item.id"
                        class="flex justify-between border-b pb-2"
                    >
                        <div>
                            <span
                                >{{ item.name }} ({{
                                    item.unit?.unit_name || 'No unit'
                                }})</span
                            >
                        </div>
                        <div class="flex items-center gap-2">
                            <input
                                type="text"
                                class="w-16 rounded-md border-gray-300 px-2 py-1 text-sm text-gray-800"
                                v-model="item.quantity"
                            />
                            <button
                                class="rounded-md bg-red-500 px-2 py-1 text-sm text-white"
                                @click="deleteCustomProduct(item.id)"
                            >
                                Delete
                            </button>
                        </div>
                    </li>
                </ul>
                <div class="mt-6 flex justify-end gap-2">
                    <button
                        class="rounded-md bg-gray-200 px-4 py-2 text-sm text-gray-800"
                        @click="toggleCheckoutModal"
                    >
                        Close
                    </button>
                    <button
                        class="rounded-md bg-blue-600 px-4 py-2 text-sm text-white"
                        @click="placeOrder"
                    >
                        Place Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
