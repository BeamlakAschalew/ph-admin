<script setup>
import { computed, ref } from 'vue';
import Navbar from './Navbar.vue';

// Products data
const products = ref([
    { id: 1, name: 'Paracetamol', unit: 'tablet' },
    { id: 2, name: 'Amoxicillin', unit: 'capsule' },
    { id: 3, name: 'Azithromycin', unit: 'g' },
    { id: 4, name: 'Hydrocortisone', unit: 'ml' },
    { id: 5, name: 'Furosemide', unit: 'ampoule' },
    { id: 6, name: 'Metformin', unit: 'tablet' },
    { id: 7, name: 'Insulin', unit: 'vial' },
    { id: 8, name: 'Ceftriaxone', unit: 'vial' },
    { id: 9, name: 'Diazepam', unit: 'ampoule' },
    { id: 10, name: 'Omeprazole', unit: 'capsule' },
    { id: 11, name: 'Ciprofloxacin', unit: 'tablet' },
]);

// Search functionality
const searchQuery = ref('');

const filteredProducts = computed(() => {
    if (!searchQuery.value) return products.value;

    const query = searchQuery.value.toLowerCase();
    return products.value.filter(
        (product) =>
            product.name.toLowerCase().includes(query) ||
            product.unit.toLowerCase().includes(query),
    );
});

// Edit product functionality
const showEditModal = ref(false);
const editingProduct = ref({ id: null, name: '', unit: '' });

const openEditModal = (product) => {
    editingProduct.value = { ...product };
    showEditModal.value = true;
};

const saveProduct = () => {
    const index = products.value.findIndex(
        (p) => p.id === editingProduct.value.id,
    );
    if (index !== -1) {
        products.value[index] = { ...editingProduct.value };
    }
    showEditModal.value = false;
};

// Delete product functionality
const showDeleteModal = ref(false);
const productToDelete = ref(null);

const openDeleteModal = (productId) => {
    productToDelete.value = productId;
    showDeleteModal.value = true;
};

const confirmDelete = () => {
    products.value = products.value.filter(
        (p) => p.id !== productToDelete.value,
    );
    showDeleteModal.value = false;
    productToDelete.value = null;
};

// Add product functionality
const showAddModal = ref(false);
const newProduct = ref({ name: '', unit: '' });

const openAddModal = () => {
    newProduct.value = { name: '', unit: '' };
    showAddModal.value = true;
};

const addProduct = () => {
    const newId = Math.max(...products.value.map((p) => p.id)) + 1;
    products.value.push({
        id: newId,
        name: newProduct.value.name,
        unit: newProduct.value.unit,
    });
    showAddModal.value = false;
};
</script>

