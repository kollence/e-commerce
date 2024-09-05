<script setup>
import NavigationHeader from '@/Components/NavigationHeader.vue';
import NavCategories from '@/Components/Shop/NavCategories.vue';
import { Link, Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

defineProps({
    products: Object,
    categories: Object,
    selectedCategory: String

});

</script>

<template>
    <Head :title="selectedCategory" />
    <NavigationHeader>
        <template #breadcrumbs>
            <span class="mx-2">/</span>
            <span>Shop {{ selectedCategory ? '- '+selectedCategory : ''}}</span>
        </template>
    </NavigationHeader>

    <div class="flex flex-col lg:flex-row">
        <NavCategories :categories="categories" />

        <div class="border-l w-4/5 mx-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 sm:gap-2 md:gap-4 lg:gap-5 sm:p-0 md:p-4">
                <Link :href="route('shop.show', product.slug)" class="card rounded-lg p-2 shadow-md" v-for="(product, index) in products" :key="index">
                <img v-if="product.images.length > 0" :src="product.images[0].url" :alt="product.name"
                    class="object-cover">
                <img v-else :src="'/storage/images/defaults/default.jpg'" alt=""
                    class="object-cover">
                <div class="flex justify-around bg-gray-200 py-2">
                    <span>
                        {{ currencyFormat(product.lowest_sale_price_product_item.original_price) }}
                    </span>
                    <span v-if="product.lowest_sale_price_product_item" class="text-red-500 font-bold">
                        ON SAIL! {{currencyFormat(product.lowest_sale_price_product_item.sale_price)}}

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