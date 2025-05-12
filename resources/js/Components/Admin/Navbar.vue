<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch, watchEffect } from 'vue';

const mobileMenuOpen = ref(false);

const logout = () => {
    let logout = useForm();
    logout.post('/admin/logout');
};

const user = usePage().props.auth.user;

watch(
    () => usePage().props.auth.user,
    (newUser) => {
        Object.assign(user, newUser);
    },
);

const currentPage = computed(() => usePage().component);

watchEffect(currentPage);

const dynamicNavClass = (page) =>
    currentPage.value === `Admin/${page}`
        ? 'font-bold text-white'
        : 'font-medium text-gray-300 hover:text-white';

const showEditModal = ref(false);
const editForm = useForm({
    first_name: user.first_name || '',
    last_name: user.last_name || '',
    phone_number: user.phone || '',
    password: '',
    password_confirmation: '',
});

const openEditModal = () => {
    showEditModal.value = true;
    editForm.password = '';
    editForm.password_confirmation = '';
};

const closeEditModal = () => {
    showEditModal.value = false;
    editForm.clearErrors();
};

const submitEdit = () => {
    console.log('HIT IT');
    editForm.put(`/admin/profile`, {
        onSuccess: () => closeEditModal(),
    });
};
</script>

<template>
    <!-- Preline UI Navbar -->
    <header
        class="z-50 flex w-full flex-wrap bg-blue-900 py-4 text-sm sm:flex-nowrap sm:justify-start"
    >
        <nav
            class="mx-auto w-full max-w-full px-4 sm:flex sm:items-center sm:justify-between"
            aria-label="Global"
        >
            <div class="flex items-center justify-between">
                <Link
                    class="flex-none text-xl font-bold text-white"
                    href="/admin"
                >
                    <img
                        src="/logo.png"
                        class="mr-2 inline-block h-10 w-10"
                    />PH Admin</Link
                >
                <div class="sm:hidden">
                    <button
                        type="button"
                        class="inline-flex items-center justify-center gap-2 rounded-md border border-gray-700 p-2 text-gray-200 transition-all hover:bg-blue-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 focus:ring-offset-blue-800"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                    >
                        <svg
                            v-if="!mobileMenuOpen"
                            class="h-4 w-4"
                            width="16"
                            height="16"
                            fill="currentColor"
                            viewBox="0 0 16 16"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"
                            />
                        </svg>
                        <svg
                            v-else
                            class="h-4 w-4"
                            width="16"
                            height="16"
                            fill="currentColor"
                            viewBox="0 0 16 16"
                        >
                            <path
                                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"
                            />
                        </svg>
                    </button>
                </div>
            </div>
            <div
                :class="{ hidden: !mobileMenuOpen }"
                class="grow basis-full overflow-hidden transition-all duration-300 sm:block"
            >
                <div
                    class="mt-5 flex flex-col gap-5 sm:mt-0 sm:flex-row sm:items-center sm:justify-end sm:pl-5"
                >
                    <Link :class="dynamicNavClass('Dashboard')" href="/admin/"
                        >Home</Link
                    >
                    <Link
                        :class="dynamicNavClass('Suppliers')"
                        href="/admin/suppliers"
                        >Suppliers</Link
                    >
                    <Link
                        :class="dynamicNavClass('Products')"
                        href="/admin/products"
                        >Products</Link
                    >

                    <Link
                        v-if="$page.props.auth.user_role === 'superadmin'"
                        :class="dynamicNavClass('Admins')"
                        href="/admin/admins"
                        >Admins</Link
                    >

                    <div
                        v-if="$page.props.auth.user_role === 'superadmin'"
                        class="hs-dropdown relative inline-flex"
                    >
                        <button
                            id="hs-dropdown-default"
                            type="button"
                            class="hs-dropdown-toggle focus:outline-hidden flex w-full items-center font-medium text-gray-300 hover:text-white"
                            aria-haspopup="menu"
                            aria-expanded="false"
                            aria-label="Dropdown"
                        >
                            Pending
                            <svg
                                class="size-4 hs-dropdown-open:rotate-180"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>

                        <div
                            class="hs-dropdown-menu duration z-50 mt-2 hidden min-w-60 rounded-lg bg-white opacity-0 shadow-md transition-[opacity,margin] before:absolute before:-top-4 before:start-0 before:h-4 before:w-full after:absolute after:-bottom-4 after:start-0 after:h-4 after:w-full hs-dropdown-open:opacity-100"
                            role="menu"
                            aria-orientation="vertical"
                            aria-labelledby="hs-dropdown-default"
                        >
                            <div class="space-y-0.5 p-1">
                                <Link
                                    class="focus:outline-hidden flex items-center gap-x-3.5 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100"
                                    href="/admin/pending-consumers"
                                >
                                    Pending Consumers
                                </Link>
                                <Link
                                    class="focus:outline-hidden flex items-center gap-x-3.5 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100"
                                    href="/admin/pending-suppliers"
                                >
                                    Pending Suppliers
                                </Link>
                            </div>
                        </div>
                    </div>
                    <!-- Dropdown -->

                    <div
                        v-if="$page.props.auth.user_role === 'superadmin'"
                        class="hs-dropdown relative inline-flex"
                    >
                        <button
                            id="hs-dropdown-default"
                            type="button"
                            class="hs-dropdown-toggle focus:outline-hidden flex w-full items-center font-medium text-gray-300 hover:text-white"
                            aria-haspopup="menu"
                            aria-expanded="false"
                            aria-label="Dropdown"
                        >
                            Users
                            <svg
                                class="size-4 hs-dropdown-open:rotate-180"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>

                        <div
                            class="hs-dropdown-menu duration z-50 mt-2 hidden min-w-60 rounded-lg bg-white opacity-0 shadow-md transition-[opacity,margin] before:absolute before:-top-4 before:start-0 before:h-4 before:w-full after:absolute after:-bottom-4 after:start-0 after:h-4 after:w-full hs-dropdown-open:opacity-100"
                            role="menu"
                            aria-orientation="vertical"
                            aria-labelledby="hs-dropdown-default"
                        >
                            <div class="space-y-0.5 p-1">
                                <Link
                                    class="focus:outline-hidden flex items-center gap-x-3.5 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100"
                                    href="/admin/consumers"
                                >
                                    Consumers
                                </Link>
                                <Link
                                    class="focus:outline-hidden flex items-center gap-x-3.5 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100"
                                    href="/admin/suppliers"
                                >
                                    Suppliers
                                </Link>
                            </div>
                        </div>
                    </div>
                    <!-- Dropdown -->
                    <div
                        class="hs-dropdown relative inline-flex [--placement:bottom-center]"
                    >
                        <button
                            id="hs-dropdown-account"
                            type="button"
                            class="size-9.5 focus:outline-hidden inline-flex items-center justify-center gap-x-2 rounded-full border border-transparent text-sm font-semibold text-gray-800 disabled:pointer-events-none disabled:opacity-50 dark:text-white"
                            aria-haspopup="menu"
                            aria-expanded="false"
                            aria-label="Dropdown"
                        >
                            <span
                                class="inline-flex size-8 items-center justify-center rounded-full bg-red-500 font-semibold text-white"
                            >
                                {{ user.first_name[0] }}{{ user.last_name[0] }}
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
                                <p class="text-sm font-medium text-gray-800">
                                    {{ user.first_name }} +251{{ user.phone }}
                                </p>
                            </div>
                            <div class="space-y-0.5 p-1.5">
                                <a
                                    class="focus:outline-hidden flex items-center gap-x-3.5 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100"
                                    @click="openEditModal"
                                >
                                    Edit profile
                                </a>
                                <form @submit.prevent="logout">
                                    <button
                                        class="focus:outline-hidden flex w-full items-center gap-x-3.5 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100"
                                        href="#"
                                    >
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Dropdown -->
                </div>
            </div>
        </nav>
    </header>

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
                @click="closeEditModal"
            ></div>
            <div
                class="inline-block w-full max-w-md transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle"
            >
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <h3
                        class="text-lg font-medium leading-6 text-gray-900"
                        id="modal-title"
                    >
                        Edit Profile
                    </h3>
                    <form class="mt-4 space-y-4" @submit.prevent="submitEdit">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >First Name</label
                                >
                                <input
                                    type="text"
                                    v-model="editForm.first_name"
                                    class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                />
                                <p
                                    v-if="editForm.errors.first_name"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ editForm.errors.first_name }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Last Name</label
                                >
                                <input
                                    type="text"
                                    v-model="editForm.last_name"
                                    class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                />
                                <p
                                    v-if="editForm.errors.last_name"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ editForm.errors.last_name }}
                                </p>
                            </div>
                            <div class="sm:col-span-2">
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Phone Number</label
                                >
                                <input
                                    type="text"
                                    v-model="editForm.phone_number"
                                    class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                />
                                <p
                                    v-if="editForm.errors.phone_number"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ editForm.errors.phone_number }}
                                </p>
                            </div>
                            <div class="sm:col-span-2">
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >New Password</label
                                >
                                <input
                                    type="password"
                                    v-model="editForm.password"
                                    class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                    placeholder="Leave blank to keep current password"
                                />
                                <p
                                    v-if="editForm.errors.password"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ editForm.errors.password }}
                                </p>
                            </div>
                            <div class="sm:col-span-2">
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Confirm Password</label
                                >
                                <input
                                    type="password"
                                    v-model="editForm.password_confirmation"
                                    class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                    placeholder="Leave blank to keep current password"
                                />
                                <p
                                    v-if="editForm.errors.password_confirmation"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ editForm.errors.password_confirmation }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end gap-2">
                            <button
                                type="button"
                                @click="closeEditModal"
                                class="rounded-md bg-gray-200 px-4 py-2 text-sm text-gray-800"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="rounded-md bg-blue-600 px-4 py-2 text-sm text-white"
                                :disabled="editForm.processing"
                            >
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
