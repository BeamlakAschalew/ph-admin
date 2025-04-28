import { defineStore } from 'pinia';
import { ref } from 'vue';

/* eslint-disable indent */
export const useConsumerStore = defineStore('consumer', () => {
    // Search
    const searchQuery = ref('');

    // Cart
    const cartCount = ref(0);
    const cartItems = ref({
        products: [],
        customProducts: [],
    });

    // Load cart from localStorage
    const savedCart = localStorage.getItem('consumerCart');
    if (savedCart) {
        const { cartCount: savedCount, cartItems: savedItems } =
            JSON.parse(savedCart);
        cartCount.value = savedCount;
        cartItems.value = savedItems;
    }

    // Persist cart to localStorage
    function saveCart() {
        localStorage.setItem(
            'consumerCart',
            JSON.stringify({
                cartCount: cartCount.value,
                cartItems: cartItems.value,
            }),
        );
    }

    // Cart methods
    function addToCart(product) {
        cartCount.value++;
        cartItems.value.products.push(product);
        saveCart();
    }
    function addCustomProductToCart(product) {
        cartCount.value++;
        cartItems.value.customProducts.push(product);
        saveCart();
    }
    function deleteProduct(pid) {
        cartItems.value.products = cartItems.value.products.filter(
            (item) => item.pid !== pid,
        );
        cartCount.value = Math.max(0, cartCount.value - 1);
        saveCart();
    }
    function deleteCustomProduct(id) {
        cartItems.value.customProducts = cartItems.value.customProducts.filter(
            (item) => item.id !== id,
        );
        cartCount.value = Math.max(0, cartCount.value - 1);
        saveCart();
    }
    function clearCart() {
        cartItems.value.products = [];
        cartItems.value.customProducts = [];
        cartCount.value = 0;
        saveCart();
    }

    return {
        searchQuery,
        cartCount,
        cartItems,
        addToCart,
        addCustomProductToCart,
        deleteProduct,
        deleteCustomProduct,
        clearCart,
    };
});
/* eslint-enable indent */
