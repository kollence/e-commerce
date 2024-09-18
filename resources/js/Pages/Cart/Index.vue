<script setup>
import NavigationHeader from '@/Components/NavigationHeader.vue';
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

const page = usePage();
const showPopup = ref(false);
let timeoutID = undefined
defineProps({
    items: Object,
})
onMounted(() => {
  if (page.props.flash.message) {
    showPopup.value = true;
    timeoutID = setTimeout(() => {
      showPopup.value = false;
    }, 3000);
  }
})

onBeforeUnmount(() => {
  clearTimeout(timeoutID);
});

function closePopup() {
  showPopup.value = false;
}

const form = useForm({
    product_item_id: null,
    size_option: null,
})
function removeFromCart(item_id, size_option) {
    form.product_item_id = item_id;
    form.size_option = size_option;
    form.post(route('cart.remove'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            clearTimeout(timeoutID);
        },
        onFinish: () => {
              if (page.props.flash.message) {
                showPopup.value = true;
                timeoutID = setTimeout(() => {
                showPopup.value = false;
                }, 3000);
            }
        }
    });
}
const quantity = ref(1);
function decrementQuantity(item) {
    //   if (item.quantity > 1) {
    //     item.quantity--;
    //   }
}
function incrementQuantity(item) {
    //   item.quantity++;
}
</script>

<template>

    <Head title="Cart" />
    <NavigationHeader>
        <template #breadcrumbs>
            <span class="mx-2">/</span>
            <Link :href="route('shop.index')">Shop</Link>
            <span class="mx-2">/</span>
            <span>Cart</span>
        </template>
        <template #search>
            <input type="search" class="w-full bg-gray-200 rounded-lg py-2 px-4 focus:outline-none focus:bg-white"
                placeholder="Search for products">
        </template>
    </NavigationHeader>
    <div class="container mx-auto px-4 py-8">
            <div v-if="showPopup" class="fixed top-20 right-5 z-10 border-red-500 border-2 bg-white rounded-lg shadow-md">
                <div class="flex bg-white rounded-lg shadow-md p-4 items-center justify-between">
                <p class="px-1 text-green-500">{{ page.props.flash.message }}</p>
                <button class="px-3 text-gray-500 hover:text-gray-700 text-xl font-bold ml-auto" @click="closePopup">
                    &times;
                </button>
                </div>
            </div>
        <h1 class="text-3xl font-bold text-center mb-8">Your Shopping Cart</h1>
            <div v-if="Object.keys(items).length > 0" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-9 text-center">
                    <table class="table table-auto w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Product</th>
                                <th class="px-4 py-2">Image</th>
                                <th class="px-4 py-2">Color</th>
                                <th class="px-4 py-2">Size</th>
                                <th class="px-4 py-2">Quantity</th>
                                <th class="px-4 py-2">Price</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in items" :key="item.id">
                                
                                <td class="px-4 py-2 text-center">{{ item.name }}</td>
                                <td class="px-4 py-2 flex justify-center">
                                    <img v-if="item.images.length > 0" :src="item.images[0].url" :alt="item.name" class="w-24 h-24 object-cover">
                                    <img v-else :src="'/storage/images/defaults/default.jpg'" alt=""  class="w-24 h-24 object-cover">
                                </td>

                                <td class="px-4 py-2 text-center">
                                    <div class="flex justify-center items-center">
                                        <div class="bg-red-300 rounded-full text-stone-400 h-10 w-10" :style="{'background-color': item.color.hex}"></div>

                                    </div>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <div class="p-2 bg-stone-200 border-red-300 border-1" >{{ item.size_option.name }}</div>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex text-black items-center justify-center">
                                        <button class="border-l border-gray-700 border-y px-3 bg-stone-200 rounded-l text-2xl" @click="decrementQuantity">-</button>
                                        <input :value="item.quantity" type="number" min="1" max="599" class="appearance-none-arrow border rounded-none px-4 py-1 w-17 text-center">
                                        <button class="border-r border-gray-700 border-y px-3 bg-stone-200 rounded-r text-2xl" @click="incrementQuantity">+</button>
                                    </div>
                                </td>
                                <td v-if="item.sale_price < item.original_price && item.sale_price > 0" class="px-4 py-2 text-center text-red-600 font-bold">
                                    <div class="grid item-center">
                                        <div class="text-red-600 font-bold text-md">On Sale!</div>
                                        <div class="text-red-500">{{ currencyFormat(item.sale_price) }}</div>
                                    </div>
                                </td>
                                <td v-else class="px-4 py-2 text-center">
                                    <div class="grid item-center">
                                        <div class="text-black font-bold text-lg">{{ currencyFormat(item.original_price) }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <button @click="removeFromCart(item.product_item_id, item.size_option)" type="button" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                                        </svg>
                                        <span class="sr-only">Remove</span>
                                    </button>
                                    <!-- <button type="button" class="text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
                                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M5 3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7.414A2 2 0 0 0 20.414 6L18 3.586A2 2 0 0 0 16.586 3H5Zm10 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7V5h8v2a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
                                        </svg>

                                        <span class="sr-only">Save</span>
                                    </button> -->
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <div class="md:col-span-3">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-medium mb-4">Order Summary</h2>
                    <div class="flex justify-between mb-2">
                    <span>Subtotal:</span>
                    <span>$99</span>
                    </div>
                    <div class="flex justify-between mb-2">
                    <span>Tax:</span>
                    <span>$99</span>
                    </div>
                    <div class="flex justify-between mb-4">
                    <span>Total:</span>
                    <span>$99</span>
                    </div>
                    <button class="btn btn-primary w-full">Checkout</button>
                </div>
                </div>
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