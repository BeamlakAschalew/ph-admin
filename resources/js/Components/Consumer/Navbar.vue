<script setup>
import { useConsumerStore } from '@/stores/consumerStore';
import { useForm, usePage } from '@inertiajs/vue3';
import {
    MenuIcon,
    PillIcon,
    SearchIcon,
    ShoppingCartIcon,
    XIcon,
} from 'lucide-vue-next';
import { ref, watch } from 'vue';

defineProps({ subcities: Array });

const consumerStore = useConsumerStore();
const isMenuOpen = ref(false);

const user = usePage().props.auth.user;

watch(
    () => usePage().props.auth.user,
    (newUser) => {
        Object.assign(user, newUser);
    },
);

const emit = defineEmits(['goToCheckout']);
const emitGoToCheckout = () => emit('goToCheckout');

const logout = () => {
    let logout = useForm();
    logout.post('/logout');
};

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};

const showEditModal = ref(false);
const editForm = useForm({
    first_name: user.first_name || '',
    last_name: user.last_name || '',
    institution_name: user.institution_name || '',
    woreda: user.woreda || '',
    subcity_id: user.subcity_id || '',
    special_place: user.special_place || '',
    license_number: user.license_number || '',
    primary_phone: user.primary_phone || '',
    secondary_phone: user.secondary_phone || '',
    password: '',
    password_confirmation: '',
});

const openEditModal = () => {
    showEditModal.value = true;
    // Reset password fields
    editForm.password = '';
    editForm.password_confirmation = '';
};

const closeEditModal = () => {
    showEditModal.value = false;
    editForm.clearErrors();
};

const submitEdit = () => {
    editForm.put('/profile', {
        onSuccess: () => {
            closeEditModal();
        },
    });
};
</script>

<template>
    <header
        class="sticky top-0 z-50 flex w-full flex-wrap border-b border-gray-200 bg-white text-sm sm:flex-nowrap sm:justify-start"
    >
        <nav
            class="relative mx-auto w-full max-w-[85rem] px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8"
            aria-label="Global"
        >
            <div class="mr-5 flex items-center justify-between">
                <Link
                    class="flex-none py-4 text-xl font-semibold text-blue-600"
                    href="/"
                    aria-label="PH"
                >
                    <PillIcon class="mr-2 inline-block h-8 w-8" />
                    PH
                </Link>
                <div class="sm:hidden">
                    <button
                        type="button"
                        class="inline-flex items-center justify-center gap-2 rounded-md border border-gray-200 p-2 text-sm text-gray-600 transition-all hover:bg-gray-100 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
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
                            v-model="consumerStore.searchQuery"
                            type="search"
                            class="block w-full rounded-full border border-gray-300 bg-gray-50 p-2.5 ps-10 text-sm text-gray-900 transition-all duration-300 focus:border-blue-600 focus:ring-blue-600"
                            placeholder="Search medications..."
                        />
                    </div>

                    <button
                        class="mb-6 inline-flex items-center gap-x-2 rounded-full border border-transparent bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition-all duration-300 hover:bg-blue-700 disabled:pointer-events-none disabled:opacity-50 sm:mb-0"
                        @click="emitGoToCheckout"
                    >
                        <ShoppingCartIcon class="h-4 w-4" />
                        Checkout ({{ consumerStore.cartCount }})
                    </button>

                    <Link
                        href="/past-orders"
                        class="mb-6 inline-flex items-center gap-x-2 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-blue-600 transition-all duration-300 hover:bg-blue-50 hover:text-blue-800 sm:mb-0"
                    >
                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <rect x="3" y="4" width="18" height="16" rx="2" />
                            <path d="M16 2v4M8 2v4M3 10h18" />
                        </svg>
                        Past orders
                    </Link>

                    <Link
                        href="/supplier"
                        class="mb-6 inline-flex items-center gap-x-2 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-blue-600 transition-all duration-300 hover:bg-blue-50 hover:text-blue-800 sm:mb-0"
                    >
                        Go to supplier
                    </Link>

                    <div
                        class="hs-dropdown relative inline-flex [--placement:bottom-right]"
                    >
                        <button
                            id="hs-dropdown-account"
                            type="button"
                            class="focus:outline-hidden inline-flex h-10 w-10 items-center justify-center gap-x-2 rounded-full border border-transparent text-sm font-semibold text-gray-800 disabled:pointer-events-none disabled:opacity-50 dark:text-white"
                            aria-haspopup="menu"
                            aria-expanded="false"
                            aria-label="Dropdown"
                        >
                            <span
                                class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-red-500 font-semibold text-white"
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
                                    {{ user.first_name }} +251{{
                                        user.primary_phone
                                    }}
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
                                        href="#"
                                        class="focus:outline-hidden flex w-full items-center gap-x-3.5 rounded-lg px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 focus:bg-gray-100"
                                    >
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Profile Dropdown -->
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
                                    >Institution Name</label
                                >
                                <input
                                    type="text"
                                    v-model="editForm.institution_name"
                                    class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                />
                                <p
                                    v-if="editForm.errors.institution_name"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ editForm.errors.institution_name }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Woreda</label
                                >
                                <input
                                    type="number"
                                    min="1"
                                    max="14"
                                    v-model="editForm.woreda"
                                    class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                />
                                <p
                                    v-if="editForm.errors.woreda"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ editForm.errors.woreda }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Subcity</label
                                >
                                <select
                                    v-model="editForm.subcity_id"
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
                                <p
                                    v-if="editForm.errors.subcity_id"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ editForm.errors.subcity_id }}
                                </p>
                            </div>
                            <div class="sm:col-span-2">
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Special Place</label
                                >
                                <input
                                    type="text"
                                    v-model="editForm.special_place"
                                    class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                />
                                <p
                                    v-if="editForm.errors.special_place"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ editForm.errors.special_place }}
                                </p>
                            </div>
                            <div class="sm:col-span-2">
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >License Number</label
                                >
                                <input
                                    type="text"
                                    v-model="editForm.license_number"
                                    class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                />
                                <p
                                    v-if="editForm.errors.license_number"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ editForm.errors.license_number }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Primary Phone</label
                                >
                                <input
                                    type="text"
                                    v-model="editForm.primary_phone"
                                    class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                />
                                <p
                                    v-if="editForm.errors.primary_phone"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ editForm.errors.primary_phone }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Secondary Phone</label
                                >
                                <input
                                    type="text"
                                    v-model="editForm.secondary_phone"
                                    class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm"
                                />
                                <p
                                    v-if="editForm.errors.secondary_phone"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ editForm.errors.secondary_phone }}
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
