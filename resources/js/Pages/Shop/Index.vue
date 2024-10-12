<script setup>
import Breadcrumbs from '@/Components/LayoutPartials/Breadcrumbs.vue';
import NavCategories from '@/Components/Shop/NavCategories.vue';
import { Link, Head } from '@inertiajs/vue3';
import { onBeforeUnmount, onMounted } from 'vue';
import breadcrumbsStore from '@/Components/LayoutPartials/store/breadcrumbs.js'
const props = defineProps({
    products: Object,
    categories: Object,
    selectedCategory: String,
    breadcrumbs: Object,
});
// NEED FIX!! only clear previous breadcrumbs (category history) so they don't pile up just in Cart/Index output
onBeforeUnmount(() => {
    breadcrumbsStore.removeCrumbs()
    breadcrumbsStore.addCrumbs(props.breadcrumbs)  
})
</script>

<template>
    <Head :title="selectedCategory" />
    <Breadcrumbs :breadcrumbs="breadcrumbs">
        <template #breadcrumbs>
            <Link :href="route('shop.index')">Shop</Link>
            <span class="mx-2">/</span>
        </template>
        <template #search>
            <input type="text" class="w-full bg-gray-200 rounded-lg py-2 px-4 focus:outline-none focus:bg-white"
                placeholder="Search for products">
        </template>
    </Breadcrumbs>

    <div class="flex flex-col lg:flex-row">
        <NavCategories :categories="categories" />

        <div class="border-l w-4/5 mx-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 sm:gap-2 md:gap-4 lg:gap-5 sm:p-0 md:p-4">
                <Link :href="route('shop.show', [product.slug, product.product_item.color.slug])" class="card rounded-lg p-2 shadow-md" v-for="(product, index) in products" :key="index">
                <img v-if="product.product_item.images.length > 0" :src="product.product_item.images[0].url" :alt="product.name"
                    class="object-cover">
                <img v-else :src="'/storage/images/defaults/default.jpg'" alt=""
                    class="object-cover">
                <div class="flex justify-around bg-gray-200 py-2">
                    <span>
                        {{ currencyFormat(product.product_item.original_price) }}
                    </span>
                    <span v-if="product.product_item && product.product_item.sale_price > 0" class="text-red-500 font-bold">
                        ON SAIL! {{currencyFormat(product.product_item.sale_price)}}

                    </span>
                    <span v-else class="line-through text-gray-500">
                        Not on sail
                    </span>
                </div>
                </Link>
            </div>
        </div>
    </div>
</template>

<style></style>