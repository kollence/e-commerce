<script setup>
import { Head } from '@inertiajs/vue3';
import Nav from '@/Components/LayoutPartials/Nav.vue';
import Footer from '@/Components/LayoutPartials/Footer.vue';

defineProps({
    title: String,
});


</script>

<template>
    <div class="">

        <Head :title="title" />

        <div class="min-h-screen bg-gray-100">
            <!-- Components/LayoutPartials -->
            <Nav />

            <!-- Page Heading -->
            <header class=" shadow bg-gradient-to-r from-emerald-300 via-teal-600 to-cyan-950 animate-gradient-xy">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h3 class="text-white text-center leading-tight">Layout header</h3>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>

            <!-- Page Footer -->
            <!-- Components/LayoutPartials -->
            <Footer />
        </div>
    </div>
</template>
