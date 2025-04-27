<script setup>
import Navbar from '@/Components/Consumer/Navbar.vue';
import { useConsumerStore } from '@/stores/consumerStore';
import { router, useForm } from '@inertiajs/vue3';
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

const contactForm = useForm({
    name: '',
    phone: '',
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
    <div class="flex min-h-screen flex-col bg-gray-50">
        <Navbar :subcities="subcities" @goToCheckout="goToCheckout" />
        <!-- Feedback Toasts -->
        <transition name="fade">
            <div
                v-if="addToCartFeedback"
                class="fixed left-1/2 top-6 z-[9999] -translate-x-1/2 rounded bg-green-600 px-4 py-2 text-white shadow-lg"
                style="pointer-events: none"
            >
                Added to cart!
            </div>
        </transition>
        <transition name="fade">
            <div
                v-if="addCustomFeedback"
                class="fixed left-1/2 top-6 z-[9999] -translate-x-1/2 rounded bg-green-600 px-4 py-2 text-white shadow-lg"
                style="pointer-events: none"
            >
                Custom product added!
            </div>
        </transition>
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
        <div class="relative flex-grow">
            <div
                class="absolute inset-0 bg-cover bg-center"
                style="background-image: url('/background.png')"
            ></div>
            <div class="relative px-4 py-1 sm:px-6 lg:px-8 lg:py-14">
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
                                            {{
                                                product.unit?.unit_name ??
                                                'None'
                                            }}
                                        </p>
                                    </div>
                                    <button
                                        @click="
                                            addToCart({
                                                ...product,
                                                quantity: '1',
                                            })
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
                            class="responsive-pagination mt-5"
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

        <div class="mx-2 my-10">
            <h2 class="mb-4 text-center text-2xl font-medium text-gray-800">
                Contact Us
            </h2>
            <form
                @submit.prevent="submitContactForm"
                class="mx-auto max-w-3xl rounded-lg bg-white p-6 shadow-md"
            >
                <div class="mb-4">
                    <label
                        for="name"
                        class="block text-sm font-medium text-gray-700"
                        >Name</label
                    >
                    <input
                        v-model="contactForm.name"
                        type="text"
                        id="name"
                        placeholder="Your name"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600"
                    />
                </div>
                <div class="mb-4">
                    <label
                        for="phone"
                        class="block text-sm font-medium text-gray-700"
                        >Phone</label
                    >
                    <input
                        v-model="contactForm.phone"
                        required
                        type="text"
                        id="phone"
                        placeholder="Your Phone Number"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600"
                    />
                </div>
                <div class="mb-4">
                    <label
                        for="message"
                        class="block text-sm font-medium text-gray-700"
                        >Message</label
                    >
                    <textarea
                        v-model="contactForm.message"
                        required
                        id="message"
                        placeholder="Your Message"
                        rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600"
                    ></textarea>
                </div>
                <button
                    type="submit"
                    class="w-full rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
                    :disabled="contactForm.processing"
                >
                    Send
                </button>
            </form>
        </div>

        <!-- Checkout Modal -->
        <div
            v-if="isCheckoutModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        >
            <div
                class="checkout-modal max-h-[90vh] min-w-[80%] overflow-y-auto rounded-lg bg-white p-3 px-2 sm:p-8 md:max-w-md lg:min-w-fit"
            >
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
                                @click="deleteProduct(item.pid)"
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
                <div
                    class="mt-6 flex flex-col gap-2 sm:flex-row sm:justify-end"
                >
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

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
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

/* Responsive modal: full width on mobile, no horizontal scroll */
@media (max-width: 640px) {
    .checkout-modal {
        width: 100vw !important;
        max-width: 100vw !important;
        min-width: 0 !important;
        border-radius: 0.75rem;
        padding: 1rem !important;
        left: 0;
        right: 0;
        margin: 0 auto;
    }
}
</style>
