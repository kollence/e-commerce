<script setup>
import {loadStripe} from '@stripe/stripe-js';
import OrderSummary from '@/Components/OrderSummary/OrderSummary.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { useCartStore } from '@/Components/Cart/store';

const cartStore = useCartStore();
const props = defineProps({
    order_summary: Object,
})
// populate cart store with props
cartStore.orderSummary = props.order_summary;

const isSubmitting = ref(true);
const cardError = ref('');
const form = useForm({
    name: '',
    email: '',
    address: {
        country: '',
        city: '',
        street_and_number: '',
        zip: '',
        phone_1: '',
        phone_2: '',
    },
    billing_address: {
        country: '',
        city: '',
        street_and_number: '',
        zip: '',
        phone_1: '',
        phone_2: '',
    },
    notes: '',
    shipping_method: 'standard',
    payment_method: 'card',
    payment_method_id: null,
})

const stripe = ref({})
const activeTab = ref('address');
// stripe card element
const cardElement = ref(null);
const elements = ref({});

onMounted(() => {
    initStripe();
})

const initStripe = async () => {
    const key = import.meta.env.VITE_STRIPE_KEY;
    stripe.value = await loadStripe(key);
    elements.value = stripe.value.elements();
    cardElement.value = elements.value.create('card', {
        classes: {
            base: 'bg-gray-100 rounded-lg shadow-lg p-4 text-black',
        },
    });
    cardElement.value.mount('#card-element');
    cardElement.value.on('change', (event) => {
        isSubmitting.value = false    
        cardError.value = event.error ? event.error.message : '';
    })
}

const submitPayment = async () => {
    isSubmitting.value = false;

    const {paymentMethod, error} = await stripe.value.createPaymentMethod({
        type: 'card',
        card: cardElement.value,
        billing_details: {
            name: form.name,
            email: form.email,
            address: {
                city: form.address.city,
                country: form.address.country,
                line1: form.address.street_and_number,
                postal_code: form.address.zip,
            },
            phone: form.phone_1
        },
    })
    if(error) {
        cardError.value = error.message;
        return;
    }
    form.payment_method_id = paymentMethod.id;
    form.post(route('checkout.store'), {
        preserveScroll: true,
        onSuccess: () => {
            console.log('success');
            
            //redirect
            // window.location.href = route('checkout.success');
        },
        onError: (error) => {
            cardError.value = error.message;
        },
    })

}

