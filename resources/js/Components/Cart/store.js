import { defineStore, storeToRefs } from "pinia";
import { computed, ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";

export const useCartStore = defineStore("cart", () => {
    //state
    const cartItems = ref({})
    const orderSummary = ref({
        tax_rate: 0,
        cart_subtotal: 0, 
        cart_tax: 0, 
        new_total: 0, 
        coupon: { code: null, discount: 0 }
    })
    const formCoupon = useForm({ coupon_code: null })
    const formRemoveKey = useForm({ cart_item_key: null })
    const formCartItems = useForm({ cart_items: null })
    // helpers
    let cartItemsChanged = ref(false);
    const price = (product_item) => { return (product_item.sale_price < product_item.original_price && product_item.sale_price > 0) ? product_item.sale_price : product_item.original_price; };
    
    // getter
    const couponCode = computed(() => orderSummary.value.coupon.code || null)
    const rawCartSubtotal = computed(() => { 
        let sum = 0; 
        for (const item in cartItems.value) { 
            sum += cartItems.value[item].subtotal; 
        } 
        return Number(sum.toFixed(2)); 
    }); 
    const cartSubtotal = computed(() => { const discount = orderSummary.value.coupon.discount || 0; 
        let subtotalAfterDiscount = rawCartSubtotal.value - discount; 
        subtotalAfterDiscount = subtotalAfterDiscount < 0 ? 0 : subtotalAfterDiscount; 
        // Ensure subtotal is not negative 
        return subtotalAfterDiscount; 
    });
    const cartTax = computed(() => { 
        let tax = cartSubtotal.value * orderSummary.value.tax_rate; 
        tax = tax < 0 ? 0 : tax; 
        // Ensure tax is not negative 
        return tax; 
    }); 
    const newTotal = computed(() => { 
        let total = cartSubtotal.value + cartTax.value; 
        total = total < 0 ? 0 : total; 
        // Ensure total is not negative 
        return total; 
    });

    // action
    function incrementQuantity (key){ 
        const item = cartItems.value[key]
        // console.log('store incrementQuantity');
        item.product_item.quantity++; 
        updateSubtotal(item); 
        cartItemsChanged.value = true
    } 
    function decrementQuantity (key){ 
        const item = cartItems.value[key]
        if (item.product_item.quantity > 1) { 
            item.product_item.quantity--; 
            updateSubtotal(item); 
            cartItemsChanged.value = true
        } 
    }
    function updateQuantity(key, quantity) { 
        const item = cartItems.value[key]; 
        if (item && quantity >= 1) { 
            item.product_item.quantity = quantity; 
            updateSubtotal(item); 
            cartItemsChanged.value = true; 
        } 
    }
    function updateSubtotal (item){ 
        item.subtotal = price(item.product_item) * item.product_item.quantity; 
        updateOrderSummary();
    }
    function updateOrderSummary (){ 
        orderSummary.value.cart_subtotal = cartSubtotal.value; 
        orderSummary.value.cart_tax = cartTax.value; 
        orderSummary.value.new_total = newTotal.value; 
    }
    function applyCoupon (){
        formCoupon.post(route('coupon.apply'), {
            preserveScroll: true,
            onSuccess: (res) => {
                formCoupon.reset()
                // assign props again so data is behave reactive
                cartItems.value = res.props.cart_items
                orderSummary.value = res.props.order_summary
                
            },
        })
    }
    function removeCoupon (){
        formCoupon.delete(route('coupon.forget'), {
            preserveScroll: true,
            onSuccess: (res) => {
                formCoupon.reset()
                // assign props again so data is behave reactive
                cartItems.value = res.props.cart_items
                orderSummary.value = res.props.order_summary
            },
        })
    }
    function removeFromCart (key) {
        formRemoveKey.cart_item_key = key;
        delete cartItems.value[key]
        formRemoveKey.delete(route('cart.remove'), {
            preserveScroll: true,
            onSuccess: (res) => {
                formRemoveKey.reset()
                // assign props again so data is behave reactive
                cartItems.value = res.props.cart_items
                orderSummary.value = res.props.order_summary
            },
        });
    }
    function submitCartItems (){
        // Extract cart item properties and create a new object with the desired properties
        const extractCartItemProperties = Object.fromEntries(Object.entries(cartItems.value).map(([key, value]) => [key, { product_item_id: value.product_item.product_item_id, quantity: value.product_item.quantity, subtotal: value.subtotal, size_option_id: value.product_item.size_option.id}]));
        // console.log(extractCartItemProperties);
        formCartItems.cart_items = extractCartItemProperties
        formCartItems.post(route('cart.update'), {
            preserveScroll: true,
            onSuccess: (res) => {
                // assign props again so data is behave reactive
                cartItems.value = res.props.cart_items
                orderSummary.value = res.props.order_summary
            },
        });
        cartItemsChanged.value = false; 
    } 
    function handleMouseLeave() { 
        //only if items quantity changed
        if (cartItemsChanged.value) { 
            submitCartItems(); 
        } 
    }

    return { 
        incrementQuantity, decrementQuantity, updateSubtotal, updateOrderSummary, 
        applyCoupon, removeFromCart, removeCoupon, submitCartItems, handleMouseLeave,
        updateQuantity,
        formCoupon,  cartItems, orderSummary, couponCode,  
    }

})

// export const useCartStore = defineStore("cart", {
//     state: () => ({
//         cartItems: [],
//         orderSummary: { 
//             tax_rate: null, 
//             cart_subtotal: null, 
//             cart_tax: null, 
//             new_total: null, 
//             coupon: { code: null, discount: 0}
//         },
//         formCoupon: useForm({ coupon_code: null }),
//         formRemoveKey: useForm({ cart_item_key: null }),
//     }),
//     getters: {
//         price: (state) => (item) => {
//             return (item.product_item.sale_price < item.product_item.original_price && item.product_item.sale_price > 0)
//                 ? item.product_item.sale_price
//                 : item.product_item.original_price
//         },
//         cartSubtotal: state => state.cartItems.reduce((sum, item) => sum + item.subtotal, 0), 
//         cartTax: state => state.cartSubtotal * state.orderSummary.tax_rate, 
//         newTotal: state => state.cartSubtotal + state.cartTax 
//     }, 
//     actions: {
//         incrementQuantity(item) {
//             item.product_item.quantity++; this.updateSubtotal(item); 
//         }, 
//         decrementQuantity(item) {
//              if (item.product_item.quantity > 1) { 
//                 item.product_item.quantity--; 
//                 this.updateSubtotal(item); 
//             } 
//         },
//         updateSubtotal(item) { 
//             item.subtotal = this.price * item.product_item.quantity; 
//             this.updateOrderSummary(); 
//         }, 
//         updateOrderSummary() { 
//             this.orderSummary.cart_subtotal = this.cartSubtotal; 
//             this.orderSummary.cart_tax = this.cartTax; 
//             this.orderSummary.new_total = this.newTotal; 
//         }, 
//         applyCoupon(code) { 
//             if (code === 'fixed_value') { 
//                 const discount = 20; 
//                 this.orderSummary.coupon = { code, discount }; 
//                 const newSubtotal = this.orderSummary.cart_subtotal - discount; 
//                 const newTax = newSubtotal * this.orderSummary.tax_rate; 
//                 const newTotal = newSubtotal + newTax; 
//                 this.orderSummary.cart_subtotal = newSubtotal; 
//                 this.orderSummary.cart_tax = newTax; 
//                 this.orderSummary.new_total = newTotal; 
//             } 
//         } 
//     }
// })