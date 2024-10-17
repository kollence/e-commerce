<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const showingNavigationDropdown = ref(false);
const expandedCategories = ref([]);

defineProps({
  categories: Object
})

function toggleNavigationDropdown() {
  showingNavigationDropdown.value = !showingNavigationDropdown.value;
}

function hideNavigation() {
  showingNavigationDropdown.value = false;
}

function toggleSubcategories(index) {
  if (expandedCategories.value.includes(index)) {
    expandedCategories.value = expandedCategories.value.filter(i => i !== index);
  } else {
    expandedCategories.value.push(index);
  }
}
</script>

<template>
  <!-- Sidebar/Slide Menu -->
  <div class="-me-2 flex items-center lg:hidden">
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
    class="fixed top-0 left-0 h-full w-64 bg-white z-50 transform transition-transform duration-300 ease-in-out lg:hidden">
    <div class="pt-2 pb-3 space-y-1">
      <div>
        <div class="text-black text-center bg-gray-200 py-4">
          <p>Categories:</p>
        </div>
        <div class="flex flex-col divide-y">
          <div v-for="(category, index) in categories" :key="index">
            <div class="flex justify-between">
              <Link :href="route('shop.index', { category: category.slug })" class="py-2 px-4 hover:bg-gray-200">
              {{ category.name }}
              </Link>
              <div class="flex justify-end">
                <button v-if="category.children.length > 0" class="toggle-subcategories flex justify-end"
                  @click="toggleSubcategories(index)">
                  <div v-if="!expandedCategories.includes(index)" class="py-2 px-4 hover:bg-gray-200">
                    &#9655; <!-- Right arrow -->
                  </div>
                  <div v-if="expandedCategories.includes(index)" class="py-2 px-4 hover:bg-gray-200">
                    &#9660; <!-- Down arrow -->
                  </div>
                </button>
              </div>
            </div>

            <!-- Subcategories -->
            <div v-if="expandedCategories.includes(index)" class="pl-3">
              <div v-for="subcategory in category.children" :key="subcategory.name">
                <Link :href="route('shop.index', { category:  subcategory.slug})" class="py-2 px-4 hover:bg-gray-200 block">
                {{ subcategory.name }}
                </Link>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Desktop Navigation Menu -->
  <div class="border-r w-1/5 lg:block hidden">
    <div>
      <div class="text-black text-center bg-gray-200 py-4">
        <p>Categories:</p>
      </div>
      <div v-for="(category, index) in categories" :key="index">
        <div class="flex justify-between">
          <Link :href="route('shop.index', { category: category.slug })" class="py-2 px-4 hover:bg-gray-200">
          {{ category.name }}
          </Link>
          <div class="flex justify-end">
            <button v-if="category.children.length > 0" class="toggle-subcategories flex justify-end"
              @click="toggleSubcategories(index)">
              <div v-if="!expandedCategories.includes(index)" class="py-2 px-4 hover:bg-gray-200">
                &#9655; <!-- Right arrow -->
              </div>
              <div v-if="expandedCategories.includes(index)" class="py-2 px-4 hover:bg-gray-200">
                &#9660; <!-- Down arrow -->
              </div>
            </button>
          </div>
        </div>
        <!-- Subcategories -->
        <div v-if="expandedCategories.includes(index)" class="pl-6">
          <div v-for="subcategory in category.children" :key="subcategory.name">
            <Link :href="route('shop.index', { category:  subcategory.slug })" class="py-2 px-4 hover:bg-gray-200 block">
            {{ subcategory.name }}
            </Link>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Overlay Background -->
  <div v-if="showingNavigationDropdown" @click="hideNavigation"
    class="fixed inset-0 bg-black opacity-50 z-40 lg:hidden"> </div>
</template>