<script setup>
import NavCategories from '@/Components/Shop/NavCategories.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    products: Object,
    categories: Object,

})


</script>

<template>
    <header class="text-black bg-gray-300 shadow">
        <div class="flex justify-between items-center max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center leading-tight">
                <Link :href="route('welcome')" class="text-black transition hover:text-yellow-700">
                Home
                </Link>
            </div>
            <div class="w-1/2">
                Section
            </div>
        </div>
    </header>
    <div class="flex flex-col lg:flex-row">
        <NavCategories :categories="categories" />

        <div class="border-l w-4/5 mx-auto">
            <div class="container flex flex-wrap">
                <Link href="#" class="flex flex-col w-full p-4 rounded sm:w-1/2 md:w-1/3  lg:w-1/4"
                    v-for="(product, index) in products" :key="index">
                <img v-if="product.images.length > 0" :src="product.images[0].url" :alt="product.name"
                    class="h-72 object-cover md:w-72 lg:w-96">
                <img v-else :src="'/storage/images/defaults/default.jpg'" alt=""
                    class="h-72 object-cover md:w-72 lg:w-96">
                <div class="flex justify-around bg-gray-200 py-2">
                    <span>
                        {{ product.name }}
                    </span>
                    <span>
                        123 din
                    </span>
                </div>
                </Link>
            </div>
        </div>
    </div>
</template>

<style></style>