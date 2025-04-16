<script setup>
import Navbar from '@/Components/Consumer/Navbar.vue';
import { useConsumerStore } from '@/stores/consumerStore';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { PackageXIcon, SearchIcon, ShoppingCartIcon } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import MainLayout from './MainLayout.vue';

defineOptions({
    layout: MainLayout,
});

// State
const consumerStore = useConsumerStore();

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

consumerStore.searchQuery = props.filters?.search || '';

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

watch(
    () => consumerStore.searchQuery,
    (newQuery) => {
        debouncedSearch(newQuery);
    },
);

// Methods
const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};

const isCheckoutModalOpen = ref(false);

const toggleCheckoutModal = () => {
    isCheckoutModalOpen.value = !isCheckoutModalOpen.value;
};

const addToCart = (product) => {
    consumerStore.addToCart(product);
};

const deleteCustomProduct = (id) => {
    consumerStore.deleteCustomProduct(id);
};

const deleteProduct = (id) => {
    consumerStore.deleteProduct(id);
};

const placeOrder = () => {
    if (
        consumerStore.cartItems.customProducts.length > 0 ||
        consumerStore.cartItems.products.length > 0
    ) {
        router.post(
            '/checkout',
            {
                products: consumerStore.cartItems.products,
                customProducts: consumerStore.cartItems.customProducts.map(
                    (item) => ({
                        ...item,
                        unit: item.unit?.id || null,
                    }),
                ),
            },
            {
                onSuccess: () => {
                    consumerStore.clearCart();
                    isCheckoutModalOpen.value = false;
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
        consumerStore.addCustomProductToCart({
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
    if (consumerStore.cartCount > 0) {
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
        <Navbar @goToCheckout="goToCheckout" />
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
                                v-model="consumerStore.searchQuery"
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
            <div v-if="consumerStore.searchQuery.trim()">
                <div
                    class="mx-auto mb-10 hidden max-w-2xl text-center lg:block"
                >
                    <h2
                        class="text-2xl font-bold text-gray-800 md:text-3xl md:leading-tight"
                    >
                        Search Results for "{{ consumerStore.searchQuery }}"
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
                        v-for="(item, index) in consumerStore.cartItems
                            .products"
                        :key="index"
                        class="flex justify-between border-b pb-2"
                    >
                        <div>
                            <span
                                >{{ item.product_name }} ({ {
                                item.unit?.unit_name || 'No unit' } })</span
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
                        v-for="item in consumerStore.cartItems.customProducts"
                        :key="item.id"
                        class="flex justify-between border-b pb-2"
                    >
                        <div>
                            <span
                                >{{ item.name }} ({ { item.unit?.unit_name ||
                                'No unit' } })</span
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
