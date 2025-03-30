<script setup>
import { ref } from 'vue';
import MainLayout from './MainLayout.vue';
defineOptions({
    layout: MainLayout,
});

const showEditModal = ref(false);
const showAddAdminModal = ref(false);
const showConfirmModal = ref(false);
const adminToRemove = ref(null);
const editingAdmin = ref({
    id: null,
    name: '',
    lastName: '',
    email: '',
    role: '',
    active: true,
    addedDate: '',
});
const newAdmin = ref({
    name: '',
    lastName: '',
    email: '',
    role: 'Admin',
    active: true,
});
const admins = ref([
    {
        id: 1,
        name: 'Abebe',
        lastName: 'Kebede',
        email: 'abebe.kebede@example.com',
        role: 'Super Admin',
        active: true,
        addedDate: '12 Jan 2023',
    },
    {
        id: 2,
        name: 'Tigist',
        lastName: 'Haile',
        email: 'tigist.haile@example.com',
        role: 'Admin',
        active: true,
        addedDate: '03 Mar 2023',
    },
    {
        id: 3,
        name: 'Dawit',
        lastName: 'Tadesse',
        email: 'dawit.tadesse@example.com',
        role: 'Moderator',
        active: true,
        addedDate: '15 Apr 2023',
    },
    {
        id: 4,
        name: 'Hiwot',
        lastName: 'Girma',
        email: 'hiwot.girma@example.com',
        role: 'Admin',
        active: false,
        addedDate: '22 Jun 2023',
    },
    {
        id: 5,
        name: 'Yonas',
        lastName: 'Bekele',
        email: 'yonas.bekele@example.com',
        role: 'Moderator',
        active: true,
        addedDate: '10 Aug 2023',
    },
]);
const pendingAdmins = ref([
    {
        id: 101,
        name: 'Meron',
        lastName: 'Alemu',
        email: 'meron.alemu@example.com',
        requestedRole: 'Admin',
        requestDate: '05 May 2024',
    },
    {
        id: 102,
        name: 'Bereket',
        lastName: 'Tesfaye',
        email: 'bereket.tesfaye@example.com',
        requestedRole: 'Moderator',
        requestDate: '12 May 2024',
    },
    {
        id: 103,
        name: 'Selam',
        lastName: 'Negash',
        email: 'selam.negash@example.com',
        requestedRole: 'Admin',
        requestDate: '18 May 2024',
    },
]);

function editAdmin(admin) {
    editingAdmin.value = { ...admin };
    showEditModal.value = true;
}

function saveAdmin() {
    const index = admins.value.findIndex((a) => a.id === editingAdmin.value.id);
    if (index !== -1) {
        admins.value[index] = { ...editingAdmin.value };
    }
    showEditModal.value = false;
}

function confirmRemoveAdmin(id) {
    adminToRemove.value = id;
    showConfirmModal.value = true;
}

function removeAdmin() {
    admins.value = admins.value.filter(
        (admin) => admin.id !== adminToRemove.value,
    );
    showConfirmModal.value = false;
    adminToRemove.value = null;
}

function addAdmin() {
    const newId = Math.max(...admins.value.map((a) => a.id)) + 1;
    const today = new Date();
    const formattedDate = today.toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });

    admins.value.push({
        id: newId,
        name: newAdmin.value.name,
        lastName: newAdmin.value.lastName,
        email: newAdmin.value.email,
        role: newAdmin.value.role,
        active: newAdmin.value.active,
        addedDate: formattedDate,
    });

    // Reset form
    newAdmin.value = {
        name: '',
        lastName: '',
        email: '',
        role: 'Admin',
        active: true,
    };

    showAddAdminModal.value = false;
}

function approveAdmin(id) {
    const pendingAdmin = pendingAdmins.value.find((a) => a.id === id);
    if (pendingAdmin) {
        const newId = Math.max(...admins.value.map((a) => a.id)) + 1;
        const today = new Date();
        const formattedDate = today.toLocaleDateString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        });

        admins.value.push({
            id: newId,
            name: pendingAdmin.name,
            lastName: pendingAdmin.lastName,
            email: pendingAdmin.email,
            role: pendingAdmin.requestedRole,
            active: true,
            addedDate: formattedDate,
        });

        pendingAdmins.value = pendingAdmins.value.filter((a) => a.id !== id);
    }
}

