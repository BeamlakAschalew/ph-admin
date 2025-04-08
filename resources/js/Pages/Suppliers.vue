<script setup>
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { ref, watch } from 'vue';
import MainLayout from './Admin/MainLayout.vue';

defineOptions({
    layout: MainLayout,
});

const props = defineProps({
    suppliers: Object,
    filters: Object,
    subcities: Array,
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
        '/admin/suppliers',
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
    institution_name: '',
    first_name: '',
    last_name: '',
    special_place: '',
    primary_phone: '',
    secondary_phone: '',
    subcity_id: null,
    password: '',
    status: 'Approved',
});

function editSupplier(supplier) {
    console.log(supplier);
    editingSupplier.value = {
        ...supplier,
        status: supplier.deleted_at ? 'Rejected' : 'Approved',
    };
    showEditModal.value = true;
}

function saveSupplier() {
    const index = localSuppliers.value.findIndex(
        (c) => c.id === editingSupplier.value.id,
    );
    if (index !== -1) {
        console.log(editingSupplier.value);
        router.put(`/admin/suppliers/${editingSupplier.value.id}`, {
            ...editingSupplier.value,
        });
    }
    showEditModal.value = false;
}

function confirmRemoveSupplier(id) {
    supplierToRemove.value = id;
    showConfirmModal.value = true;
}

function removeSupplier() {
    router.delete(`/admin/suppliers/${supplierToRemove.value}`, {
        preserveState: true,
        replace: true,
    });
    showConfirmModal.value = false;
    supplierToRemove.value = null;
}

const showAddModal = ref(false);
const newSupplier = ref({
    institution_name: '',
    first_name: '',
    last_name: '',
    special_place: '',
    primary_phone: '',
    secondary_phone: '',
    subcity_id: null,
    woreda: null,
    password: '',
    license_number: '',
});

function addSupplier() {
    router.post('/admin/suppliers', {
        ...newSupplier.value,
    });
    showAddModal.value = false;
    newSupplier.value = {
        institution_name: '',
        first_name: '',
        last_name: '',
        special_place: '',
        primary_phone: '',
        secondary_phone: '',
        subcity_id: null,
        woreda: null,
        password: '',
        license_number: '',
    };
}
</script>

