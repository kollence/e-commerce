<script setup>
import Breadcrumbs from '@/Components/LayoutPartials/Breadcrumbs.vue';
import breadcrumbsStore from '@/Components/LayoutPartials/store/breadcrumbs'
import { Link, Head, useForm, router } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';

const props = defineProps({
    product: Object,
    productItem: Object,
    productItems: Object,
    breadcrumbs: Object,
});
const selectedSizeOptionId = ref(props.productItem.size_options[0].id);
const selectedSizeOption = ref(props.productItem.size_options[0]);
const quantity = ref(1);

onMounted(() => { // pick size_option if provided in url (no call request)
    const url = new URL(window.location.href);
      const sizeOptionSlug = url.searchParams.get('size_option');
      if (sizeOptionSlug) {
        const matchedOption = props.productItem.size_options.find(option => option.slug === sizeOptionSlug);
        if (matchedOption) {
          selectedSizeOption.value = matchedOption;
          selectedSizeOptionId.value = matchedOption.id;
        }
      } 
})

const updateQueryParam = (sizeOptionSlug) => {
    // make changes to the URL query parameters but don't make request (data is already there)
    const url = new URL(window.location.href);
    url.searchParams.set('size_option', selectedSizeOption.value.slug);
    window.history.replaceState({}, '', url)
};

const priceXquantity = computed(() => {
    if (props.productItem.sale_price && props.productItem.sale_price > 0) {
        return props.productItem.sale_price * quantity.value;
    } else {
        return props.productItem.original_price * quantity.value;
    }
});
function selectSizeOption(sizeOptionId) {
    //reset quantity on change
    quantity.value = 1;
    selectedSizeOptionId.value = sizeOptionId;

    selectedSizeOption.value = props.productItem.size_options.find(
        size_option => size_option.id === parseInt(sizeOptionId)
    );
    updateQueryParam(selectedSizeOption.value.slug)
}
function decrementQuantity() {
    if (quantity.value > 1) {
        quantity.value--;
    }
}
function incrementQuantity() {
    if (quantity.value < selectedSizeOption.value.pivot.in_stock) {
        quantity.value++;
    } else {
        quantity.value = selectedSizeOption.value.pivot.in_stock;
    }
}
// Initialize the form with the product data
const form = useForm({
    product_item_id: props.productItem.id,
    size_option_id: selectedSizeOptionId,
    // in_stock: selectedSizeOption.value.pivot.in_stock - quantity.value,
    quantity: quantity.value,
    // submitted_pxq: priceXquantity.value,
});
// Watchers to update form fields when values change
watch([selectedSizeOption, quantity], () => {
    form.quantity = quantity.value;
    form.size_option_id = selectedSizeOptionId;
});
// On submit, add additional fields and send the form data to the server
function addToCart() {
    form.post(route('cart.add'), {
        preserveScroll: true,
        onSuccess: () => {
        },
    });
}
function orderNow() {
    // console.log('order now ', form);
}
// NEED FIX!! only clear previous breadcrumbs (category history) so they don't pile up just in Cart/Index output
onBeforeUnmount(() => {
    breadcrumbsStore.removeCrumbs()
    breadcrumbsStore.addCrumbs(props.breadcrumbs)  
})
</script>

