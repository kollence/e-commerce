<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
const showingNavigationDropdown = ref(false);
defineProps({
    products: Object,
    categories: Object,

})
function toggleNavigationDropdown() {
  showingNavigationDropdown.value = !showingNavigationDropdown.value;
}

function hideNavigation() {
  showingNavigationDropdown.value = false;
}

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
    <div class="flex flex-col md:flex-row">
        <!-- Sidebar/Slide Menu -->
        <div class="-me-2 flex items-center sm:hidden">
            <button
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                @click="showingNavigationDropdown = !showingNavigationDropdown">
                Categories:
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{ 'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <!-- Responsive Navigation Menu -->
        <div :class="{ 'translate-x-0': showingNavigationDropdown, '-translate-x-full': !showingNavigationDropdown }"
            class="fixed top-0 left-0 h-full w-64 bg-white z-50 transform transition-transform duration-300 ease-in-out sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <div>
                    <div class="text-black text-center bg-gray-200 py-4">
                        
                            <p class="">Categories:</p>
                    </div>
                    <div class="flex flex-col divide-y">
                        <Link href="#" v-for="(category, index) in categories" :key="index"
                            class="py-2 px-4 hover:bg-gray-200">
                        {{ category.name }}
                        </Link>
                    </div>
                </div>
            </div>
        </div>
          <!-- Overlay Background -->
  <div
    v-if="showingNavigationDropdown"
    @click="hideNavigation"
    class="fixed inset-0 bg-black opacity-50 z-40 sm:hidden"
  ></div>
        <!-- Responsive Navigation Menu -->
        <!-- <div :class="{ 'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown }" class="sm:hidden">

            <div class="pt-2 pb-3 space-y-1">
                <div>
                    <div class="text-black text-center bg-gray-200 py-4">
                        <p>Categories:</p>
                    </div>
                    <div class="flex flex-col divide-y">
                        <Link href="#" v-for="(category, index) in categories" :key="index"
                            class="py-2 px-4 hover:bg-gray-200">
                        {{ category.name }}
                        </Link>
                    </div>
                </div>
            </div>
        </div> -->
        <div class="border-r w-1/5 lg:block hidden">
            <div>
                <div class="text-black text-center bg-gray-200 py-4">
                    <p>Categories:</p>
                </div>
                <div class="flex flex-col divide-y">
                    <Link href="#" v-for="(category, index) in categories" :key="index"
                        class="py-2 px-4 hover:bg-gray-200">
                    {{ category.name }}
                    </Link>
                </div>
            </div>
        </div>
        <div class="border-l w-4/5 mx-auto">
            <div class="container flex flex-wrap">
                <Link href="#" class="flex flex-col w-full p-4 rounded sm:w-1/2 md:w-1/4"
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