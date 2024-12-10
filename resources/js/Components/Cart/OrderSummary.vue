<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from 'vue';
import { useCartStore } from './store';
import { toRef } from '@vue/reactivity';
import { storeToRefs } from 'pinia';


const page = usePage();

const cartStore = useCartStore();

const cartCounter = computed(() => page.props.cart_count)


const applyCoupon = () => {
    cartStore.applyCoupon()
}
const removeCoupon = () => {
    cartStore.removeCoupon()
}
</script>

<template>
    <div class="md:col-span-3">
        <div class="rounded-lg border border-green-700 shadow-md">
            <template v-if="cartStore.couponCode">
                <div class="flex justify-between items-center bg-lime-700 rounded-t-lg p-2">
                    <span>Coupon Code Discount:</span>
                    <button @click="removeCoupon()" type="button" class="border-2 border-lime-400 remove-button text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-small rounded-full text-xs p-1 text-center inline-flex items-center me-1 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                        <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                        </svg>
                        <span class="sr-only">Remove</span>
                    </button>
                </div>
                <div class="mb-2 rounded-lg border-b border-grey-300">
                    <div class="flex justify-between  p-2">
                        <span>Coupon Code:</span>
                        <span>{{ cartStore.couponCode }}</span>
                    </div>
                    <div class="flex justify-between  p-2">
                        <span>Discount:</span>
                        <span>- {{ currencyFormat(cartStore.orderSummary.coupon.discount) }}</span>
                    </div>
                </div>
            </template>
            <div class=" px-2 py-4">
                <h2 class="text-2xl font-semibold mb-4 ml-2"
                    v-html="cartStore.couponCode ? 'Order Summary With Discount' : 'Order Summary'">
                </h2>
                <div class="flex justify-between mb-2 px-2">
                    <span>Subtotal of {{ cartCounter }} items:</span>
                    <span>{{ currencyFormat(cartStore.orderSummary.cart_subtotal) }}</span>
                </div>
                <div class="flex justify-between mb-2 px-2">
                    <span>Tax:</span>
                    <span>{{ cartStore.orderSummary.tax_rate * 100 }}%</span>
                </div>
                <div class="flex justify-between mb-2 px-2">
                    <span>Cart Tax:</span>
                    <span>{{ currencyFormat( cartStore.orderSummary.cart_tax) }}</span>
                </div>
                <div class="flex justify-between mb-4 px-2">
                    <span>Total:</span>
                    <span>{{ currencyFormat(cartStore.orderSummary.new_total) }}</span>
                </div>
                <slot name="redirect-link"></slot>
            </div>
        </div>
        <div v-if="!cartStore.couponCode" class="flex flex-col item-center justify-center shadow-md rounded-lg border border-green-700 mt-3 py-2">
            <span class="mx-auto">Coupon code: {{cartStore.couponCode}}</span>
            <form class="mx-auto flex flex-col" @submit.prevent="applyCoupon">
                <input type="text" v-model="cartStore.formCoupon.coupon_code" class="text-black bg-green-100 border border-green-200 rounded-lg p-2" placeholder="Enter coupon code">
                <span v-if="page.props.errors.error" class="text-red-500 text-sm text-center">{{ page.props.errors.error[0] }}</span>
                <button class="border border-lime-600 rounded-md px-5 py-2 bg-lime-500 text-black font-semibold mt-2">Apply</button>
            </form>
        </div>
    </div>
</template>

<style lang="scss" scoped></style>