</script>
<template>
<Head title="Checkout" />
<div class="min-h-screen py-6 flex flex-col justify-center sm:py-12"> 
    <div class="relative py-3 w-full max-w-7xl mx-auto  shadow-lg rounded-lg"> 
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6"> <!-- Left column: Form --> 
            <div class="flex flex-col"> 
                <form @submit.prevent="submitPayment" class="rounded-lg border border-green-700 p-4"> 
                    <h2 class="text-2xl font-semibold mb-4">Billing Details</h2> 
                    <div class="mb-4 text-slate-900 dark:text-white"> 
                        <label class="block text-slate-500 dark:text-slate-400 text-sm font-bold mb-2" for="name">Name</label> 
                        <input v-model="form.name" class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" placeholder="Full Name"> 
                    </div> 
                    <div class="mb-4 text-slate-900 dark:text-white"> 
                        <label class="block text-slate-500 dark:text-slate-400 text-sm font-bold mb-2" for="email">Email</label> 
                        <input  v-model="form.email" class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" placeholder="Email"> 
                    </div>
                    <div class="grid gap-4 grid-cols-2"> 
                        <button :class="{'border-t-2 rounded-tl-lg rounded-tr-lg border-lime-600': activeTab === 'address', 'text-slate-500': activeTab !== 'address'}" class="px-4 pt-2 focus:outline-none" type="button" @click="activeTab = 'address'">
                            Address
                        </button> 
                        <button :class="{'border-t-2 rounded-tl-lg rounded-tr-lg border-lime-600': activeTab === 'billing_address', 'text-slate-500': activeTab !== 'billing_address'}" class="px-4 pt-2 focus:outline-none" type="button" @click="activeTab = 'billing_address'">
                            Billing Address (optional)
                        </button> 
                    </div> 
                    <div v-if="activeTab === 'address'" class="pt-3"> 
                        <h3 class="text-sm font-semibold mb-2">Address:</h3> 
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="country">Country</label>
                            <input v-model="form.address.country" class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="country" type="text" placeholder="Country"> 
                        </div> 
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="city">City</label> 
                            <input v-model="form.address.city" class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="city" type="text" placeholder="City"> 
                        </div> 
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="street_and_number">Street and Number</label>
                            <input v-model="form.address.street_and_number" class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="street_and_number" type="text" placeholder="Street and Number"> 
                        </div> 
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="zip_code">ZIP Code</label> 
                            <input v-model="form.address.zip_code" class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="zip_code" type="text" placeholder="ZIP Code"> 
                        </div> 
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="phone_1">Phone 1</label> 
                            <input v-model="form.address.phone_1" class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="phone_1" type="text" placeholder="Phone 1"> 
                        </div> 
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="phone_2">Phone 2 (optional)</label> 
                            <input v-model="form.address.phone_2" class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="phone_2" type="text" placeholder="Phone 2"> 
                        </div>    
                    </div> 
                    <div v-if="activeTab === 'billing_address'"  class="pt-3"> 
                        <h3 class="text-sm font-semibold mb-2">Billing Address (optional):</h3> 
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="billing_country">Country</label> 
                            <input v-model="form.billing_address.country" class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="billing_country" type="text" placeholder="Country"> 
                        </div> 
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="billing_city">City</label> 
                            <input v-model="form.billing_address.city" class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="billing_city" type="text" placeholder="City">
                        </div> 
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="billing_street_and_number">Street and Number</label> 
                            <input v-model="form.billing_address.street_and_number" class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="billing_street_and_number" type="text" placeholder="Street and Number">
                        </div>
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="billing_zip_code">ZIP Code</label> 
                            <input v-model="form.billing_address.zip_code" class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="billing_zip_code" type="text" placeholder="ZIP Code">
                        </div> 
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="billing_phone_1">Phone 1</label> 
                            <input v-model="form.billing_address.phone_1" class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="billing_phone_1" type="text" placeholder="Phone 1">
                        </div> 
                        <div class="mb-4"> 
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="billing_phone_2">Phone 2 (optional)</label> 
                            <input v-model="form.billing_address.phone_2" class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="billing_phone_2" type="text" placeholder="Phone 2">
                        </div>
                    </div>
                    <div class="mb-4 text-slate-900 dark:text-white">
                        <label class="block text-slate-500 dark:text-slate-400 text-sm font-bold mb-2" for="notes">Notes: (optional)</label>
                        <textarea class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline" id="notes" type="text" placeholder="Add additional information. For example, entrance code or special instructions (flor, room number, etc)"></textarea>
                    </div>
                    <div class="mb-4 text-slate-900 dark:text-white">
                        <label for="shipping_method" class="block text-slate-500 dark:text-slate-400 text-sm font-bold mb-2">Shipping Method</label>
                        <select v-model="form.shipping_method" id="shipping_method" class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="standard">Standard Shipping</option>
                            <option value="express">Express Shipping</option>
                        </select>
                    </div>
                    <div class="mb-4 text-slate-900 dark:text-white">
                        <label for="payment_method" class="block text-slate-500 dark:text-slate-400 text-sm font-bold mb-2">Payment Method</label>
                        <select v-model="form.payment_method"  id="payment_method" class="shadow  dark:bg-gray-800 appearance-none border rounded w-full py-2 px-3 text-slate-500 dark:text-slate-400 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="card">Credit Card</option>
                            <option value="paypal">PayPal</option>
                            <option value="bitcoin">Bitcoin</option>
                        </select>
                    </div>
                    <div class="mb-4 text-slate-900 dark:text-white">
                        <div id="card-element"></div>
                        <div id="card-error" class="text-red-500 text-center text-sm mt-2" role="alert">
                            {{cardError}}
                        </div>
                    </div>
                    <button :disabled="isSubmitting" :class="{'cursor-not-allowed': isSubmitting}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">Submit</button>
                 </form> 
            </div> 
                <!-- Right column: Order Summary --> 
            <div class="flex flex-col "> 
                <OrderSummary />
            </div> 
        </div> 
    </div> 
</div>
</template>


<style lang="scss" scoped></style>