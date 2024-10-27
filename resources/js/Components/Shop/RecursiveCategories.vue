<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    category: Object,
})
const openCategories = ref([]);

const toggleCategory = (categoryId) => {
  if (openCategories.value.includes(categoryId)) {
      openCategories.value = openCategories.value.filter(id => id !== categoryId);
  } else {
      openCategories.value.push(categoryId);
  }
};

const isCategoryOpen = (categoryId) => openCategories.value.includes(categoryId);


const checkOpenCategories = () => {
const urlParams = new URLSearchParams(window.location.search);
  const activeCategorySlug = urlParams.get('category');
  
  if (activeCategorySlug) {
    const openCategory = (categories) => {
      for (const category of categories) {
        if (category.slug === activeCategorySlug) {
          openCategories.value.push(category.id);
          return true;
        }
        const firstChild = category.all_children.find(child => child.slug === activeCategorySlug);
        if (firstChild) {
          openCategories.value.push(category.id, firstChild.id);
          return true;
        }
      }
    };
    openCategory([props.category]);
  }
  
};

onMounted(() => {
  checkOpenCategories();
});
</script>

<template>
    <div class="flex items-center justify-between cursor-pointer">
        <Link :href="route('shop.index', { category: category.slug })" class="m-1  flex-1 hover:border hover:border-gray-300 rounded-md">
        {{ category.name }}
        </Link>
        <button  @click="toggleCategory(category.id)" class="p-2  hover:border hover:border-gray-300 rounded-md"  v-if="category.all_children.length">
            <svg :class="isCategoryOpen(category.id) ? 'arrow-down' : 'arrow-up'" class="mx-1 transition-transform duration-300 shrink-0 justify-self-end" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        </button>
    </div>
    <transition name="slide-fade">
    <div  v-if="isCategoryOpen(category.id) && category.all_children.length" class="ml-2">
        <RecursiveCategories v-for="child in category.all_children" :category="child" :key="child.id">
            <Link :href="route('shop.index', { category: child.slug })" class="flex">
            {{ child.name }}
            </Link>
        </RecursiveCategories>
    </div>
  </transition>
</template>


<style  scoped>
.arrow-up {
  transform: rotate(90deg);
}
.arrow-down {
  transform: rotate(270deg);
}
.slide-fade-enter-active {
  transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
  transition: all 0.2s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateX(20px);
  opacity: 0;
}
</style>
