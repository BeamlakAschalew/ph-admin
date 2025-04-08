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
                        class="text-primary flex-none py-4 text-xl font-semibold"
                        href="#"
                        aria-label="MediShop"
                    >
                        <PillIcon class="mr-2 inline-block h-8 w-8" />
                        MediShop
                    </a>
                    <div class="sm:hidden">
                        <button
                            type="button"
                            class="focus:ring-primary inline-flex items-center justify-center gap-2 rounded-md border border-gray-200 p-2 text-sm text-gray-600 transition-all hover:bg-gray-100 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2"
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
                                class="focus:ring-primary focus:border-primary block w-full rounded-full border border-gray-300 bg-gray-50 p-2.5 ps-10 text-sm text-gray-900 transition-all duration-300"
                                placeholder="Search medications..."
                            />
                        </div>

                        <button
                            class="bg-primary hover:bg-primary-dark mb-6 inline-flex items-center gap-x-2 rounded-full border border-transparent px-4 py-2 text-sm font-semibold text-white transition-all duration-300 disabled:pointer-events-none disabled:opacity-50 sm:mb-0"
                            @click="goToCheckout"
                        >
                            <ShoppingCartIcon class="h-4 w-4" />
                            Checkout ({{ cartCount }})
                        </button>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <div
            class="relative overflow-hidden before:absolute before:start-1/2 before:top-0 before:-z-[1] before:size-full before:-translate-x-1/2 before:transform before:bg-[url('https://preline.co/assets/svg/component/polygon-bg-element.svg')] before:bg-top before:bg-no-repeat"
        >
            <div class="mx-auto max-w-[85rem] px-4 pb-10 pt-16 sm:px-6 lg:px-8">
                <!-- Title -->
                <div class="mx-auto mt-5 max-w-2xl text-center">
                    <h1
                        class="block text-4xl font-bold text-gray-800 md:text-5xl lg:text-6xl"
                    >
                        Your Health, Our Priority
                    </h1>
                </div>
                <!-- End Title -->

                <div class="mx-auto mt-5 max-w-3xl text-center">
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
                                class="focus:border-primary focus:ring-primary block w-full rounded-full border-gray-200 px-4 py-3 text-sm shadow-sm transition-all duration-300 disabled:pointer-events-none disabled:opacity-50"
                                placeholder="Search for medications..."
                            />
                        </div>
                        <button
                            class="bg-primary hover:bg-primary-dark inline-flex w-full items-center justify-center gap-x-2 rounded-full border border-transparent px-4 py-3 text-sm font-semibold text-white transition-all duration-300 disabled:pointer-events-none disabled:opacity-50 sm:w-auto"
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
            class="mx-auto w-full flex-grow px-4 py-10 sm:w-auto sm:px-6 lg:px-8 lg:py-14"
        >
            <!-- Search Results -->
            <div v-if="searchQuery.trim()">
                <div class="mx-auto mb-10 max-w-2xl text-center">
                    <h2
                        class="text-2xl font-bold text-gray-800 md:text-3xl md:leading-tight"
                    >
                        Search Results for "{{ searchQuery }}"
                    </h2>
                </div>

                <div
                    v-if="loading"
                    class="flex items-center justify-center py-12"
                >
                    <div
                        class="border-primary h-12 w-12 animate-spin rounded-full border-4 border-r-transparent"
                    ></div>
                </div>

                <div
                    v-else-if="paginatedProducts.length === 0"
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
                        class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                    >
                        <div
                            v-for="product in paginatedProducts"
                            :key="product.id"
                            class="group flex h-full w-full flex-col rounded-xl border border-gray-200 bg-white shadow-sm transition duration-300 hover:shadow-md"
                        >
                            <div class="p-6 md:p-8">
                                <h3
                                    class="group-hover:text-primary text-xl font-semibold text-gray-800 transition-colors duration-300"
                                >
                                    {{ product.name }}
                                </h3>
                                <p class="mt-2 text-gray-500">
                                    {{ product.unit }}
                                </p>
                                <div class="mt-6">
                                    <button
                                        @click="addToCart(product)"
                                        class="bg-primary hover:bg-primary-dark inline-flex w-full items-center justify-center gap-x-2 rounded-lg border border-transparent px-4 py-3 text-sm font-semibold text-white transition-all duration-300 disabled:pointer-events-none disabled:opacity-50"
                                    >
                                        <ShoppingCartIcon class="h-4 w-4" />
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12 flex justify-center">
                        <nav class="flex items-center gap-x-1">
                            <button
                                @click="
                                    currentPage = Math.max(1, currentPage - 1)
                                "
                                :disabled="currentPage === 1"
                                class="flex size-8 items-center justify-center rounded-full text-gray-800 transition-all duration-300 hover:bg-gray-100 disabled:pointer-events-none disabled:opacity-50"
                                aria-label="Previous"
                            >
                                <ChevronLeftIcon class="h-4 w-4" />
                            </button>

                            <div class="flex items-center gap-x-1">
                                <button
                                    v-for="page in totalPages"
                                    :key="page"
                                    @click="currentPage = page"
                                    :class="[
                                        'flex size-8 items-center justify-center rounded-full transition-all duration-300',
                                        currentPage === page
                                            ? 'bg-primary hover:bg-primary-dark text-white'
                                            : 'text-gray-800 hover:bg-gray-100',
                                    ]"
                                >
                                    {{ page }}
                                </button>
                            </div>

                            <button
                                @click="
                                    currentPage = Math.min(
                                        totalPages,
                                        currentPage + 1,
                                    )
                                "
                                :disabled="currentPage === totalPages"
                                class="flex size-8 items-center justify-center rounded-full text-gray-800 transition-all duration-300 hover:bg-gray-100 disabled:pointer-events-none disabled:opacity-50"
                                aria-label="Next"
                            >
                                <ChevronRightIcon class="h-4 w-4" />
                            </button>
                        </nav>
                    </div>
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
                    class="grid grid-cols-2 gap-6 md:grid-cols-4 lg:grid-cols-5"
                >
                    <div class="col-span-full lg:col-span-1">
                        <a
                            class="text-primary flex-none text-xl font-semibold"
                            href="#"
                            aria-label="MediShop"
                        >
                            <PillIcon class="mr-2 h-8 w-8" />
                            MediShop
                        </a>
                        <p class="mt-3 text-xs text-gray-600 sm:text-sm">
                            Your trusted source for quality medications and
                            health products.
                        </p>
                    </div>
                    <!-- End Col -->

                    <div class="col-span-1">
                        <h4 class="font-semibold text-gray-900">Product</h4>

                        <div class="mt-3 grid space-y-3">
                            <p>
                                <a
                                    class="inline-flex gap-x-2 text-gray-600 hover:text-gray-800"
                                    href="#"
                                    >Pricing</a
                                >
                            </p>
                            <p>
                                <a
                                    class="inline-flex gap-x-2 text-gray-600 hover:text-gray-800"
                                    href="#"
                                    >Medications</a
                                >
                            </p>
                            <p>
                                <a
                                    class="inline-flex gap-x-2 text-gray-600 hover:text-gray-800"
                                    href="#"
                                    >Supplements</a
                                >
                            </p>
                            <p>
                                <a
                                    class="inline-flex gap-x-2 text-gray-600 hover:text-gray-800"
                                    href="#"
                                    >Health Devices</a
                                >
                            </p>
                        </div>
                    </div>
                    <!-- End Col -->

                    <div class="col-span-1">
                        <h4 class="font-semibold text-gray-900">Company</h4>

                        <div class="mt-3 grid space-y-3">
                            <p>
                                <a
                                    class="inline-flex gap-x-2 text-gray-600 hover:text-gray-800"
                                    href="#"
                                    >About us</a
                                >
                            </p>
                            <p>
                                <a
                                    class="inline-flex gap-x-2 text-gray-600 hover:text-gray-800"
                                    href="#"
                                    >Blog</a
                                >
                            </p>
                            <p>
                                <a
                                    class="inline-flex gap-x-2 text-gray-600 hover:text-gray-800"
                                    href="#"
                                    >Careers</a
                                >
                                <span
                                    class="bg-primary ml-1 inline rounded-full px-2 py-1 text-xs text-white"
                                    >We're hiring</span
                                >
                            </p>
                            <p>
                                <a
                                    class="inline-flex gap-x-2 text-gray-600 hover:text-gray-800"
                                    href="#"
                                    >Customers</a
                                >
                            </p>
                        </div>
                    </div>
                    <!-- End Col -->

                    <div class="col-span-2">
                        <h4 class="font-semibold text-gray-900">
                            Stay connected
                        </h4>

                        <div class="mt-3">
                            <p class="text-sm text-gray-600">
                                Subscribe to our newsletter for the latest
                                updates and offers.
                            </p>
                            <form>
                                <div
                                    class="mt-4 flex flex-col items-center gap-2 rounded-lg bg-white p-2 sm:flex-row sm:gap-3"
                                >
                                    <div class="w-full">
                                        <label for="hero-input" class="sr-only"
                                            >Search</label
                                        >
                                        <input
                                            type="text"
                                            id="hero-input"
                                            name="hero-input"
                                            class="focus:border-primary focus:ring-primary block w-full rounded-lg border-gray-200 px-4 py-3 text-sm disabled:pointer-events-none disabled:opacity-50"
                                            placeholder="Enter your email"
                                        />
                                    </div>
                                    <a
                                        class="bg-primary hover:bg-primary-dark inline-flex w-full items-center justify-center gap-x-2 whitespace-nowrap rounded-lg border border-transparent px-4 py-3 text-sm font-semibold text-white disabled:pointer-events-none disabled:opacity-50 sm:w-auto"
                                        href="#"
                                    >
                                        Subscribe
                                    </a>
                                </div>
                                <p class="mt-3 text-xs text-gray-500">
                                    By submitting this form you agree to our
                                    <a
                                        class="text-primary font-medium decoration-2 hover:underline"
                                        href="#"
                                        >terms and conditions</a
                                    >
                                    and
                                    <a
                                        class="text-primary font-medium decoration-2 hover:underline"
                                        href="#"
                                        >privacy policy</a
                                    >.
                                </p>
                            </form>
                        </div>
                    </div>
                    <!-- End Col -->
                </div>
                <!-- End Grid -->

                <div
                    class="mt-12 grid gap-y-2 sm:flex sm:items-center sm:justify-between sm:gap-y-0"
                >
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600">
                            © 2023 MediShop. All rights reserved.
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

