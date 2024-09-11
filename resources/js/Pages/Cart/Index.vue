<script setup>
import NavigationHeader from '@/Components/NavigationHeader.vue';
import { Head, Link } from '@inertiajs/vue3';


</script>

<template>
    <Head title="Cart" />
    <NavigationHeader>
        <template #breadcrumbs>
            <span class="mx-2">/</span>
            <Link :href="route('shop.index')">Shop</Link>
            <span class="mx-2">/</span>
            <span>Cart</span>
        </template>
        <template #search>
            <input type="search" class="w-full bg-gray-200 rounded-lg py-2 px-4 focus:outline-none focus:bg-white" placeholder="Search for products">
        </template>
    </NavigationHeader>
    <div>
        Cart items
    </div>
</template>

<style scoped>

</style>