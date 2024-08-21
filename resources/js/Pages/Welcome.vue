<script setup>
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    parent_categories: Array,
    featured_products: Array
})

</script>

<template>

    <Head title="Welcome" />
    <div class="relative">
        <img :src="'/storage/images/site_images/food.jpg'" alt="e commerce online shop prodavnica"
            class="w-full h-96 opacity-75 object-cover">
        <div class="flex flex-col justify-around items-center absolute inset-0">

            <span class="text-white text-xl">Lorem ipsums's a dolar sit Them emet.</span>
            <div class="flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4">

                <button class="px-4 py-2 bg-gray-700 text-white border border-transparent rounded-md"
                    v-for="(category, index) in parent_categories" :key="index">{{ category.name }}</button>
            </div>
        </div>
    </div>
    <div class="text-center border-t border-b py-4">
        <p>Section 1!!!!!!!!!</p>
    </div>
    <div class="flex flex-col max-w-7x1 mx-auto px-4 sm:container sm:flex-row sm:space-x-4 sm:my-4 sm:px-6 lg:px-8">
        <div v-for="(product, index) in featured_products" :key="index" class="flex flex-col ">
            <Link href="#" class="w-full h-full gap-4">
                <div class="text-center text-white bg-gray-700 py-2">
                    <span>
                        {{ product.name }}
                    </span>
                </div>
                <img v-if="product.images.length > 0" :src="product.images[0].url" :alt="product.name"
                    class="h-72 object-cover md:w-72 lg:w-96">
                <img v-else :src="'/storage/images/defaults/default.jpg'" alt="" class="h-72 object-cover md:w-72 lg:w-96">
            </Link>
            <div class="w-full h-full mb-4 text-center text-white bg-gray-700 py-2">
                <span v-html="product.categories.length > 1 ? 'categories: ' : 'category: '"></span>
                <span v-for="(cat, index) in product.categories" :key="index">
                    {{ cat.name + ' ' }}
                </span>
            </div>
        </div>
    </div>
    <div class="text-center text-white bg-gray-700 px-4 py-4">
        <p class="contain">
            Section 2!!!!!!!!
        </p>
    </div>
</template>
