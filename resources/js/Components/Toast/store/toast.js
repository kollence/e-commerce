import { reactive } from "vue";

export default reactive({
    items: [],
    
    addToast(toast) {
        this.items.unshift({
            key: Symbol(),
            ...toast
        });
    },
    removeToast(index) {
        this.items.splice(index, 1);
    },
    clearToasts() {
        this.items = [];
    },
})