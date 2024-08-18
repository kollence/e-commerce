<script setup>
import { Head } from '@inertiajs/vue3';
import Nav from '@/Components/LayoutPartials/Nav.vue';

defineProps({
    title: String,
});


</script>

<template>
    <div>

        <Head :title="title" />

        <div class="min-h-screen bg-gray-100">
            <Nav />

            <!-- Page Heading -->
            <header class="bg-gray-700 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h3 class="text-white text-center leading-tight">Layout header</h3>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>

            <!-- Page Footer -->
            <footer class="text-center text-white bg-black px-4 py-4">
                <div>
                    <p>Layout footer </p>
                    <p>
                        Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolorum ratione tenetur totam, nostrum consectetur asperiores excepturi aspernatur, modi sunt minima, rerum odit ipsam optio alias? Esse commodi cupiditate suscipit cum.
                    </p>
                </div>
            </footer>
        </div>
    </div>
</template>
