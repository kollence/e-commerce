<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { Head, Link,  } from '@inertiajs/vue3';
import Breadcrumbs from '@/Components/LayoutPartials/Breadcrumbs.vue';
import breadcrumbs from '@/Components/LayoutPartials/store/breadcrumbs';
import OrderSummary from '@/Components/Cart/OrderSummary.vue';
import CartItems from '@/Components/Cart/CartItems.vue';
import { useCartStore } from '@/Components/Cart/store';

const cartStore = useCartStore();

const props = defineProps({
    cart_items: Object,
    order_summary: Object,
})
// Initialize store state with props 
cartStore.cartItems = props.cart_items;
cartStore.orderSummary = props.order_summary;

</script>

<template>
    <Head title="Cart" />
    <Breadcrumbs :breadcrumbs="breadcrumbs.crumbs">
        <template #breadcrumbs>
            <Link :href="route('shop.index')">Shop</Link>
            <span class="mx-2">/</span>
        </template>
        <template #breadcrumbs-end>
            <span class="mx-2">/</span>
            <span>Cart</span>
        </template>
        <template #search>
            <input type="text" class="w-full  bg-green-100 rounded-lg py-2 px-4 focus:outline-none focus:bg-white"
                placeholder="Search for products">
        </template>
    </Breadcrumbs>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8">Your Shopping Cart</h1>
        <div v-if="Object.keys(cart_items).length > 0" class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <!-- Cart items -->
            <CartItems />
            <!-- Order summary -->
            <OrderSummary>
                <template  #redirect-link>
                    <div class="flex justify-between mb-4 px-2">
                        <Link :href="route('checkout.index')" class="border border-lime-600 rounded-md px-5 py-2 bg-lime-500 text-black text-center font-semibold mx-auto  mt-2 w-full">Checkout</Link>
                    </div>
                </template>   
            </OrderSummary>
        </div>
        <div v-else class="pt-5 text-center">Cart is empty.</div>
    </div>
</template>

<style scoped>

</style>