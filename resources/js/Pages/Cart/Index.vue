<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { cloneDeep, isEqual } from 'lodash'; // Import lodash for deep comparison
import Breadcrumbs from '@/Components/LayoutPartials/Breadcrumbs.vue';
import breadcrumbs from '@/Components/LayoutPartials/store/breadcrumbs';
import OrderSummary from '@/Components/Cart/OrderSummary.vue';

const props = defineProps({
    cart_items: Object,
    order_summary: Object,
})
                    // use Lodash isEqual to compare objects
const cartItems = ref(cloneDeep(props.cart_items));

const cartTableWrapper = ref(null); // ref for cart table wrapper

const couponCode = computed(() => props.order_summary.coupon.code)
const discountWithCoupon = computed(() => props.order_summary.coupon.discount ?? 0)

// set dynamic data based on couponCode if exist. Load coupon discounts or load default values
const orderSummary = ref({
    cart_subtotal: props.order_summary.cart_subtotal,
    cart_tax: props.order_summary.cart_tax,
    tax_rate: props.order_summary.tax_rate,
    new_total: props.order_summary.new_total,
});
// Detect mouse leave outside the cart table and then send request to update cart items
const handleMouseOutside = () => {
    // If the mouseleave compare if object got changed and if it is then submit cart items
    if (!isEqual(cartItems.value, props.cart_items)) {
       submitCartItems();
    }
};

const form = useForm({
    cart_items: null,
})
const formRemoveKey = useForm({
    cart_item_key: null,
})
// if coupon code exist in session then set orderSummary to coupon values NEEDED FOR REHYDRATING DATA (didn't want rehydrate without watch :(  )
watch(couponCode, (newCode) => {
        orderSummary.value.cart_subtotal = props.order_summary.cart_subtotal; 
        orderSummary.value.cart_tax = props.order_summary.cart_tax; 
        orderSummary.value.new_total = props.order_summary.new_total;
})
// don't give values with discount to go in negative numbers when its FREE!
const validateNumericValues = (obj) => {
    Object.keys(obj).forEach(key => { 
        if (typeof obj[key] === 'number' && obj[key] < 0) { 
            obj[key] = 0;
        }
    }); 
};
// Validate numeric values in orderSummary object (don't let go values go under 0)
watch(orderSummary, (newVal) => { validateNumericValues(newVal); }, { deep: true });

function removeFromCart(key) {
    formRemoveKey.cart_item_key = key;

    // Delete operator to delete object property by key
    delete cartItems.value[key]
    // Recalculate totals
    orderSummary.value.cart_subtotal = couponCode ?  Number((Object.values(cartItems.value).reduce((total, item) => total + item.subtotal, 0)  - Number(discountWithCoupon.value)).toFixed(2)) : Number(Object.values(cartItems.value).reduce((total, item) => total + item.subtotal, 0).toFixed(2));
    const taxRate = Number(props.order_summary.tax_rate);
    orderSummary.value.cart_tax = Number((orderSummary.value.cart_subtotal * (taxRate / 100)).toFixed(2));
    const new_total_format = orderSummary.value.cart_subtotal + orderSummary.value.cart_tax;
    orderSummary.value.new_total = Number(new_total_format.toFixed(2));
    
    formRemoveKey.post(route('cart.remove'), {
        preserveScroll: true,
    });
}