<template>

    <Head :title="product.name" />
    <Breadcrumbs :breadcrumbs="breadcrumbs">
        <template #breadcrumbs>
            <Link :href="route('shop.index')">Shop</Link>
            <span class="mx-2">/</span>
        </template>
        <template #breadcrumbs-end>
            <span class="mx-2">/</span>
            <span>{{ product.name }}</span>
        </template>
        <template #search>
            <input type="text" class="w-full bg-gray-200 rounded-lg py-2 px-4 focus:outline-none focus:bg-white" placeholder="Search for products">
        </template>
    </Breadcrumbs>
    <div class="flex flex-col lg:flex-row">
        <div class="container mx-auto px-4 py-8 justify-end">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 mb-5">
                <div class="md:col-span-2 px-3">
                    <!-- <small>Product item images</small> -->
                    <img class="mb-3 shadow-md" v-for="(image, i) in productItem.images" :key="i" :src="image.url"
                        :alt="product.name" />
                </div>
                <div class="md:col-span-6 px-4">
                    <img :src="productItem.images[0].url" :alt="product.name"
                        class="shadow-md object-cover border border-gray-700 rounded-lg">
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
                                '&#x2714;' : ' &#x2716;' }}</span>
                        </div>
                        <div class="flex items-center mb-1  pb-1 ml-5">
                            <span class=" mr-2">Product code:</span>
                            <span>{{ productItem.product_code }}</span>
                        </div>
                    </div>
                    <div class="flex justify-start items-center mb-3 pb-3 border-b border-gray-300">
                        <div class="mr-2">
                            <div class="flex items-center mb-1  pb-1">
                                Picked:
                            </div>
                            <div class="shadow-md">
                                <span class="p-2 shadow-md border border-2 border-stone-100" :style="{ 'background-color': productItem.color.hex }">
                                    <span class="color-name">
                                        {{ productItem.color.name}}
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center mb-1 pb-1">
                                Colors:
                            </div>
                            <Link  preserve-scroll :href="route('shop.show', [product.slug, color.id])"
                                :style="{ 'background-color': color.color.hex }"
                                class="mr-2 card rounded-lg p-2 shadow-md" v-for="(color, i) in productItems"
                                :key="i">
                            <span>
                                <span class="color-name">
                                    {{ color.color.name }}
                                </span>
                            </span>
                            </Link>
                        </div>

                    </div>
                    <div class="">
                        <div>
                            Sizes:
                        </div>

                        <button v-for="size_option in productItem.size_options" :key="size_option.id" :class="{
                            'bg-blue-500 text-white': selectedSizeOptionId === size_option.id,
                            'bg-gray-50 text-gray-900': selectedSizeOptionId !== size_option.id,
                            'border border-gray-300 text-sm rounded-lg p-2.5 mr-2 mb-2 focus:outline-none': true
                        }" @click="selectSizeOption(size_option.id)">
                            {{ size_option.name }}
                        </button>
                    </div>
                    <div v-if="selectedSizeOption" class="ml-1">
                        <p><strong>SKU:</strong> {{ selectedSizeOption.pivot.sku }}</p>
                        <p v-if="selectedSizeOption.pivot.in_stock == 0">
                            <strong class="text-red-600">Sold out</strong>
                        </p>
                        <p v-else-if="selectedSizeOption.pivot.in_stock - quantity < 1">
                            <strong class="text-red-600">The last one left that you can order!</strong>
                        </p>
                        <p v-else-if="selectedSizeOption.pivot.in_stock - quantity < 5">
                            <strong class="text-orange-700">Only a few left  {{ selectedSizeOption.pivot.in_stock - quantity }}</strong>
                        </p>
                        <p v-else><strong>In Stock:</strong> {{ selectedSizeOption.pivot.in_stock - quantity }}</p>
                        <!-- <p class="mt-1" v-if="selectedSizeOption.size_description"><strong>Description:</strong> {{ selectedSizeOption.size_description }}</p> -->
                    </div>
                    <div class="flex items-center font-bold py-1" :class="{'justify-between' : selectedSizeOption.pivot.in_stock > 0, 'justify-end': selectedSizeOption.pivot.in_stock == 0}">
                        <div class="flex text-black items-center" v-if="selectedSizeOption.pivot.in_stock > 0" >
                            <button :disabled="quantity <= 1"
                                :class="{'bg-stone-300 text-gray-500 cursor-not-allowed': quantity <= 1, 'bg-stone-200': quantity > 1}"
                                class="border-l border-gray-700 border-y px-3 rounded-l text-2xl"
                                @click="decrementQuantity">-</button>
                            <input :disabled="selectedSizeOption.pivot.in_stock - quantity < 1" type="number" min="1" :max="selectedSizeOption.pivot.in_stock"
                                :class="{'bg-stone-300 text-gray-500 cursor-not-allowed': selectedSizeOption.pivot.in_stock - quantity < 1, 'bg-stone-200': selectedSizeOption.pivot.in_stock - quantity >= 1}"
                                v-model="quantity"
                                class="appearance-none-arrow border rounded-none px-4 py-1 w-17 text-center">
                            <button :disabled="selectedSizeOption.pivot.in_stock - quantity < 1"
                                :class="{'bg-stone-300 text-gray-500 cursor-not-allowed': selectedSizeOption.pivot.in_stock - quantity < 1, 'bg-stone-200': selectedSizeOption.pivot.in_stock - quantity >= 1}"
                                class="border-r border-gray-700 border-y px-3 rounded-r text-2xl"
                                @click="incrementQuantity">+</button>
                        </div>

                        <div>
                            price per quantity: {{ currencyFormat(priceXquantity) }}
                        </div>
                    </div>
                    <div v-if="selectedSizeOption.pivot.in_stock != 0" class="flex justify-between items-center mt-5 mb-1 border font-bold text-lg p-4">
                        <button
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            @click="addToCart">
                            Add to Cart
                        </button>
                        <button
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            @click="orderNow">
                            Order Now
                        </button>
                    </div>
                    <div class="flex justify-between items-center mt-5 mb-1 border font-bold text-lg p-4">
                        <span>
                            {{ currencyFormat(productItem.original_price) }}
                        </span>
                        <span v-if="productItem.sale_price" class="text-red-500 font-bold text-lg">
                            ON SAIL! {{ currencyFormat(productItem.sale_price) }}
                        </span>
                        <span v-else class="line-through  font-bold text-gray-400">
                            Not on sail
                        </span>
                    </div>
                    <div class="items-center mb-1 border-b border-gray-300 ">
                        <span class="mr-2">Description:</span>
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
            <div class="grid grid-cols-1 md:grid-cols-3  gap-4 p-2 rounded-lg mt-5  ">
                <!-- product item variants -->
                <div v-for="variant in productItems" :key="variant.product_code"
                    class="card rounded-lg p-4 shadow-md align-self-end relative border border-gray-700  border-1">
                    <div class="flex flex-row justify-between items-center mb-2">
                        <div class="md:col-span-3">
                            <div class="border-2 absolute left-1 top-2 rounded-full" :key="variant.product_code">
                                <span class="w-half h-full rounded-full p-2 inline-block"
                                    :style="{ backgroundColor: variant.color.hex }">{{ variant.color.name }}</span>
                            </div>
                        </div>
                        <div class="md:col-span-4">
                            <img :src="variant.images[0].url" :alt="variant.product_code"
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
                            <span class="text-red-500  font-bold">{{ variant.sale_price ? "On sale: " +
                                currencyFormat(variant.sale_price) : '' }}</span>
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

<style scoped>
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0;
}
.color-name {
    /* This value is the OPPOSITE color of our background */
    color: rgb(255, 255, 255) !important; 
    mix-blend-mode: exclusion !important;
    -webkit-mix-blend-mode: exclusion !important;
}
</style>