function rejectAdmin(id) {
    pendingAdmins.value = pendingAdmins.value.filter((a) => a.id !== id);
}
</script>
<template>
    <Head title="Admins" />
    <!-- Main Content -->
    <main class="flex-1">
        <div class="mx-auto w-full px-2 py-6 sm:px-4">
            <div class="flex flex-col gap-6 2xl:flex-row">
                <!-- Current Admins List -->
                <div class="w-full 2xl:w-1/2">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-800">
                            Current Admins
                        </h2>
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

                    <div class="mb-4">
                        <input
                            type="text"
                            class="block w-full rounded-lg border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Search admins"
                        />
                    </div>

                    <div class="rounded-xl border bg-white shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                        >
                                            Name
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                        >
                                            Email
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
                                        v-for="admin in admins"
                                        :key="admin.id"
                                        class="hover:bg-gray-100"
                                    >
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="flex items-center">
                                                <div
                                                    class="h-10 w-10 flex-shrink-0"
                                                >
                                                    <div
                                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 font-semibold text-gray-500"
                                                    >
                                                        {{ admin.name.charAt(0)
                                                        }}{{
                                                            admin.lastName.charAt(
                                                                0,
                                                            )
                                                        }}
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div
                                                        class="text-sm font-medium text-gray-900"
                                                    >
                                                        {{ admin.name }}
                                                        {{ admin.lastName }}
                                                    </div>
                                                    <div
                                                        class="text-sm text-gray-500"
                                                    >
                                                        Added on
                                                        {{ admin.addedDate }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                {{ admin.email }}
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
                                                    admin.active
                                                        ? 'bg-green-100 text-green-800'
                                                        : 'bg-gray-100 text-gray-800'
                                                "
                                            >
                                                {{
                                                    admin.active
                                                        ? 'Active'
                                                        : 'Inactive'
                                                }}
                                            </span>
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-end"
                                        >
                                            <div class="flex justify-end gap-2">
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
                                                        <path
                                                            d="m15 5 4 4"
                                                        ></path>
                                                    </svg>
                                                </button>
                                                <button
                                                    @click="
                                                        confirmRemoveAdmin(
                                                            admin.id,
                                                        )
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
                                                        <path
                                                            d="M3 6h18"
                                                        ></path>
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
                </div>

                <!-- Pending Admins List -->
                <div class="mt-8 w-full 2xl:mt-0 2xl:w-1/2">
                    <h2 class="mb-4 text-xl font-bold text-gray-800">
                        Pending Admin Requests
                    </h2>
                    <div class="mb-4">
                        <input
                            type="text"
                            class="block w-full rounded-lg border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Search pending requests"
                        />
                    </div>
                    <div class="rounded-xl border bg-white shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                        >
                                            Name
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                        >
                                            Email
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                        >
                                            Requested Role
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-start text-xs font-medium uppercase text-gray-500"
                                        >
                                            Date
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
                                    <tr v-if="pendingAdmins.length === 0">
                                        <td
                                            colspan="5"
                                            class="px-6 py-8 text-center text-gray-500"
                                        >
                                            No pending admin requests
                                        </td>
                                    </tr>
                                    <tr
                                        v-for="admin in pendingAdmins"
                                        :key="admin.id"
                                        class="hover:bg-gray-100"
                                    >
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="flex items-center">
                                                <div
                                                    class="h-10 w-10 flex-shrink-0"
                                                >
                                                    <div
                                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 font-semibold text-gray-500"
                                                    >
                                                        {{ admin.name.charAt(0)
                                                        }}{{
                                                            admin.lastName.charAt(
                                                                0,
                                                            )
                                                        }}
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div
                                                        class="text-sm font-medium text-gray-900"
                                                    >
                                                        {{ admin.name }}
                                                        {{ admin.lastName }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                {{ admin.email }}
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                {{ admin.requestedRole }}
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                {{ admin.requestDate }}
                                            </div>
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-end"
                                        >
                                            <div class="flex justify-end gap-2">
                                                <button
                                                    @click="
                                                        approveAdmin(admin.id)
                                                    "
                                                    class="inline-flex items-center gap-x-1 rounded-lg border border-transparent bg-green-500 px-3 py-1 text-sm font-medium text-white hover:bg-green-600"
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
                                                        <polyline
                                                            points="20 6 9 17 4 12"
                                                        ></polyline>
                                                    </svg>
                                                    Approve
                                                </button>
                                                <button
                                                    @click="
                                                        rejectAdmin(admin.id)
                                                    "
                                                    class="inline-flex items-center gap-x-1 rounded-lg border border-gray-200 bg-white px-3 py-1 text-sm font-medium text-red-500 hover:bg-red-50"
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
                                                            d="M18 6 6 18"
                                                        ></path>
                                                        <path
                                                            d="m6 6 12 12"
                                                        ></path>
                                                    </svg>
                                                    Reject
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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
                                        >First Name</label
                                    >
                                    <input
                                        type="text"
                                        id="edit-name"
                                        v-model="editingAdmin.name"
                                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="edit-lastName"
                                        class="block text-sm font-medium text-gray-700"
                                        >Last Name</label
                                    >
                                    <input
                                        type="text"
                                        id="edit-lastName"
                                        v-model="editingAdmin.lastName"
                                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="edit-email"
                                        class="block text-sm font-medium text-gray-700"
                                        >Email</label
                                    >
                                    <input
                                        type="email"
                                        id="edit-email"
                                        v-model="editingAdmin.email"
                                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="edit-role"
                                        class="block text-sm font-medium text-gray-700"
                                        >Role</label
                                    >
                                    <select
                                        id="edit-role"
                                        v-model="editingAdmin.role"
                                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                    >
                                        <option value="Super Admin">
                                            Super Admin
                                        </option>
                                        <option value="Admin">Admin</option>
                                        <option value="Moderator">
                                            Moderator
                                        </option>
                                    </select>
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
                                        v-model="newAdmin.name"
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
                                        v-model="newAdmin.lastName"
                                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="add-email"
                                        class="block text-sm font-medium text-gray-700"
                                        >Email</label
                                    >
                                    <input
                                        type="email"
                                        id="add-email"
                                        v-model="newAdmin.email"
                                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="add-role"
                                        class="block text-sm font-medium text-gray-700"
                                        >Role</label
                                    >
                                    <select
                                        id="add-role"
                                        v-model="newAdmin.role"
                                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                    >
                                        <option value="Super Admin">
                                            Super Admin
                                        </option>
                                        <option value="Admin">Admin</option>
                                        <option value="Moderator">
                                            Moderator
                                        </option>
                                    </select>
                                </div>
                                <div class="flex items-center">
                                    <input
                                        type="checkbox"
                                        id="add-active"
                                        v-model="newAdmin.active"
                                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    />
                                    <label
                                        for="add-active"
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
