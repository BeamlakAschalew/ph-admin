<script setup>
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { ref, watch } from 'vue';
import MainLayout from './MainLayout.vue';

defineOptions({
    layout: MainLayout,
});

const props = defineProps({
    suppliers: Array,
    filters: Object,
});

const localSuppliers = ref(props.suppliers.data);

watch(
    () => props.suppliers,
    (newSuppliers) => {
        localSuppliers.value = newSuppliers.data;
    },
);

const searchQuery = ref(props.filters.search);

const debouncedSearch = debounce((query) => {
    router.get(
        '/suppliers',
        { search: query },
        {
            preserveState: true,
            replace: true,
        },
    );
}, 300);

watch(searchQuery, (newQuery) => {
    debouncedSearch(newQuery);
});

const showEditModal = ref(false);
const showConfirmModal = ref(false);
const supplierToRemove = ref(null);
const editingSupplier = ref({
    id: null,
    institution: '',
    contact: '',
    location: '',
    phone: '',
    active: true,
});

function editSupplier(supplier) {
    editingSupplier.value = {
        ...supplier,
        active: supplier.status === 'Active',
    };
    showEditModal.value = true;
}

function saveSupplier() {
    const index = localSuppliers.value.findIndex(
        (s) => s.id === editingSupplier.value.id,
    );
    if (index !== -1) {
        router.put(`/suppliers/${editingSupplier.value.id}`, {
            ...editingSupplier.value,
            status: editingSupplier.value.active,
        });
    }
    showEditModal.value = false;
}

function confirmRemoveSupplier(id) {
    supplierToRemove.value = id;
    showConfirmModal.value = true;
}

function removeSupplier() {
    router.delete(`/suppliers/${supplierToRemove.value}`, {
        preserveState: true,
        replace: true,
    });
    showConfirmModal.value = false;
    supplierToRemove.value = null;
}
</script>

<template>
    <Head title="Suppliers" />
    <!-- Main Content -->
    <main class="flex-1">
        <div class="mx-auto w-full max-w-4xl px-4 py-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-800">Suppliers List</h1>
                <button
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
                    placeholder="Search users..."
                />
            </div>

            <!-- Suppliers Table -->
            <div class="overflow-hidden rounded-xl border bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                >
                                    Institution
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                >
                                    Location
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                >
                                    Phone
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
                            <tr
                                v-for="supplier in localSuppliers"
                                :key="supplier.id"
                                class="hover:bg-gray-100"
                            >
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ supplier.institution }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ supplier.contact }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ supplier.location }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ supplier.locationDetail }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ supplier.phone }}
                                    </div>
                                    <div
                                        v-if="supplier.phoneAlt"
                                        class="text-sm text-gray-500"
                                    >
                                        {{ supplier.phoneAlt }}
                                    </div>
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 text-end"
                                >
                                    <div class="flex justify-end gap-2">
                                        <button
                                            @click="editSupplier(supplier)"
                                            class="inline-flex items-center rounded-lg border border-gray-200 bg-white p-2 text-sm font-medium text-gray-500 hover:bg-gray-100"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            @click="
                                                confirmRemoveSupplier(
                                                    supplier.id,
                                                )
                                            "
                                            class="inline-flex items-center rounded-lg border border-gray-200 bg-white p-2 text-sm font-medium text-red-500 hover:bg-red-50"
                                        >
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

    <!-- Edit Supplier Modal -->
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
            <div
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                @click="showEditModal = false"
            ></div>
            <div
                class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle"
            >
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        Edit Supplier
                    </h3>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label
                                for="edit-institution"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Institution
                            </label>
                            <input
                                type="text"
                                id="edit-institution"
                                v-model="editingSupplier.institution"
                                class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                        <div>
                            <label
                                for="edit-contact"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Contact
                            </label>
                            <input
                                type="text"
                                id="edit-contact"
                                v-model="editingSupplier.contact"
                                class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                        <div>
                            <label
                                for="edit-location"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Location
                            </label>
                            <input
                                type="text"
                                id="edit-location"
                                v-model="editingSupplier.location"
                                class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                        <div>
                            <label
                                for="edit-phone"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Phone
                            </label>
                            <input
                                type="text"
                                id="edit-phone"
                                v-model="editingSupplier.phone"
                                class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                    </div>
                </div>
                <div
                    class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
                >
                    <button
                        type="button"
                        @click="saveSupplier"
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

    <!-- Confirmation Modal -->
    <div
        v-if="showConfirmModal"
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
    >
        <div
            class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0"
        >
            <div
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                @click="showConfirmModal = false"
            ></div>
            <div
                class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle"
            >
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        Remove Supplier
                    </h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Are you sure you want to remove this supplier? This
                        action cannot be undone.
                    </p>
                </div>
                <div
                    class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
                >
                    <button
                        type="button"
                        @click="removeSupplier"
                        class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Remove
                    </button>
                    <button
                        type="button"
                        @click="showConfirmModal = false"
                        class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:mt-0 sm:w-auto sm:text-sm"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
