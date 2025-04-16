import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useConsumerStore = defineStore('consumer', () => {
    // Search
    const searchQuery = ref('');

    // Cart
    const cartCount = ref(0);
    const cartItems = ref({
        products: [],
        customProducts: [],
    });

    // Cart methods
    function addToCart(product) {
        cartCount.value++;
        cartItems.value.products.push(product);
    }
    function addCustomProductToCart(product) {
        cartCount.value++;
        cartItems.value.customProducts.push(product);
    }
    function deleteProduct(id) {
        cartItems.value.products = cartItems.value.products.filter(item => item.id !== id);
        cartCount.value = Math.max(0, cartCount.value - 1);
    }
    function deleteCustomProduct(id) {
        cartItems.value.customProducts = cartItems.value.customProducts.filter(item => item.id !== id);
        cartCount.value = Math.max(0, cartCount.value - 1);
    }
    function clearCart() {
        cartItems.value.products = [];
        cartItems.value.customProducts = [];
        cartCount.value = 0;
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
