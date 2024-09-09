<script setup>
import NavigationHeader from '@/Components/NavigationHeader.vue';
import NavCategories from '@/Components/Shop/NavCategories.vue';
import { Link, Head } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';

const props = defineProps({
    product: Object,
});
const selectedSizeOptionId = ref(props.product.lowest_priced_item.size_options[0].id);
const selectedSizeOption = ref(props.product.lowest_priced_item.size_options[0]);

function updateSelectedSizeOption() {
    selectedSizeOption.value = props.product.lowest_priced_item.size_options.find(
    size_option => size_option.id === parseInt(selectedSizeOptionId.value)
    );
}

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
        <template #search>
            <input type="text" class="w-full bg-gray-200 rounded-lg py-2 px-4 focus:outline-none focus:bg-white" placeholder="Search for products">
        </template>
    </NavigationHeader>

    <div class="flex flex-col lg:flex-row bg-gradient-to-r from-emerald-400 via-teal-600 to-cyan-950">

        <div class="container text-stone-100 mx-auto px-4 py-8 justify-end">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 mb-5">
                <div class="md:col-span-2">
                    <!-- <small>Product item images</small> -->
                    <img v-for="(image, i) in product.lowest_priced_item.images" :key="i" :src="image.url" :alt="product.name"/> 
                </div>
                <div class="md:col-span-6 ">
                    <img :src="product.images[0].url" :alt="product.name" class="object-cover border border-gray-700 rounded-lg">
                </div>
                <div class="md:col-span-4">
                    <h1 class="text-3xl font-bold mb-4">{{ product.name }}</h1>
                    <div class="flex items-end mb-4  pb-2">

                        <span class="  mr-2">Brand:</span>
                        <p class="text-lg">{{ product.brand.name }}</p>
                    </div>
                    <div class="flex justify-start items-center mb-1 pb-1 border-b border-gray-300">
                        <div class="flex items-center mb-1  pb-1">
                            <span class=" mr-2">Is Featured:</span>
                            <span :class="product.is_featured ? 'text-green-100' : 'text-red-600'">{{
                                product.is_featured ?
                                    '&#x2714;'
                                : ' &#x2716;' }}</span>
                        </div>
                        <div class="flex items-center mb-1  pb-1 ml-5">
                            <span class=" mr-2">Product code:</span>
                            <span>{{product.lowest_priced_item.product_code}}</span>
                        </div>
                    </div>

                    <div >
                        <label for="size_options" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Select size option
                        </label>
                        <select id="size_options" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            v-model="selectedSizeOptionId"
                            @change="updateSelectedSizeOption"    
                        >
                            <option v-for="size_options in product.lowest_priced_item.size_options" :key="size_options.id" :value="size_options.id">{{ size_options.name }}</option>
                        </select>
                    </div>
                    <div v-if="selectedSizeOption" class="ml-1 mt-1">
                        <p><strong>SKU:</strong> {{ selectedSizeOption.pivot.sku }}</p>
                        <p><strong>In Stock:</strong> {{ selectedSizeOption.pivot.in_stock }}</p>
                        <!-- <p class="mt-1" v-if="selectedSizeOption.size_description"><strong>Description:</strong> {{ selectedSizeOption.size_description }}</p> -->
                    </div>

                    <div class="flex justify-between items-center mt-5 mb-1 border font-bold text-stone-200 text-lg p-4">
                        <span>
                            {{ currencyFormat(product.lowest_priced_item.original_price) }}
                        </span>
                        <span v-if="product.lowest_priced_item && product.lowest_priced_item.sale_price < 0"
                            class="text-red-500 font-bold text-lg">
                            ON SAIL! {{ currencyFormat(product.lowest_priced_item.sale_price) }}
                        </span>
                        <span v-else class="line-through  font-bold text-gray-400">
                            Not on sail
                        </span>
                    </div>
                    <div class="items-center mb-1 border-b border-gray-300 ">
                        <span class="  mr-2">Description:</span>
                        <p class="mb-2">{{ product.description }}</p>
                    </div>
                    <div class="items-center mb-2 border-b border-gray-300">
                        <span class=" mr-2">Care Instructions:</span>
                        <p class=" mb-4">{{ product.care_instructions }}</p>

                    </div>
                    <div class="items-center mb-4 border-b border-gray-300">
                        <span class=" mr-2">About:</span>
                        <p>{{ product.about }}</p>
                    </div>

                </div>
            </div>
            <h2 class="text-xl text-cyan-950 font-semibold m-2 mt-3">Product Variants</h2>
            <div
                class="grid grid-cols-1 md:grid-cols-3  gap-4 border-1 border bg-gradient-to-t from-emerald-500 via-teal-600 to-cyan-700 p-2 rounded-lg mt-5  ">
                <!-- product item variants -->
                <div v-for="variant in product.product_items" :key="variant.product_code"
                    class="card rounded-lg p-4 shadow-md align-self-end relative bg-gradient-to-r from-emerald-500 via-teal-600 to-cyan-700">
                    <div class="flex flex-row justify-between items-center mb-2">
                        <div class="md:col-span-3">
                            <div class="border-2 absolute left-1 top-2 rounded-full" :key="variant.product_code">
                                <span class="w-half h-full rounded-full p-2 inline-block"
                                    :style="{ backgroundColor: variant.color.hex }">{{ variant.color.name }}</span>
                            </div>
                        </div>
                        <div class="md:col-span-4">
                            <img :src="product.images[0].url" :alt="variant.product_code"
                                class="w-40 h-40 object-cover rounded-lg mb-2">

                        </div>
                    </div>



                    <div class="md:col">
                        <h2 class="text-lg text-green-900 font-bold mb-1">{{ variant.is_active ? 'Active' : 'Not Active'}}
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">{{ variant.product_code }}</div>
                        <h2 class="text-xl font-semibold mb-2">Product Name: {{ product.name }}</h2>
                        <p class="text-dark-500 mb-4">{{ variant.description }}</p>
                        <div class="flex items-center justify-between mb-4 border-t border-b">
                            <span class="text-black-700 mr-2 font-bold">Price: {{ currencyFormat(variant.original_price)
                                }}</span>
                            <span class="text-lime-200  font-bold">{{ variant.sale_price ? "On sale: " +
                                currencyFormat(variant.sale_price) : 'Not on sail' }}</span>
                        </div>

                        <div class="flex justify-around">
                            <button
                                class="text-black hover:text-white bg-gradient-to-r from-teal-200 via-teal-500 to-teal-800 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-teal-500 dark:focus:ring-teal-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Add
                                to Favorites</button>
                            <button
                                class="text-black hover:text-white bg-gradient-to-r from-teal-400 via-teal-500 to-teal-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-teal-700 dark:focus:ring-teal-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Add
                                to Cart</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<style></style>