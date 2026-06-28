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

const props = defineProps({
    products: Object,
    filters: Object,
    units: Array,
    subcities: Array,
});

const localProducts = ref(props.products?.data || []);

watch(
    () => props.products,
    (newProducts) => {
        localProducts.value = newProducts?.data || [];
    },
);

const crtId = ref(1);

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

const isCheckoutModalOpen = ref(false);

const toggleCheckoutModal = () => {
    isCheckoutModalOpen.value = !isCheckoutModalOpen.value;
};

// Add feedback state
const addToCartFeedback = ref(false);
const addCustomFeedback = ref(false);

const addToCart = (product) => {
    consumerStore.addToCart({ ...product, pid: crtId.value++ });
    addToCartFeedback.value = true;

    setTimeout(() => {
        addToCartFeedback.value = false;
    }, 2500);
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
            pid: crtId.value++,
            id: Date.now(),
            unit:
                newProduct.value.unit !== ''
                    ? props.units.find(
                          (unit) => unit.id === newProduct.value.unit,
                      )
                    : null,
        });
        newProduct.value = { name: '', unit: '', quantity: '' };
        addCustomFeedback.value = true;
        setTimeout(() => {
            addCustomFeedback.value = false;
        }, 2500);
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

const user = usePage().props.auth.user;

const contactForm = useForm({
    name: `${user.first_name} ${user.last_name}`,
    phone: `+251${user.primary_phone}`,
    message: '',
    user: 'Consumer',
});

const submitContactForm = () => {
    contactForm.post('/contact', {
        onSuccess: () => {
            contactForm.reset();
        },
        onError: (errors) => {
            console.error('Form submission errors:', errors);
        },
    });
};
</script>

