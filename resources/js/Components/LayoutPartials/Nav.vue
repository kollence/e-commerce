<script setup>
import { ref, computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

const showingNavigationDropdown = ref(false);
const page = usePage()

const logout = () => {
    router.post(route('logout'));
};

const cartCounter = computed(() => page.props.cart_count)

const authUser = computed(() => page.props.auth.user)
</script>

<template>
    <nav class="border-b border-gray-500">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <Link :href="route('welcome')">
                            <Icon name="logo"></Icon>
                        </Link>
                    </div>

                    <!-- Navigation Links -->
                    <!-- <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </NavLink>
                    </div> -->
                </div>

                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <!-- <div class="ms-3 relative">
                                
                            </div> -->

                    <!-- Settings Dropdown -->
                    <div class="ms-3 relative">
                        <div class="flex items-center space-x-3">
                            <!-- <Link :href="route('dashboard')" class="hover:text-yellow-500 transition">Dashboard</Link> -->
                            <template v-if="authUser">
                                <Link :href="route('dashboard')" class="hover:text-yellow-500 transition" :active="route().current('dashboard')">
                                Dashboard
                                </Link> <!-- Authentication -->
                                <form method="POST" @submit.prevent="logout">
                                    <button type="submit" class="hover:text-yellow-500 transition">
                                        Logout
                                    </button>
                                </form>
                            </template>

                            <template v-else>
                                <Link :href="route('register')" class="hover:text-yellow-500 transition" :active="route().current('register')">
                                Register
                                </Link>
                                <Link :href="route('login')" class="hover:text-yellow-500 transition" :active="route().current('login')">
                                Login
                                </Link>
                            </template>
                            <Link :href="route('shop.index')" class="hover:text-yellow-500 transition" :active="route().current('shop.index')">
                                Shop
                                </Link>

                            <Link :href="route('cart.index')" class="hover:text-red-700 transition">
                            <span  v-if="$page.props.cart_count > 0" class="bg-red-600 text-white text-xs rounded-full px-1 absolute" style="top: -10px; right: -8px;">
                                {{cartCounter}}
                            </span>

                            <Icon class="w-4 h-4 fill-current" name="cart"></Icon>

                            </Link>

                        </div>
                    </div>
                </div>

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                        @click="showingNavigationDropdown = !showingNavigationDropdown">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path
                                :class="{ 'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path
                                :class="{ 'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{ 'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown }" class="sm:hidden">
            <template v-if="authUser">
                <div class="pt-2 pb-3 space-y-1">
                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                        Dashboard
                    </ResponsiveNavLink>
                </div>
            </template>
            <template v-else>
                <div class="pt-2 pb-3 space-y-1">
                    <ResponsiveNavLink :href="route('register')">
                        Register
                    </ResponsiveNavLink>
                </div>
                <div class="pt-2 pb-3 space-y-1">
                    <ResponsiveNavLink :href="route('login')">
                        Login
                    </ResponsiveNavLink>
                </div>
            </template>
                <div class="pt-2 pb-3 space-y-1">
                    <ResponsiveNavLink :href="route('shop.index')" :active="route().current('shop.index')">
                        Shop
                    </ResponsiveNavLink>
                </div>
            <div class="pt-2 pb-3 space-y-1">
                <Link :href="route('cart.index')" class="flex items-center pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-red-600 hover:border-red-700 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition">
                    
                    <Icon class="w-4 h-4 fill-current" name="cart"></Icon>
                    
                    <span class="ml-1 text-red-600" v-if="$page.props.cart_count > 0">
                        {{cartCounter > 0 ? cartCounter+' in cart' : ''}} 
                    </span>
                </Link>
            </div>
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">


                <div class="mt-3 space-y-1">
                    <ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')">
                        Profile
                    </ResponsiveNavLink>



                    <!-- Authentication -->
                    <form method="POST" @submit.prevent="logout">
                        <ResponsiveNavLink as="button">
                            Logout
                        </ResponsiveNavLink>
                    </form>

                </div>
            </div>
        </div>
    </nav>
</template>



<style></style>