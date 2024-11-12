<script setup>
import {loadStripe} from '@stripe/stripe-js';
import OrderSummary from '@/Components/Cart/OrderSummary.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    order_summary: Object,
})
// console.log(props.order_summary);
const activeTab = ref('address');
</script>
<template>
<Head title="Checkout" />
<div class="min-h-screen py-6 flex flex-col justify-center sm:py-12"> 
    <div class="relative py-3 w-full max-w-7xl mx-auto  shadow-lg rounded-lg"> 
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6"> <!-- Left column: Form --> 
            <div class="flex flex-col"> 
                <form class="rounded-lg border border-green-700 p-4"> 
                    <h2 class="text-2xl font-semibold mb-4">Billing Details</h2> 
                    <div class="mb-4 text-slate-900 dark:text-white"> 
                        <label class="block text-slate-500 dark:text-slate-400 text-sm font-bold mb-2" for="name">Name</label> 
                        <input class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" placeholder="Full Name"> 
                    </div> 
                    <div class="mb-4 text-slate-900 dark:text-white"> 
                        <label class="block text-slate-500 dark:text-slate-400 text-sm font-bold mb-2" for="email">Email</label> 
                        <input class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" placeholder="Email"> 
                    </div> 
                    <div class="flex"> 
                        <button :class="{'border-b-2 border-lime-600': activeTab === 'address'}" class="px-4 py-2 focus:outline-none" type="button" @click="activeTab = 'address'">
                            Address
                        </button> 
                        <button :class="{'border-b-2 border-lime-600': activeTab === 'billing_address'}" class="px-4 py-2 focus:outline-none" type="button" @click="activeTab = 'billing_address'">
                            Billing Address (optional)
                        </button> 
                    </div> 
                    <div v-if="activeTab === 'address'"> 
                        <h3 class="text-xl font-semibold mb-2">Address</h3> 
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="country">Country</label>
                            <input class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="country" type="text" placeholder="Country"> 
                        </div> 
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="city">City</label> 
                            <input class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="city" type="text" placeholder="City"> 
                        </div> 
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="street_and_number">Street and Number</label>
                            <input class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="street_and_number" type="text" placeholder="Street and Number"> 
                        </div> 
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="zip_code">ZIP Code</label> 
                            <input class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="zip_code" type="text" placeholder="ZIP Code"> 
                        </div> 
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="phone_1">Phone 1</label> 
                            <input class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="phone_1" type="text" placeholder="Phone 1"> 
                        </div> 
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="phone_2">Phone 2</label> 
                            <input class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="phone_2" type="text" placeholder="Phone 2"> 
                        </div>    
                    </div> 
                    <div v-if="activeTab === 'billing_address'"> 
                        <h3 class="text-xl font-semibold mb-2">Billing Address (optional)</h3> 
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="billing_country">Country</label> 
                            <input class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="billing_country" type="text" placeholder="Country"> 
                        </div> 
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="billing_city">City</label> 
                            <input class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="billing_city" type="text" placeholder="City"> 
                        </div> 
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="billing_street_and_number">Street and Number</label> 
                            <input class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="billing_street_and_number" type="text" placeholder="Street and Number"> 
                        </div>
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="billing_zip_code">ZIP Code</label> 
                            <input class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="billing_zip_code" type="text" placeholder="ZIP Code">
                        </div> 
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="billing_phone_1">Phone 1</label> 
                            <input class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="billing_phone_1" type="text" placeholder="Phone 1"> 
                        </div> 
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="billing_phone_2">Phone 2</label> 
                            <input class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="billing_phone_2" type="text" placeholder="Phone 2"> 
                        </div>
                    </div>
                    <div class="mb-4 text-slate-900 dark:text-white">
                        <label class="block text-slate-500 dark:text-slate-400 text-sm font-bold mb-2" for="notes">Notes: (optional)</label>
                        <textarea class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="notes" type="text" placeholder="Add additional information. For example, entrance code or special instructions (flor, room number, etc)"></textarea>
                    </div>
                    <div class="mb-4 text-slate-900 dark:text-white">
                        <label for="shipping_method" class="block text-slate-500 dark:text-slate-400 text-sm font-bold mb-2">Shipping Method</label>
                        <select id="shipping_method" class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="standard">Standard Shipping</option>
                            <option value="express">Express Shipping</option>
                        </select>
                    </div>
                    <div class="mb-4 text-slate-900 dark:text-white">
                        <label for="payment_method" class="block text-slate-500 dark:text-slate-400 text-sm font-bold mb-2">Payment Method</label>
                        <select id="payment_method" class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="credit_card">Credit Card</option>
                            <option value="paypal">PayPal</option>
                            <option value="bitcoin">Bitcoin</option>
                        </select>
                    </div>
                    

                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button"> Submit </button>
                 </form> 
            </div> 
                <!-- Right column: Order Summary --> 
            <div class="flex flex-col "> 
                <OrderSummary
                    :cartSubtotal="order_summary.cart_subtotal"
                    :cartTax="order_summary.cart_tax"
                    :taxRate="order_summary.tax_rate"
                    :cartTotal="order_summary.new_total"
                    :couponCode="order_summary.coupon.code"
                    :couponDiscount="order_summary.coupon.discount"
                />
            </div> 
        </div> 
    </div> 
</div>


                <!-- <div class="flex-1"> -->
                    Order Summary
                    <!--  -->
                <!-- </div> -->
</template>


<style lang="scss" scoped></style>