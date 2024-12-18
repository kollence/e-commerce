<script setup>
import { Link } from '@inertiajs/vue3';
import { useCartStore } from './store';
import { watch } from 'vue';

const cartStore = useCartStore();

const handleMouseOutside = () => {
    cartStore.handleMouseLeave()
}
const updateQuantity = (id, qty) => {
    cartStore.updateQuantity(id, qty)
}

</script>

<template>
    <div ref="cartTableWrapper" class="md:col-span-9 text-center"  @mouseleave="handleMouseOutside"> 
        <table class="table table-auto w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2">Product</th>
                    <th class="px-4 py-2">Image</th>
                    <th class="px-4 py-2">Color</th>
                    <th class="px-4 py-2">Size</th>
                    <th class="px-4 py-2">Quantity</th>
                    <th class="px-4 py-2">Price</th>
                    <th class="px-4 py-2">Subtotal</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, key) in cartStore.cartItems" :key="key">

                    <td class="px-4 py-2 text-center">
                        <Link
                            :href="`${route('shop.show', [item.product.slug, item.product_item.product_item_id])}?size_option=${item.product_item.size_option.slug}`"
                            class="text-blue-500 hover:underline">
                        {{ item.product.name }}
                        </Link>
                    </td>
                    <td class="px-4 py-2 flex justify-center">
                        <img v-if="item.product_item.images.length > 0" :src="item.product_item.images[0].url"
                            :alt="item.product.name" class="w-24 h-24 object-cover">
                        <img v-else :src="'/storage/images/defaults/default.jpg'" alt="" class="w-24 h-24 object-cover">
                    </td>
                    <td class="px-4 py-2 text-center">
                        <div class="flex justify-center items-center">
                            <div class="bg-red-300 rounded-full text-stone-400 h-10 w-10"
                                :style="{ 'background-color': item.product_item.color.hex }"></div>
                        </div>
                    </td>
                    <td class="p-2 text-center">
                        <div class="p-2 bg-stone-200 border-red-300 rounded-xl border-1 text-stone-600">{{
                            item.product_item.size_option.name }}</div>
                    </td>
                    <td class="px-4 py-2 text-center">
                        <div class="flex items-center justify-center">
                            <button
                                class="decrement-button border-l border-gray-700 border-y px-3 bg-stone-200 rounded-l text-2xl text-stone-600"
                                @click="cartStore.decrementQuantity(key)">-</button>
                            <input v-model="item.product_item.quantity" @change="updateQuantity(key, item.product_item.quantity)" type="number" min="1"
                                max="599"
                                class="appearance-none-arrow border rounded-none px-4 py-1 w-17 text-center text-stone-600">
                            <button
                                class="increment-button border-r border-gray-700 border-y px-3 bg-stone-200 rounded-r text-2xl text-stone-600"
                                @click="cartStore.incrementQuantity(key)">+</button>
                        </div>
                    </td>
                    <td v-if="item.product_item.sale_price < item.product_item.original_price && item.product_item.sale_price > 0"
                        class="px-4 py-2 text-center text-red-600 font-bold">
                        <div class="grid item-center">
                            <div class="text-red-600 font-bold text-md">On Sale!</div>
                            <div class="text-red-500">{{ currencyFormat(item.product_item.sale_price) }}</div>
                        </div>
                    </td>
                    <td v-else class="px-4 py-2 text-center">
                        <div class="grid item-center">
                            <div class="font-bold text-lg">{{ currencyFormat(item.product_item.original_price) }}</div>
                        </div>
                    </td>
                    <td class="px-4 py-2 text-center">
                        <div class="grid item-center">
                            <div class="font-bold text-lg">{{ currencyFormat(item.subtotal) }}</div>
                        </div>
                    </td>
                    <td class="px-4 py-2 text-center">
                        <button @click="cartStore.removeFromCart(key)" type="button"
                            class="remove-button text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18 17.94 6M18 18 6.06 6" />
                            </svg>
                            <span class="sr-only">Remove</span>
                        </button>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</template>

<style scoped>
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0; 
}
</style>