<template>
    <div class="flex min-h-screen flex-col">
        <!-- Import the Navbar component -->
        <Navbar />

        <!-- Main Content -->
        <main class="flex-1 bg-amber-50">
            <div class="mx-auto w-full max-w-4xl px-4 py-6">
                <div class="mb-6 flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-gray-800">
                        Products List
                    </h1>
                    <button
                        @click="openAddModal"
                        class="inline-flex items-center justify-center rounded-full bg-teal-600 p-2 text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2"
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
                            class="h-6 w-6"
                        >
                            <path d="M5 12h14"></path>
                            <path d="M12 5v14"></path>
                        </svg>
                    </button>
                </div>

                <!-- Search Bar -->
                <div class="mb-6">
                    <input
                        type="text"
                        v-model="searchQuery"
                        class="block w-full rounded-lg border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Search products..."
                    />
                </div>

                <!-- Products Table -->
                <div
                    class="overflow-hidden rounded-xl border bg-white shadow-sm"
                >
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                    >
                                        Product Name
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                    >
                                        Unit
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-end text-xs font-medium uppercase text-gray-500"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-if="filteredProducts.length === 0">
                                    <td
                                        colspan="3"
                                        class="px-6 py-8 text-center text-gray-500"
                                    >
                                        No products found
                                    </td>
                                </tr>
                                <tr
                                    v-for="product in filteredProducts"
                                    :key="product.id"
                                    class="hover:bg-gray-100"
                                >
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ product.name }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ product.unit }}
                                        </div>
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-end"
                                    >
                                        <div class="flex justify-end gap-2">
                                            <button
                                                @click="openEditModal(product)"
                                                class="inline-flex items-center gap-x-1 rounded-lg border border-transparent bg-blue-500 px-3 py-1 text-sm font-medium text-white hover:bg-blue-600"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="16"
                                                    height="16"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="h-4 w-4"
                                                >
                                                    <path
                                                        d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"
                                                    ></path>
                                                    <path d="m15 5 4 4"></path>
                                                </svg>
                                                Edit
                                            </button>
                                            <button
                                                @click="
                                                    openDeleteModal(product.id)
                                                "
                                                class="inline-flex items-center gap-x-1 rounded-lg border border-gray-300 bg-gray-400 px-3 py-1 text-sm font-medium text-white hover:bg-gray-500"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="16"
                                                    height="16"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="h-4 w-4"
                                                >
                                                    <path d="M3 6h18"></path>
                                                    <path
                                                        d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"
                                                    ></path>
                                                    <path
                                                        d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"
                                                    ></path>
                                                    <line
                                                        x1="10"
                                                        x2="10"
                                                        y1="11"
                                                        y2="17"
                                                    ></line>
                                                    <line
                                                        x1="14"
                                                        x2="14"
                                                        y1="11"
                                                        y2="17"
                                                    ></line>
                                                </svg>
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        <!-- Edit Product Modal -->
        <div
            v-if="showEditModal"
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div
                class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0"
            >
                <!-- Background overlay -->
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    @click="showEditModal = false"
                ></div>

                <!-- Modal panel -->
                <div
                    class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle"
                >
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mt-3 w-full text-center sm:ml-4 sm:mt-0 sm:text-left"
                            >
                                <h3
                                    class="text-lg font-medium leading-6 text-gray-900"
                                    id="modal-title"
                                >
                                    Edit Product
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label
                                            for="edit-name"
                                            class="block text-sm font-medium text-gray-700"
                                            >Product Name</label
                                        >
                                        <input
                                            type="text"
                                            id="edit-name"
                                            v-model="editingProduct.name"
                                            class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            for="edit-unit"
                                            class="block text-sm font-medium text-gray-700"
                                            >Unit</label
                                        >
                                        <select
                                            id="edit-unit"
                                            v-model="editingProduct.unit"
                                            class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                        >
                                            <option value="tablet">
                                                Tablet
                                            </option>
                                            <option value="capsule">
                                                Capsule
                                            </option>
                                            <option value="g">Gram (g)</option>
                                            <option value="ml">
                                                Milliliter (ml)
                                            </option>
                                            <option value="ampoule">
                                                Ampoule
                                            </option>
                                            <option value="vial">Vial</option>
                                            <option value="bottle">
                                                Bottle
                                            </option>
                                            <option value="sachet">
                                                Sachet
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
                    >
                        <button
                            type="button"
                            @click="saveProduct"
                            class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Save Changes
                        </button>
                        <button
                            type="button"
                            @click="showEditModal = false"
                            class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:mt-0 sm:w-auto sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div
                class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0"
            >
                <!-- Background overlay -->
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    @click="showDeleteModal = false"
                ></div>

                <!-- Modal panel -->
                <div
                    class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle"
                >
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-red-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    aria-hidden="true"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                    />
                                </svg>
                            </div>
                            <div
                                class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left"
                            >
                                <h3
                                    class="text-lg font-medium leading-6 text-gray-900"
                                    id="modal-title"
                                >
                                    Delete Product
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to delete this
                                        product? This action cannot be undone.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
                    >
                        <button
                            type="button"
                            @click="confirmDelete"
                            class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Delete
                        </button>
                        <button
                            type="button"
                            @click="showDeleteModal = false"
                            class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:mt-0 sm:w-auto sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Product Modal -->
        <div
            v-if="showAddModal"
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div
                class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0"
            >
                <!-- Background overlay -->
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    @click="showAddModal = false"
                ></div>

                <!-- Modal panel -->
                <div
                    class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle"
                >
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mt-3 w-full text-center sm:ml-4 sm:mt-0 sm:text-left"
                            >
                                <h3
                                    class="text-lg font-medium leading-6 text-gray-900"
                                    id="modal-title"
                                >
                                    Add New Product
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label
                                            for="add-name"
                                            class="block text-sm font-medium text-gray-700"
                                            >Product Name</label
                                        >
                                        <input
                                            type="text"
                                            id="add-name"
                                            v-model="newProduct.name"
                                            class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            for="add-unit"
                                            class="block text-sm font-medium text-gray-700"
                                            >Unit</label
                                        >
                                        <select
                                            id="add-unit"
                                            v-model="newProduct.unit"
                                            class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                        >
                                            <option value="tablet">
                                                Tablet
                                            </option>
                                            <option value="capsule">
                                                Capsule
                                            </option>
                                            <option value="g">Gram (g)</option>
                                            <option value="ml">
                                                Milliliter (ml)
                                            </option>
                                            <option value="ampoule">
                                                Ampoule
                                            </option>
                                            <option value="vial">Vial</option>
                                            <option value="bottle">
                                                Bottle
                                            </option>
                                            <option value="sachet">
                                                Sachet
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
                    >
                        <button
                            type="button"
                            @click="addProduct"
                            class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Add Product
                        </button>
                        <button
                            type="button"
                            @click="showAddModal = false"
                            class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:mt-0 sm:w-auto sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
