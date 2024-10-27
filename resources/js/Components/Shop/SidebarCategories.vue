<script setup>
import RecursiveCategories from './RecursiveCategories.vue';

const props = defineProps({
    categories: Object
})

</script>

<template>
    <div class="w-1/5 text-xl mx-auto shrink-0">
        <nav>
            <RecursiveCategories v-for="category in categories" :category="category" :key="category.id" />
        </nav>
    </div>
</template>


<style lang="scss" scoped></style>