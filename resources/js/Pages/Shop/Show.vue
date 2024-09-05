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
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-9">
                    <img :src="product.images[0].url" :alt="product.name" class="object-cover rounded-lg">
                    <span>
                        {{ currencyFormat(product.lowest_sale_price_product_item.original_price) }}
                    </span>
                    <span v-if="product.lowest_sale_price_product_item" class="text-red-500 font-bold">
                        ON SAIL! {{ currencyFormat(product.lowest_sale_price_product_item.sale_price) }}

                    </span>
                    <span v-else class="line-through text-gray-500">
                        Not on sail
                    </span>
                </div>
                <div class="md:col-span-3">
                    <h1 class="text-3xl font-bold mb-4">{{ product.name }}</h1>
                    <p class="text-lg mb-2">{{ product.description }}</p>
                    <p class="text-gray-500 mb-4">{{ product.care_instructions }}</p>
                    <div class="flex items-center mb-4">
                        <span class="text-gray-700 mr-2">About:</span>
                        <p>{{ product.about }}</p>
                    </div>
                    <div class="flex items-center mb-4">
                        <span class="text-gray-700 mr-2">Is Active:</span>
                        <span :class="product.is_active ? 'text-green-500' : 'text-red-500'">{{ product.is_active ? 'Yes'
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
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border border-red-900 p-4 rounded-lg mt-4">
                PRODUCT ITEMS CARDS
                <!-- product item variants -->
            </div>
        </div>
    </div>
</template>

<style></style>