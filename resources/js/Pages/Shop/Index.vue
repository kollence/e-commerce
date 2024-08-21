<script setup>
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
    <div class="flex">
        <div class="border-r w-1/5">
            <div>
                <div class="text-black text-center bg-gray-200 py-4">
                    <p>Categories:</p>
                </div>
                <div class="flex flex-col divide-y">
                    <Link href="#" v-for="(category, index) in categories" :key="index" class="py-2 px-4 hover:bg-gray-200">
                    {{ category.name }}
                    </Link>
                </div>
            </div>
        </div>
        <div class="border-l w-4/5">
            <div class="container flex flex-wrap mx-auto">
                <Link href="#" class="flex flex-col w-full p-4 rounded sm:w-1/2 md:w-1/3" v-for="(product, index) in products" :key="index">
                    <img v-if="product.images.length > 0" :src="product.images[0].url" :alt="product.name" class="h-72 object-cover md:w-72 lg:w-96">
                    <img v-else :src="'/storage/images/defaults/default.jpg'" alt="" class="h-72 object-cover md:w-72 lg:w-96">
                    <div class="flex justify-around bg-gray-200 md:w-72 lg:w-96 py-2">
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