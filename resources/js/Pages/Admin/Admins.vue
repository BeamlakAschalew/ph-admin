<script setup>
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { ref, watch } from 'vue';
import MainLayout from './MainLayout.vue';

const props = defineProps({
    admins: Array,
    filters: Object,
});

defineOptions({
    layout: MainLayout,
});

const localAdmins = ref(props.admins.data);

watch(
    () => props.admins,
    (newAdmins) => {
        localAdmins.value = newAdmins.data;
    },
);

const searchQuery = ref(props.filters.search);

const debouncedSearch = debounce((query) => {
    router.get(
        '/admin/admins',
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
const showAddAdminModal = ref(false);
const showConfirmModal = ref(false);
const adminToRemove = ref(null);
const editingAdmin = ref({
    id: null,
    first_name: '',
    last_name: '',
    phone: '',
    active: true,
    addedDate: '',
});
const newAdmin = ref({
    first_name: '',
    last_name: '',
    phone: '',
    password: '',
});

function editAdmin(admin) {
    editingAdmin.value = {
        id: admin.id,
        first_name: admin.first_name,
        last_name: admin.last_name,
        phone: admin.phone,
        role: admin.role,
        active: admin.status === 'Active',
        password: '',
        created_at: admin.created_at,
    };
    showEditModal.value = true;
}
function saveAdmin() {
    const index = localAdmins.value.findIndex(
        (a) => a.id === editingAdmin.value.id,
    );
    console.log(editingAdmin.value.phone);
    if (index !== -1) {
        router.put(`/admin/admins/${editingAdmin.value.id}`, {
            first_name: editingAdmin.value.first_name,
            last_name: editingAdmin.value.last_name,
            phone_number: editingAdmin.value.phone,
            password: editingAdmin.value.password,
            status: editingAdmin.value.active,
        });
    }
    showEditModal.value = false;
}

function confirmRemoveAdmin(id) {
    adminToRemove.value = id;
    showConfirmModal.value = true;
}

function removeAdmin() {
    router.delete(`/admin/admins/${adminToRemove.value}`, {
        preserveState: true,
        replace: true,
    });
    showConfirmModal.value = false;
    adminToRemove.value = null;
}

function addAdmin() {
    router.post('/admin/admins', {
        first_name: newAdmin.value.first_name,
        last_name: newAdmin.value.last_name,
        phone_number: newAdmin.value.phone_number,
        password: newAdmin.value.password,
    });

    newAdmin.value = {
        first_name: '',
        last_name: '',
        phone_number: '',
        password: '',
    };

    showAddAdminModal.value = false;
}

const showChangeSecretModal = ref(false);
const superadminSecret = ref('');

function changeSuperadminSecret() {
    router.put('/admin/superadmin-secret', {
        secret: superadminSecret.value,
    });
    superadminSecret.value = '';
    showChangeSecretModal.value = false;
}
</script>
<template>
    <Head title="Admins" />
    <!-- Main Content -->
    <main class="flex-1">
        <div class="mx-auto w-full max-w-6xl px-4 py-6">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">Current Admins</h2>
                <div class="flex items-center justify-between gap-x-2">
                    <button
                        @click="showChangeSecretModal = true"
                        class="inline-flex items-center gap-x-2 rounded-lg border border-transparent bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                    >
                        Change superadmin secret
                    </button>
                    <button
                        @click="showAddAdminModal = true"
                        class="inline-flex items-center gap-x-2 rounded-lg border border-transparent bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700"
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
                            <path d="M5 12h14"></path>
                            <path d="M12 5v14"></path>
                        </svg>
                        Add Admin
                    </button>
                </div>
            </div>

            <div class="mb-6">
                <input
                    type="text"
                    v-model="searchQuery"
                    class="block w-full rounded-lg border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Search admins"
                />
            </div>

            <div
                class="mt-6 overflow-hidden rounded-xl border bg-white shadow-sm"
            >
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                >
                                    Full name
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                >
                                    Phone number
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                >
                                    Role
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                >
                                    Status
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
                                v-for="admin in localAdmins"
                                :key="admin.id"
                                class="hover:bg-gray-100"
                            >
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            <div
                                                class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 font-semibold text-gray-500"
                                            >
                                                {{ admin.first_name.charAt(0)
                                                }}{{
                                                    admin.last_name.charAt(0)
                                                }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ admin.first_name }}
                                                {{ admin.last_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                Added on
                                                {{
                                                    new Date(
                                                        admin.created_at,
                                                    ).toLocaleDateString(
                                                        'en-GB',
                                                        {
                                                            day: '2-digit',
                                                            month: 'short',
                                                            year: 'numeric',
                                                        },
                                                    )
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        +251{{ admin.phone }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ admin.role }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                        :class="
                                            admin.status === 'Active'
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-gray-100 text-gray-800'
                                        "
                                    >
                                        {{ admin.status }}
                                    </span>
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 text-end"
                                >
                                    <div
                                        v-if="admin.role !== 'Super Admin'"
                                        class="flex justify-end gap-2"
                                    >
                                        <button
                                            @click="editAdmin(admin)"
                                            class="inline-flex items-center rounded-lg border border-gray-200 bg-white p-2 text-sm font-medium text-gray-500 hover:bg-gray-100"
                                            title="Edit Admin"
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
                                        </button>
                                        <button
                                            @click="
                                                confirmRemoveAdmin(admin.id)
                                            "
                                            class="inline-flex items-center rounded-lg border border-gray-200 bg-white p-2 text-sm font-medium text-red-500 hover:bg-red-50"
                                            title="Remove Admin"
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
                <template v-if="admins.links && admins.links.length">
                    <template
                        v-for="(link, index) in admins.links"
                        :key="index"
                    >
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            :class="[
                                index === 0 ? 'rounded-l-md' : '',
                                index === admins.links.length - 1
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
                                index === admins.links.length - 1
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

    <!-- Edit Admin Modal -->
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
                                Edit Admin
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label
                                        for="edit-name"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        First Name
                                    </label>
                                    <input
                                        type="text"
                                        id="edit-name"
                                        v-model="editingAdmin.first_name"
                                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="edit-lastName"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Last Name
                                    </label>
                                    <input
                                        type="text"
                                        id="edit-lastName"
                                        v-model="editingAdmin.last_name"
                                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="edit-phone"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Phone number
                                    </label>
                                    <input
                                        type="text"
                                        id="edit-phone"
                                        v-model="editingAdmin.phone"
                                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="edit-password"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        New password
                                    </label>
                                    <input
                                        type="password"
                                        id="edit-password"
                                        v-model="editingAdmin.password"
                                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                    />
                                </div>

                                <div class="flex items-center">
                                    <input
                                        type="checkbox"
                                        id="edit-active"
                                        v-model="editingAdmin.active"
                                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    />
                                    <label
                                        for="edit-active"
                                        class="ml-2 block text-sm text-gray-900"
                                    >
                                        Active
                                    </label>
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
                        @click="saveAdmin"
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

    <!-- Add Admin Modal -->
    <div
        v-if="showAddAdminModal"
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
                @click="showAddAdminModal = false"
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
                                Add New Admin
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label
                                        for="add-name"
                                        class="block text-sm font-medium text-gray-700"
                                        >First Name</label
                                    >
                                    <input
                                        type="text"
                                        id="add-name"
                                        v-model="newAdmin.first_name"
                                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="add-lastName"
                                        class="block text-sm font-medium text-gray-700"
                                        >Last Name</label
                                    >
                                    <input
                                        type="text"
                                        id="add-lastName"
                                        v-model="newAdmin.last_name"
                                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="add-phone"
                                        class="block text-sm font-medium text-gray-700"
                                        >Phone number</label
                                    >
                                    <input
                                        type="text"
                                        id="add-phone"
                                        v-model="newAdmin.phone_number"
                                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="add-password"
                                        class="block text-sm font-medium text-gray-700"
                                        >Password</label
                                    >
                                    <input
                                        type="password"
                                        id="add-password"
                                        v-model="newAdmin.password"
                                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                    />
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
                        @click="addAdmin"
                        class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Add Admin
                    </button>
                    <button
                        type="button"
                        @click="showAddAdminModal = false"
                        class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:mt-0 sm:w-auto sm:text-sm"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Superadmin Secret Modal -->
    <div
        v-if="showChangeSecretModal"
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
                @click="showChangeSecretModal = false"
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
                                Change Superadmin Secret
                            </h3>
                            <div class="mt-4">
                                <label
                                    for="superadmin-secret"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    New Secret
                                </label>
                                <input
                                    type="password"
                                    id="superadmin-secret"
                                    v-model="superadminSecret"
                                    class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
                >
                    <button
                        type="button"
                        @click="changeSuperadminSecret"
                        class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Save Secret
                    </button>
                    <button
                        type="button"
                        @click="showChangeSecretModal = false"
                        class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:mt-0 sm:w-auto sm:text-sm"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Change Superadmin Secret Modal -->

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
            <!-- Background overlay -->
            <div
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                @click="showConfirmModal = false"
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
                                Remove Admin
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to remove this admin?
                                    This action cannot be undone.
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
                        @click="removeAdmin"
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
