<script setup>
import { onUnmounted, ref } from 'vue';
import ToastItem from './ToastItem.vue';
import { router, usePage } from '@inertiajs/vue3';
import toast from './store/toast';
const page = usePage()
// Event listener on FINISH to add a new toast item to the list at top of items (unshift)
let clearFinishEventListener = router.on('finish', () => {
    if(page.props.flash.message) {
        toast.addToast({
           message: page.props.flash.message
        })
    }
});
const remove = (index) => {
    toast.removeToast(index)
}

onUnmounted(() => clearFinishEventListener())
</script>

<template>
    <TransitionGroup 
        tag="div"
        enter-from-class="translate-x-full opacity-0"
        enter-active-class="duration-300 ease-out"
        leave-active-class="duration-300 ease-in"
        leave-to-class="translate-x-full opacity-0"
        class="fixed top-4 right-4 z-50 space-y-4 w-full max-w-xs">
        <ToastItem v-for="(item, index) in toast.items" :key="item.key" :message="item.message" type="success" @close="remove(index)"/>
    </TransitionGroup>
</template>


<style lang="scss" scoped>

</style>