<script setup>
import {
    ChevronLeftIcon,
    ChevronRightIcon,
    MenuIcon,
    PackageXIcon,
    PillIcon,
    SearchIcon,
    ShoppingCartIcon,
    XIcon,
} from 'lucide-vue-next';
import { computed, onMounted, ref, watch } from 'vue';

// State
const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 8;
const loading = ref(false);
const cartCount = ref(0);
const isMenuOpen = ref(false);

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

// Mock data for products - simplified to just the measurement unit without form description
const allProducts = ref([
    { id: 1, name: 'Paracetamol', unit: '500mg' },
    { id: 2, name: 'Amoxicillin', unit: '250mg' },
    { id: 3, name: 'Vitamin D3', unit: '1000IU' },
    { id: 4, name: 'Omeprazole', unit: '20mg' },
    { id: 5, name: 'Cetirizine', unit: '10mg' },
    { id: 6, name: 'Metformin', unit: '500mg' },
    { id: 7, name: 'Ibuprofen', unit: '200mg' },
    { id: 8, name: 'Omega-3', unit: '1000mg' },
    { id: 9, name: 'Loratadine', unit: '10mg' },
    { id: 10, name: 'Probiotic Complex', unit: '50B CFU' },
    { id: 11, name: 'Atorvastatin', unit: '10mg' },
    { id: 12, name: 'Melatonin', unit: '5mg' },
    { id: 13, name: 'Multivitamin', unit: 'Daily' },
    { id: 14, name: 'Aspirin', unit: '81mg' },
    { id: 15, name: 'Calcium', unit: '600mg' },
    { id: 16, name: 'Lisinopril', unit: '10mg' },
    { id: 17, name: 'Zinc', unit: '50mg' },
    { id: 18, name: 'Magnesium', unit: '400mg' },
    { id: 19, name: 'Folic Acid', unit: '400mcg' },
    { id: 20, name: 'Vitamin C', unit: '1000mg' },
]);

