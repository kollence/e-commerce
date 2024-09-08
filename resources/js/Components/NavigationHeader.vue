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
                <slot name="search"></slot>
            </div>
        </div>
    </header>
</template>

<style scoped>

</style>