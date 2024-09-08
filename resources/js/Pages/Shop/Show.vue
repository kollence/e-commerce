<script setup>
import NavigationHeader from '@/Components/NavigationHeader.vue';
import NavCategories from '@/Components/Shop/NavCategories.vue';
import { Link, Head } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';

defineProps({
    product: Object,
});



</script>

<template>

    <Head :title="product.name" />
    <NavigationHeader>
        <template #breadcrumbs>
            <span class="mx-2">/</span>
            <Link :href="route('shop.index')">Shop</Link>
            <span class="mx-2">/</span>
            <span>{{ product.name }}</span>
        </template>
    </NavigationHeader>

    <div class="flex flex-col lg:flex-row">

        <div class="container mx-auto px-4 py-8 justify-end">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 mb-5">
                <div class="md:col-span-7">
                    <img :src="product.images[0].url" :alt="product.name" class="object-cover rounded-lg">
                    
                </div>
                <div class="md:col-span-4">
                    <h1 class="text-3xl font-bold mb-4">{{ product.name }}</h1>
                    <p class="text-lg mb-2">{{ product.description }}</p>
                    <p class="text-gray-500 mb-4">{{ product.care_instructions }}</p>
                    <div class="flex items-center mb-4">
                        <span class="text-gray-700 mr-2">About:</span>
                        <p>{{ product.about }}</p>
                    </div>
                    <div class="flex items-center mb-4">
                        <span class="text-gray-700 mr-2">Is Active:</span>
                        <span :class="product.is_active ? 'text-green-500' : 'text-red-500'">{{ product.is_active ?
                            'Yes'
                            :
                            'No' }}</span>
                    </div>
                    <div class="flex items-center mb-4">
                        <span class="text-gray-700 mr-2">Is Featured:</span>
                        <span :class="product.is_featured ? 'text-green-500' : 'text-red-500'">{{ product.is_featured ?
                            'Yes'
                            : 'No' }}</span>
                    </div>
                    <div class="flex items-center mb-4">
                        <span class="text-gray-700 mr-2">Brand:</span>
                        <p>{{ product.name }}</p>
                    </div>
                    <span>
                        {{ currencyFormat(product.lowest_priced_item.original_price) }}
                    </span>
                    <span
                        v-if="product.lowest_priced_item && product.lowest_priced_item.sale_price < product.lowest_priced_item.original_price"
                        class="text-red-500 font-bold">
                        ON SAIL! {{ currencyFormat(product.lowest_priced_item.sale_price) }}

                    </span>
                    <span v-else class="line-through text-dark-500">
                        Not on sail
                    </span>
                </div>
            </div>
            <h2 class="text-xl font-semibold m-2 mt-5 mt-3">Product Variants</h2>
            <div
                class="grid grid-cols-1 md:grid-cols-3  gap-4 border-2 border gradient-border border-red-900 p-4 rounded-lg mt-5  ">

                <!-- product item variants -->
                <div v-for="variant in product.product_items" :key="variant.product_code"
                    class="card rounded-lg p-4 shadow-md align-self-end relative bg-gradient-to-r from-cyan-300 via-teal-500 to-sky-700">
                    <div class="flex flex-row justify-between items-center mb-2">
                        <div class="md:col-span-3">
                        <div class="border-2 absolute left-1 top-2 rounded-full" :key="variant.product_code">
                            <span class="w-half h-full rounded-full p-2 inline-block" :style="{backgroundColor: variant.color.hex}">{{variant.color.name}}</span>
                        </div>
                    </div>
                    <div class="md:col-span-4">
                        <img :src="product.images[0].url" :alt="variant.product_code" class="w-40 h-40 object-cover rounded-lg mb-2">
                        
                    </div>
                    </div>
                    
                    

                    <div class="md:col">
                        <h2 class="text-lg text-green-900 font-bold mb-1">{{ variant.is_active ? 'Active' : 'Not Active'}}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">{{ variant.product_code }}</div>
                        <h2 class="text-xl font-semibold mb-2">Product Name: {{ product.name}}</h2>
                        <p class="text-dark-500 mb-4">{{ variant.description }}</p>
                        <div class="flex items-center justify-between mb-4 border-t border-b">
                            <span class="text-black-700 mr-2 font-bold">Price: {{ currencyFormat(variant.original_price) }}</span>
                            <span class="text-lime-200  font-bold">{{ variant.sale_price ? "On sale: " + currencyFormat(variant.sale_price)  : '' }}</span>
                        </div>
                        
                        <div class="flex justify-around">
                            <button class="text-black hover:text-white bg-gradient-to-r from-teal-200 via-teal-500 to-teal-800 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-teal-500 dark:focus:ring-teal-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Add to Favorites</button>
                            <button class="text-black hover:text-white bg-gradient-to-r from-teal-400 via-teal-500 to-teal-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-teal-700 dark:focus:ring-teal-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Add to Cart</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<style></style>