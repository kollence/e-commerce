<script setup>
import { Link, router } from '@inertiajs/vue3'
const props = defineProps({
    breadcrumbs: Object
})
</script>

<template>
        <header class="text-black bg-gray-300 shadow">
        <div class="flex justify-between items-center max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center leading-tight">
                <Link :href="route('welcome')" class="text-black transition hover:text-yellow-700">
                Home
                </Link>
                <span class="mx-2">/</span>
                <slot name="breadcrumbs"></slot>
                <template v-for="(crumb, index) in breadcrumbs" :key="index">
                    <span v-show="index > 0" class="mx-2 text-black transition hover:text-yellow-700">/</span>
                    <Link :href="crumb.url" class="text-black transition hover:text-yellow-700">
                        {{ crumb.name }}
                    </Link>
                </template>
                <slot name="breadcrumbs-end"></slot>
            </div>
            <div class="w-1/2">
                <slot name="search"></slot>
            </div>
        </div>
    </header>
</template>

<style scoped>

</style>