<template>
    <Head title="Suppliers" />
    <!-- Main Content -->
    <main class="flex-1">
        <div class="mx-auto w-full max-w-6xl px-4 py-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-800">Suppliers List</h1>
                <button
                    v-if="$page.props.auth.user_role === 'superadmin'"
                    @click="showAddModal = true"
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
                                    class="px-6 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                >
                                    Status
                                </th>
                                <th
                                    v-if="
                                        $page.props.auth.user_role ===
                                        'superadmin'
                                    "
                                    scope="col"
                                    class="px-6 py-3 text-end text-xs font-medium uppercase text-gray-500"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-if="localSuppliers.length === 0">
                                <td
                                    colspan="3"
                                    class="px-6 py-8 text-center text-gray-500"
                                >
                                    No suppliers found
                                </td>
                            </tr>
                            <tr
                                v-for="supplier in localSuppliers"
                                :key="supplier.id"
                                class="hover:bg-gray-100"
                            >
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ supplier.institution_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ supplier.first_name }}
                                        {{ supplier.last_name }}
                                    </div>
                                </td>
                                <td
                                    class="whitespace-normal break-words px-6 py-4"
                                >
                                    <div class="text-sm text-gray-900">
                                        {{ supplier.subcity.subcity_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Woreda {{ supplier.woreda }}
                                        {{ supplier.special_place }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        +251{{ supplier.primary_phone }}
                                    </div>
                                    <div
                                        v-if="supplier.secondary_phone"
                                        class="text-sm text-gray-500"
                                    >
                                        +251{{ supplier.secondary_phone }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span
                                        :class="[
                                            supplier.deleted_at
                                                ? 'bg-red-100 text-red-800'
                                                : 'bg-green-100 text-green-800',
                                            'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                        ]"
                                    >
                                        {{
                                            supplier.deleted_at
                                                ? 'Rejected'
                                                : 'Approved'
                                        }}
                                    </span>
                                </td>
                                <td
                                    v-if="
                                        $page.props.auth.user_role ===
                                        'superadmin'
                                    "
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
            <!-- Pagination -->
            <nav
                class="mt-5 flex items-center -space-x-px"
                aria-label="Pagination"
            >
                <template v-if="suppliers.links && suppliers.links.length">
                    <template
                        v-for="(link, index) in suppliers.links"
                        :key="index"
                    >
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            :class="[
                                index === 0 ? 'rounded-l-md' : '',
                                index === suppliers.links.length - 1
                                    ? 'rounded-r-md'
                                    : '',
                                'min-h-9.5 min-w-9.5 flex items-center justify-center px-3 py-2 text-sm',
                                link.active
                                    ? 'bg-gray-600 text-white'
                                    : 'border border-gray-200 text-gray-800 hover:bg-gray-100',
                            ]"
                        >
                            <span v-html="link.label"></span>
                        </Link>
                        <span
                            v-else
                            v-html="link.label"
                            :class="[
                                index === 0 ? 'rounded-l-md' : '',
                                index === suppliers.links.length - 1
                                    ? 'rounded-r-md'
                                    : '',
                                'min-h-9.5 min-w-9.5 flex items-center justify-center border border-gray-200 px-3 py-2 text-gray-800',
                            ]"
                        ></span>
                    </template>
                </template>
            </nav>
            <!-- End Pagination -->
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
                                for="edit-firstname"
                                class="block text-sm font-medium text-gray-700"
                            >
                                First name
                            </label>

                            <input
                                type="text"
                                id="edit-firstname"
                                required
                                v-model="editingSupplier.first_name"
                                class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                        <div>
                            <label
                                for="edit-lastname"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Last name
                            </label>
                            <input
                                type="text"
                                id="edit-institution"
                                required
                                v-model="editingSupplier.last_name"
                                class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                        <div>
                            <label
                                for="edit-institution"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Institution name
                            </label>
                            <input
                                type="text"
                                id="edit-institution"
                                required
                                v-model="editingSupplier.institution_name"
                                class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                            />
                        </div>

                        <div>
                            <label
                                for="edit-subcity"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Subcity
                            </label>
                            <select
                                id="edit-subcity"
                                required
                                v-model="editingSupplier.subcity_id"
                                class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                            >
                                <option disabled value="">
                                    Select Subcity
                                </option>
                                <option
                                    v-for="subcity in subcities"
                                    :key="subcity.id"
                                    :value="subcity.id"
                                >
                                    {{ subcity.subcity_name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label
                                for="edit-woreda"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Woreda
                            </label>
                            <select
                                id="edit-woreda"
                                required
                                v-model="editingSupplier.woreda"
                                class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                            >
                                <option disabled value="">Select Woreda</option>
                                <option v-for="n in 14" :key="n" :value="n">
                                    {{ n }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label
                                for="edit-special-place"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Special Place
                            </label>
                            <input
                                type="text"
                                required
                                id="edit-special-place"
                                v-model="editingSupplier.special_place"
                                class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                        <div>
                            <label
                                for="edit-phone"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Primary Phone
                            </label>
                            <input
                                type="text"
                                required
                                id="edit-phone"
                                v-model="editingSupplier.primary_phone"
                                class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                        <div>
                            <label
                                for="edit-phone"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Secondary Phone
                            </label>
                            <input
                                type="text"
                                required
                                id="edit-phone"
                                v-model="editingSupplier.secondary_phone"
                                class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                        <div>
                            <label
                                for="edit-password"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Password
                            </label>
                            <input
                                type="password"
                                required
                                id="edit-password"
                                v-model="editingSupplier.password"
                                class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                        <div>
                            <label
                                for="edit-status"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Status
                            </label>
                            <select
                                id="edit-status"
                                v-model="editingSupplier.status"
                                class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
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

    <!-- Add Supplier Modal -->
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
            <div
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                @click="showAddModal = false"
            ></div>
            <div
                class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle"
            >
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        Add Supplier
                    </h3>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label
                                for="add-firstname"
                                class="block text-sm font-medium text-gray-700"
                            >
                                First name
                            </label>
                            <input
                                type="text"
                                id="add-firstname"
                                v-model="newSupplier.first_name"
                                class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                        <div>
                            <label
                                for="add-lastname"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Last name
                            </label>
                            <input
                                type="text"
                                id="add-lastname"
                                v-model="newSupplier.last_name"
                                class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                        <div>
                            <label
                                for="add-institution"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Institution name
                            </label>
                            <input
                                type="text"
                                id="add-institution"
                                v-model="newSupplier.institution_name"
                                class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                        <div>
                            <label
                                for="add-subcity"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Subcity
                            </label>
                            <select
                                id="add-subcity"
                                v-model="newSupplier.subcity_id"
                                class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                            >
                                <option disabled value="">
                                    Select Subcity
                                </option>
                                <option
                                    v-for="subcity in subcities"
                                    :key="subcity.id"
                                    :value="subcity.id"
                                >
                                    {{ subcity.subcity_name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label
                                for="add-woreda"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Woreda
                            </label>
                            <select
                                id="add-woreda"
                                v-model="newSupplier.woreda"
                                class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                            >
                                <option disabled value="">Select Woreda</option>
                                <option v-for="n in 14" :key="n" :value="n">
                                    {{ n }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label
                                for="add-special-place"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Special Place
                            </label>
                            <input
                                type="text"
                                id="add-special-place"
                                v-model="newSupplier.special_place"
                                class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                        <div>
                            <label
                                for="add-phone"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Primary Phone
                            </label>
                            <input
                                type="text"
                                id="add-phone"
                                v-model="newSupplier.primary_phone"
                                class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                        <div>
                            <label
                                for="add-phone"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Secondary Phone
                            </label>
                            <input
                                type="text"
                                id="add-phone"
                                v-model="newSupplier.secondary_phone"
                                class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                        <div>
                            <label
                                for="add-password"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Password
                            </label>
                            <input
                                type="password"
                                id="add-password"
                                v-model="newSupplier.password"
                                class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                            />
                        </div>
                        <div>
                            <label
                                for="add-license_number"
                                class="block text-sm font-medium text-gray-700"
                            >
                                License
                            </label>
                            <input
                                type="text"
                                id="add-license_number"
                                v-model="newSupplier.license_number"
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
                        @click="addSupplier"
                        class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Add Supplier
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