// Computed properties
const filteredProducts = computed(() => {
    if (!searchQuery.value.trim()) return [];

    const query = searchQuery.value.toLowerCase();
    return allProducts.value.filter(
        (product) =>
            product.name.toLowerCase().includes(query) ||
            product.unit.toLowerCase().includes(query),
    );
});

const totalPages = computed(() => {
    return Math.ceil(filteredProducts.value.length / itemsPerPage);
});

const paginatedProducts = computed(() => {
    const startIndex = (currentPage.value - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    return filteredProducts.value.slice(startIndex, endIndex);
});

// Watchers
watch(searchQuery, () => {
    currentPage.value = 1;
    loading.value = true;
    setTimeout(() => {
        loading.value = false;
    }, 500);
});

watch(filteredProducts, () => {
    if (currentPage.value > totalPages.value && totalPages.value > 0) {
        currentPage.value = totalPages.value;
    }
});

// Lifecycle hooks
onMounted(() => {
    // Simulate initial loading
    loading.value = true;
    setTimeout(() => {
        loading.value = false;
    }, 1000);
});
</script>

<style>
:root {
    --color-primary: #2563eb;
    --color-primary-dark: #1d4ed8;
}

.text-primary {
    color: var(--color-primary);
}

.bg-primary {
    background-color: var(--color-primary);
}

.bg-primary-dark {
    background-color: var(--color-primary-dark);
}

.hover\:bg-primary-dark:hover {
    background-color: var(--color-primary-dark);
}

.focus\:ring-primary:focus {
    --tw-ring-color: var(--color-primary);
}

.size-8 {
    width: 2rem;
    height: 2rem;
}

.size-10 {
    width: 2.5rem;
    height: 2.5rem;
}

.size-12 {
    width: 3rem;
    height: 3rem;
}

.size-4 {
    width: 1rem;
    height: 1rem;
}
</style>
