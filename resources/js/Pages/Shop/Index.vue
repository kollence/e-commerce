<script setup>
import Breadcrumbs from '@/Components/LayoutPartials/Breadcrumbs.vue';
import NavCategories from '@/Components/Shop/NavCategories.vue';
import { Link, Head, router } from '@inertiajs/vue3';
import { onBeforeUnmount, ref } from 'vue';
import breadcrumbsStore from '@/Components/LayoutPartials/store/breadcrumbs.js'
const props = defineProps({
    products: Object,
    categories: Object,
    selectedCategory: String,
    queryParams: Object,
    breadcrumbs: Object,
});
const sort = ref('default');
const category = ref(props.queryParams.category);

const updateSort = () => {
    // console.log(sort.value);
  const params = new URLSearchParams(window.location.search);
  category.value && params.set('category', category.value);
  sort.value !== 'default' ? params.set('sort', sort.value) : params.delete('sort');
  router.get(`${window.location.pathname}?${params.toString()}`, {}, { preserveState: true });
};
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
            <div class="flex items-center justify-end space-x-2 pt-4 pr-6">
                <span class="text-gray-500">Sort by:</span>
                <select v-model="sort" name="sort" id="sort" class="bg-gray-200 rounded-lg py-2 px-7 focus:outline-none focus:bg-white" @change="updateSort">
                    <option value="default" selected="selected">Pick option</option>
                    <option value="is_featured">Featured</option>
                    <option value="price_ascending">Price: Low to High</option>
                    <option value="price_descending">Price: High to Low</option>
                    <option value="newest">Newest</option>
                    <option value="oldest">Oldest</option>
                </select>
            </div>
            <div v-if="products.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 sm:gap-2 md:gap-4 lg:gap-5 sm:p-0 md:p-4">
                <Link :href="route('shop.show', [product.slug, product.product_item.id])" class="card rounded-lg p-2 shadow-md" v-for="(product, index) in products" :key="index">
                <img v-if="product.product_item.images.length > 0" :src="product.product_item.images[0].url" :alt="product.name"
                    class="object-cover">
                <img v-else :src="'/storage/images/defaults/default.jpg'" alt=""
                    class="object-cover">
                <div class="flex flex-wrap gap-4 justify-between p-1">
                    <span :class="{ 'line-through text-gray-500 col-span-auto': product.product_item.sale_price > 0}">
                        {{ currencyFormat(product.product_item.original_price) }}
                    </span>
                    <span class="text-gray-500 col-span-auto text-center">
                    </span>
                    <span v-if="product.product_item && product.product_item.sale_price > 0" class="text-right text-red-500 font-bold col-span-auto">
                        On sail! {{currencyFormat(product.product_item.sale_price)}}
                    </span>
                </div>
                </Link>
            </div>
            <div v-else class="flex justify-center">
                <h1 class="text-2xl font-bold text-gray-500" >Products not found.</h1>
            </div>
        </div>
    </div>
</template>

<style></style>