<template>
    <Head title="Home" />
    <div class="flex min-h-screen flex-col bg-gray-50">
        <Navbar :subcities="subcities" @goToCheckout="goToCheckout" />
        <!-- Feedback Toasts -->
        <transition name="toast-slide">
            <div
                v-if="addToCartFeedback"
                class="fixed left-1/2 top-6 z-[9999] flex -translate-x-1/2 items-center gap-2 rounded-2xl bg-gradient-to-r from-green-500 to-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-2xl shadow-green-500/30"
                style="pointer-events: none"
            >
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7"
                    />
                </svg>
                Added to cart!
            </div>
        </transition>
        <transition name="toast-slide">
            <div
                v-if="addCustomFeedback"
                class="fixed left-1/2 top-6 z-[9999] flex -translate-x-1/2 items-center gap-2 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-600 px-5 py-3 text-sm font-semibold text-white shadow-2xl shadow-amber-500/30"
                style="pointer-events: none"
            >
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7"
                    />
                </svg>
                Custom product added!
            </div>
        </transition>
        <!-- Hero Section -->
        <div
            class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800"
        >
            <!-- Decorative shapes -->
            <div class="pointer-events-none absolute inset-0 overflow-hidden">
                <div
                    class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-white/5 blur-3xl"
                ></div>
                <div
                    class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-blue-300/10 blur-3xl"
                ></div>
                <div
                    class="absolute left-1/4 top-1/2 h-48 w-48 rounded-full bg-indigo-300/10 blur-2xl"
                ></div>
            </div>
            <div
                class="relative mx-auto max-w-[85rem] px-4 pb-10 pt-8 sm:px-6 lg:px-8 lg:pb-20 lg:pt-20"
            >
                <!-- Title -->
                <div class="mx-auto max-w-2xl text-center">
                    <div
                        class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-sm font-medium text-blue-100 backdrop-blur-sm"
                    >
                        <span class="relative flex h-2 w-2">
                            <span
                                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75"
                            ></span>
                            <span
                                class="relative inline-flex h-2 w-2 rounded-full bg-green-400"
                            ></span>
                        </span>
                        Pharmacy Delivery Network
                    </div>
                    <h1
                        class="block text-3xl font-extrabold text-white sm:text-4xl md:text-5xl lg:text-6xl"
                    >
                        Your Health,
                        <span class="text-blue-200">Our Priority</span>
                    </h1>
                </div>

                <div class="mx-auto mt-4 max-w-3xl text-center">
                    <p class="text-lg text-blue-100/90">
                        Find all your pharmaceutical needs in one place. Fast,
                        reliable, and trusted.
                    </p>
                </div>

                <!-- Search -->
                <div class="mx-auto mt-8 max-w-2xl">
                    <div class="flex flex-col items-center gap-3 sm:flex-row">
                        <div class="relative w-full">
                            <div
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4"
                            >
                                <SearchIcon class="h-5 w-5 text-blue-300" />
                            </div>
                            <input
                                v-model="consumerStore.searchQuery"
                                type="text"
                                id="hero-search"
                                class="block w-full rounded-2xl border-0 bg-white/10 py-3.5 pl-12 pr-4 text-sm text-white placeholder-blue-200/60 shadow-lg backdrop-blur-sm transition-all duration-300 focus:bg-white/15 focus:ring-2 focus:ring-white/30 disabled:pointer-events-none disabled:opacity-50"
                                placeholder="Search for medications..."
                            />
                        </div>
                        <button
                            class="inline-flex w-full items-center justify-center gap-x-2 rounded-2xl bg-white px-6 py-3.5 text-sm font-semibold text-blue-700 shadow-lg transition-all duration-300 hover:bg-blue-50 hover:shadow-xl disabled:pointer-events-none disabled:opacity-50 sm:w-auto"
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
            class="relative flex-grow bg-gradient-to-b from-blue-50 via-white to-gray-50"
        >
            <div class="relative px-4 py-1 sm:px-6 lg:px-8 lg:py-14">
                <!-- Search Results -->
                <div v-if="consumerStore.searchQuery.trim()">
                    <div class="mx-auto mb-10 max-w-2xl text-center">
                        <span
                            class="mb-2 inline-block rounded-full bg-blue-100 px-4 py-1 text-sm font-medium text-blue-700"
                            >Search Results</span
                        >
                        <h2
                            class="mt-3 text-2xl font-bold text-gray-800 md:text-3xl md:leading-tight"
                        >
                            Results for "<span class="text-blue-600">{{
                                consumerStore.searchQuery
                            }}</span
                            >"
                        </h2>
                    </div>

                    <div
                        v-if="localProducts.length === 0"
                        class="mx-auto max-w-sm py-16 text-center"
                    >
                        <div
                            class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-blue-50"
                        >
                            <PackageXIcon class="h-12 w-12 text-blue-300" />
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">
                            No products found
                        </h3>
                        <p class="mt-2 text-gray-500">
                            Try a different search term or browse categories
                        </p>
                    </div>

                    <div v-else>
                        <div
                            class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-6 lg:grid-cols-3 lg:gap-8 xl:grid-cols-4"
                        >
                            <div
                                v-for="product in localProducts"
                                :key="product.id"
                                class="group flex h-full w-full flex-col rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl md:p-6"
                            >
                                <div class="mb-4 flex items-start gap-3">
                                    <div
                                        class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 text-white shadow-md shadow-blue-200/50"
                                    >
                                        <span class="text-lg font-bold">{{
                                            product.product_name.charAt(0)
                                        }}</span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h3
                                            class="text-lg font-bold text-gray-800 transition-colors duration-300 group-hover:text-blue-700"
                                        >
                                            {{ product.product_name }}
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500">
                                            {{
                                                product.unit?.unit_name ??
                                                'Per unit'
                                            }}
                                        </p>
                                    </div>
                                </div>
                                <div
                                    class="mt-auto flex items-center justify-between border-t border-gray-50 pt-4"
                                >
                                    <span
                                        class="text-xs font-medium uppercase tracking-wide text-gray-400"
                                        >Click to order</span
                                    >
                                    <button
                                        @click="
                                            addToCart({
                                                ...product,
                                                quantity: '1',
                                            })
                                        "
                                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-200/50 transition-all duration-300 hover:from-blue-700 hover:to-blue-800 hover:shadow-lg active:scale-95 disabled:pointer-events-none disabled:opacity-50"
                                    >
                                        <ShoppingCartIcon class="h-4 w-4" />
                                        Add
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <nav
                            class="responsive-pagination mt-8"
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
                                            'flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200',
                                            link.active
                                                ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg shadow-blue-200/50'
                                                : 'border border-gray-200 bg-white text-gray-700 hover:border-blue-300 hover:text-blue-600 hover:shadow-md',
                                        ]"
                                    >
                                        <span v-html="link.label"></span>
                                    </Link>
                                    <span
                                        v-else
                                        v-html="link.label"
                                        class="flex items-center justify-center rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-400"
                                    ></span>
                                </template>
                            </template>
                        </nav>
                    </div>
                    <!-- End Pagination -->
                </div>

                <!-- Empty State -->
                <div v-else class="mx-auto max-w-md py-20 text-center">
                    <div
                        class="mx-auto mb-6 flex h-28 w-28 items-center justify-center rounded-3xl bg-gradient-to-br from-blue-50 to-indigo-50 shadow-lg shadow-blue-100/50"
                    >
                        <SearchIcon class="h-14 w-14 text-blue-400" />
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        Find your health products
                    </h2>
                    <p class="mt-3 leading-relaxed text-gray-500">
                        Search for medications, supplements, and health
                        essentials to get started
                    </p>
                </div>
            </div>
        </div>

        <!-- Custom Order Section -->
        <div
            class="border-t border-gray-100 bg-gradient-to-b from-white to-blue-50/50 px-4 py-12 sm:px-6 lg:px-8"
        >
            <div class="mx-auto max-w-xl">
                <div class="mb-8 text-center">
                    <div
                        class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 shadow-lg shadow-amber-200/50"
                    >
                        <ShoppingCartIcon class="h-7 w-7 text-white" />
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        Can't find what you need?
                    </h2>
                    <p class="mt-2 text-gray-500">
                        Create a custom order and we'll source it for you
                    </p>
                </div>
                <div
                    class="rounded-2xl border border-gray-100 bg-white p-6 shadow-xl shadow-gray-100/50"
                >
                    <div class="space-y-4">
                        <div>
                            <label
                                class="mb-1.5 block text-sm font-medium text-gray-700"
                                >Product Name</label
                            >
                            <input
                                v-model="newProduct.name"
                                type="text"
                                placeholder="e.g., Paracetamol 500mg"
                                class="w-full rounded-xl border-gray-200 bg-gray-50 p-3 text-sm transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20"
                            />
                        </div>
                        <div>
                            <label
                                class="mb-1.5 block text-sm font-medium text-gray-700"
                                >Unit</label
                            >
                            <select
                                v-model="newProduct.unit"
                                class="w-full rounded-xl border-gray-200 bg-gray-50 p-3 text-sm transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20"
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
                        </div>
                        <div>
                            <label
                                class="mb-1.5 block text-sm font-medium text-gray-700"
                                >Quantity</label
                            >
                            <input
                                v-model="newProduct.quantity"
                                type="text"
                                placeholder="e.g., 2 Strips"
                                class="w-full rounded-xl border-gray-200 bg-gray-50 p-3 text-sm transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20"
                            />
                        </div>
                        <button
                            class="w-full rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-200/50 transition-all duration-300 hover:from-blue-700 hover:to-blue-800 hover:shadow-xl active:scale-[0.98]"
                            @click="addCustomProductToCart"
                        >
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div
            class="border-t border-gray-100 bg-gradient-to-b from-gray-50 to-white px-4 py-16 sm:px-6 lg:px-8"
        >
            <div class="mx-auto max-w-3xl">
                <div class="mb-8 text-center">
                    <div
                        class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-green-400 to-emerald-500 shadow-lg shadow-green-200/50"
                    >
                        <svg
                            class="h-7 w-7 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
                            />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        Get in Touch
                    </h2>
                    <p class="mt-2 text-gray-500">
                        Have questions? We'd love to hear from you.
                    </p>
                </div>
                <form
                    @submit.prevent="submitContactForm"
                    class="rounded-2xl border border-gray-100 bg-white p-6 shadow-xl shadow-gray-100/50 sm:p-8"
                >
                    <div class="mb-5">
                        <label
                            for="message"
                            class="mb-1.5 block text-sm font-medium text-gray-700"
                            >Your Message</label
                        >
                        <textarea
                            v-model="contactForm.message"
                            required
                            id="message"
                            placeholder="Tell us how we can help you..."
                            rows="5"
                            class="block w-full rounded-xl border-gray-200 bg-gray-50 p-4 text-sm shadow-sm transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20"
                        ></textarea>
                    </div>
                    <button
                        type="submit"
                        class="w-full rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-green-200/50 transition-all duration-300 hover:from-green-600 hover:to-emerald-700 hover:shadow-xl active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-50"
                        :disabled="contactForm.processing"
                    >
                        <span v-if="contactForm.processing">Sending...</span>
                        <span v-else>Send Message</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Checkout Modal -->
        <div
            v-if="isCheckoutModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
            @click.self="toggleCheckoutModal"
        >
            <div
                class="checkout-modal max-h-[85vh] w-full overflow-y-auto rounded-2xl bg-white p-5 shadow-2xl sm:p-8 md:max-w-lg lg:max-w-xl"
            >
                <div class="mb-6 flex items-center justify-between">
                    <h2
                        class="flex items-center gap-2 text-xl font-bold text-gray-800"
                    >
                        <ShoppingCartIcon class="h-6 w-6 text-blue-600" />
                        Checkout
                    </h2>
                    <button
                        class="rounded-xl p-2 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600"
                        @click="toggleCheckoutModal"
                    >
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Empty cart state -->
                <div
                    v-if="
                        consumerStore.cartItems.products.length === 0 &&
                        consumerStore.cartItems.customProducts.length === 0
                    "
                    class="py-12 text-center"
                >
                    <PackageXIcon
                        class="mx-auto mb-3 h-12 w-12 text-gray-300"
                    />
                    <p class="text-gray-500">Your cart is empty</p>
                </div>

                <ul v-else class="space-y-3">
                    <li
                        v-for="(item, index) in consumerStore.cartItems
                            .products"
                        :key="'p-' + index"
                        class="flex items-center justify-between gap-3 rounded-xl border border-gray-100 bg-gray-50/50 p-3 transition-colors hover:bg-gray-50"
                    >
                        <div class="min-w-0 flex-1">
                            <p
                                class="truncate text-sm font-semibold text-gray-800"
                            >
                                {{ item.product_name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ item.unit?.unit_name || 'No unit' }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <input
                                type="text"
                                class="w-14 rounded-lg border-gray-200 bg-white px-2 py-1.5 text-center text-sm font-medium text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                v-model="item.quantity"
                            />
                            <button
                                class="rounded-lg bg-red-50 p-2 text-red-500 transition-colors hover:bg-red-100 hover:text-red-600"
                                @click="deleteProduct(item.pid)"
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                    />
                                </svg>
                            </button>
                        </div>
                    </li>
                    <li
                        v-for="item in consumerStore.cartItems.customProducts"
                        :key="'c-' + item.id"
                        class="flex items-center justify-between gap-3 rounded-xl border border-amber-100 bg-amber-50/50 p-3 transition-colors hover:bg-amber-50"
                    >
                        <div class="min-w-0 flex-1">
                            <div class="mb-0.5 flex items-center gap-1.5">
                                <span
                                    class="rounded-md bg-amber-100 px-1.5 py-0.5 text-[10px] font-bold uppercase text-amber-700"
                                    >Custom</span
                                >
                            </div>
                            <p
                                class="truncate text-sm font-semibold text-gray-800"
                            >
                                {{ item.name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ item.unit?.unit_name || 'No unit' }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <input
                                type="text"
                                class="w-14 rounded-lg border-gray-200 bg-white px-2 py-1.5 text-center text-sm font-medium text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                v-model="item.quantity"
                            />
                            <button
                                class="rounded-lg bg-red-50 p-2 text-red-500 transition-colors hover:bg-red-100 hover:text-red-600"
                                @click="deleteCustomProduct(item.id)"
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                    />
                                </svg>
                            </button>
                        </div>
                    </li>
                </ul>
                <div
                    class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end"
                >
                    <button
                        class="rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition-all duration-200 hover:border-gray-300 hover:bg-gray-50"
                        @click="toggleCheckoutModal"
                    >
                        Continue Shopping
                    </button>
                    <button
                        v-if="
                            consumerStore.cartItems.products.length > 0 ||
                            consumerStore.cartItems.customProducts.length > 0
                        "
                        class="rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-200/50 transition-all duration-300 hover:from-blue-700 hover:to-blue-800 hover:shadow-xl active:scale-[0.98]"
                        @click="placeOrder"
                    >
                        Place Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Toast slide-down animation */
.toast-slide-enter-active {
    transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.toast-slide-leave-active {
    transition: all 0.2s ease-in;
}
.toast-slide-enter-from {
    opacity: 0;
    transform: translate(-50%, -1.5rem) scale(0.9);
}
.toast-slide-leave-to {
    opacity: 0;
    transform: translate(-50%, -0.5rem) scale(0.95);
}

/* Responsive pagination: wrap and prevent overflow */
.responsive-pagination {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
    overflow-x: auto;
    padding: 0.5rem 0;
}

/* Responsive modal: full width on mobile */
@media (max-width: 640px) {
    .checkout-modal {
        margin: 1rem;
        max-height: 90vh;
        border-radius: 1.25rem !important;
    }
}

/* Smooth scrollbar for modal */
.checkout-modal::-webkit-scrollbar {
    width: 6px;
}
.checkout-modal::-webkit-scrollbar-track {
    background: transparent;
}
.checkout-modal::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}
</style>
