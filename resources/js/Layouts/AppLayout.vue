<script setup>
import { Head } from '@inertiajs/vue3';
import Nav from '@/Components/LayoutPartials/Nav.vue';
import Footer from '@/Components/LayoutPartials/Footer.vue';
import ToastList from '@/Components/Toast/ToastList.vue';

defineProps({
    title: String,
});


</script>

<template>
    <div class="">
        <ToastList/>
        <Head :title="title" />

        <div class="min-h-screen text-gray-500 bg-white dark:text-gray-300 dark:bg-gray-800">
            <!-- Components/LayoutPartials -->
            <Nav />

            <!-- Page Heading -->
            <header class=" shadow bg-gradient-to-r from-emerald-700 via-teal-800 to-cyan-800 animate-gradient-xy">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h3 class="text-white text-center leading-tight">Layout header</h3>
                </div>
            </header>

            <!-- Page Content -->
            <main  class="container mx-auto">
                <slot />
            </main>

            <!-- Page Footer -->
            <!-- Components/LayoutPartials -->
            <Footer />
        </div>
    </div>
</template>