function decrementQuantity(key) {
    const item = cartItems.value[key];
    if (item.product_item.quantity > 1) {
        --item.product_item.quantity;
        updateQuantity(key); // Update after decrement
    }else{
        item.product_item.quantity = 1
    }
}
function incrementQuantity(key) { 
    const item = cartItems.value[key];
    if (item.product_item.quantity < 599) {
        ++item.product_item.quantity;
        updateQuantity(key); // Update after decrement
    }else{
        item.product_item.quantity = 599
    }
}
// Update quantity in the local cartItems object
const updateQuantity = (key) => {
    const item = cartItems.value[key];
    // Ensure tax_rate is a number
    const taxRate = Number(props.order_summary.tax_rate);
    // Optional: update the subtotal locally
    const subtotal_format = ((item.product_item.sale_price < item.product_item.original_price && item.product_item.sale_price > 0) ? item.product_item.sale_price : item.product_item.original_price) * item.product_item.quantity;
    item.subtotal = Number(subtotal_format.toFixed(2));
    const cart_total_subtotal = Object.values(cartItems.value).reduce((total, item) => total + item.subtotal, 0);
    const cart_subtotal_format = couponCode ? Number((cart_total_subtotal - discountWithCoupon.value).toFixed(2)) : cart_total_subtotal;
    orderSummary.value.cart_subtotal = Number(cart_subtotal_format.toFixed(2));
    orderSummary.value.cart_tax = Number(( cart_subtotal_format  * (taxRate / 100) ).toFixed(2));

    const new_total_format = orderSummary.value.cart_subtotal + orderSummary.value.cart_tax;
    orderSummary.value.new_total = Number(new_total_format.toFixed(2));
};

const submitCartItems = () => {
        // Extract cart item properties and create a new object with the desired properties
        const extractCartItemProperties = Object.fromEntries(Object.entries(cartItems.value).map(([key, value]) => [key, { product_item_id: value.product_item.product_item_id, quantity: value.product_item.quantity, subtotal: value.subtotal, size_option_id: value.product_item.size_option.id}]));
        // console.log(extractCartItemProperties);
        form.cart_items = extractCartItemProperties
        form.post(route('cart.update'), {
            preserveScroll: true,
            onSuccess: () => {},
        });
}
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
                            <tr v-for="(item, key) in cartItems" :key="key">
                                
                                <td class="px-4 py-2 text-center">
                                    <Link :href="`${route('shop.show', [item.product.slug, item.product_item.product_item_id])}?size_option=${item.product_item.size_option.slug}`" class="text-blue-500 hover:underline">
                                        {{ item.product.name }}
                                    </Link>
                                </td>
                                <td class="px-4 py-2 flex justify-center">
                                    <img v-if="item.product_item.images.length > 0" :src="item.product_item.images[0].url" :alt="item.product.name" class="w-24 h-24 object-cover">
                                    <img v-else :src="'/storage/images/defaults/default.jpg'" alt=""  class="w-24 h-24 object-cover">
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex justify-center items-center">
                                        <div class="bg-red-300 rounded-full text-stone-400 h-10 w-10" :style="{'background-color': item.product_item.color.hex}"></div>
                                    </div>
                                </td>
                                <td class="p-2 text-center">
                                    <div class="p-2 bg-stone-200 border-red-300 rounded-xl border-1 text-stone-600" >{{ item.product_item.size_option.name }}</div>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <div  class="flex items-center justify-center">
                                        <button class="decrement-button border-l border-gray-700 border-y px-3 bg-stone-200 rounded-l text-2xl text-stone-600" @click="decrementQuantity(key)">-</button>
                                        <input v-model="item.product_item.quantity" @change="updateQuantity(key)" type="number" min="1" max="599" class="appearance-none-arrow border rounded-none px-4 py-1 w-17 text-center text-stone-600">
                                        <button class="increment-button border-r border-gray-700 border-y px-3 bg-stone-200 rounded-r text-2xl text-stone-600" @click="incrementQuantity(key)">+</button>
                                    </div>
                                </td>
                                <td v-if="item.product_item.sale_price < item.product_item.original_price && item.product_item.sale_price > 0" class="px-4 py-2 text-center text-red-600 font-bold">
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
                                    <button @click="removeFromCart(key)" type="button" class="remove-button text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                                        </svg>
                                        <span class="sr-only">Remove</span>
                                    </button>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <OrderSummary
                    :cartSubtotal="orderSummary.cart_subtotal"
                    :cartTax="orderSummary.cart_tax"
                    :taxRate="orderSummary.tax_rate"
                    :cartTotal="orderSummary.new_total"
                    :couponCode="couponCode"
                    :couponDiscount="discountWithCoupon"
                >
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
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0; 
}
</style>