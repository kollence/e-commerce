<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
        <header class="text-black bg-gray-300 shadow">
        <div class="flex justify-between items-center max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center leading-tight">
                <Link :href="route('welcome')" class="text-black transition hover:text-yellow-700">
                Home
                </Link>
                <slot name="breadcrumbs"></slot>
            </div>
            <div class="w-1/2">
                <input type="search" name="search" placeholder="Search" class="w-full p-2 rounded-lg border border-gray-300 focus:border-yellow-700 focus:ring-2 focus:ring-yellow-700"d="">
            </div>
        </div>
    </header>
</template>

<style scoped>

</style>