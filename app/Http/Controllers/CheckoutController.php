<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderSummary = $this->cartService->getCartSummary();
        return inertia('Checkout/Index',[
            'order_summary' => $orderSummary
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $user = $request->user(); 
        // Validate the request 
        $request->validate([ 
            'payment_method_id' => 'required|string', 
            'amount' => 'required|numeric|min:1', 
            'name' => 'required|string|min:3|max:50', 
            'email' => 'required|email', 
            'shipping_address.street_and_number' => 'required|string', 
            'shipping_address.city' => 'required|string', 
            // 'shipping_address.state' => 'required|string', 
            'shipping_address.country' => 'required|string', 
            'shipping_address.zip_code' => 'required|string', 
            'shipping_address.phone_1' => 'required|string', 
            'shipping_address.phone_2' => 'nullable|string', 
            'shipping_address.default' => 'nullable|boolean', 
            'billing_address.street_and_number' => 'nullable|string', 
            'billing_address.city' => 'nullable|string', 
            // 'billing_address.state' => 'nullable|string', 
            'billing_address.country' => 'nullable|string', 
            'billing_address.zip_code' => 'nullable|string', 
            'billing_address.phone_1' => 'nullable|string', 
            'billing_address.phone_2' => 'nullable|string', 
            'billing_address.default' => 'nullable|boolean',
        ]);

        $paymentMethodId = $request->payment_method_id;
        $amount = $request->amount * 100;

        $getCartItems = $this->cartService->getCartItems();
        $cartItems = collect($getCartItems)->map(function($item){
            return 'Product Code: '.$item['product_item']['product_code'].','.
                'Product Name: '.$item['product']['name'].','.
                'Product Quantity: '.$item['product_item']['quantity'];
                'Product SKU: '.$item['product_item']['sku'];
        })->values()->toJson();
        // DB::beginTransaction();
        try {
        // Options for the charge that will use Stripe API
            $options = [
                'return_url' => route('checkout.success'),
                'statement_descriptor' => 'E-commerce Test site',
                'receipt_email' => $request->email,
                'description' => 'One time single charge',
                'metadata' => [ // metadata will be shown in Stripe Transactions page
                    'Confirmation #' => '1234567890',
                    'cart_items' => $cartItems,
                    'count_cart_items' => collect($this->cartService->cartItems())->count(),
                ]
            ];
            // Simple Charge https://laravel.com/docs/11.x/billing#single-charges (Stripe doc says charge() is deprecated)
            (new User)->charge($amount, $paymentMethodId, $options);
        //     // Charge the user 
        //     $options = ['return_url' => route('checkout.success')]; 
        //     $user->charge($amount, $paymentMethodId, $options);
        //     // Store or update shipping address 
        //     $shippingAddress = Address::firstOrNew( [ 
        //         'user_id' => $user->id, 
        //         'street_and_number' => $request->shipping_address['street_and_number'], 
        //     ], $request->shipping_address );

        //     if (!$shippingAddress->exists || $shippingAddress->isDirty()) { 
        //         $shippingAddress->type = 'shipping';
        //         $shippingAddress->fill($request->shipping_address); 
        //         $shippingAddress->save(); 
        //     } 
        //     // Store or update billing address if provided 
        //     $billingAddressId = null; 
        //     if ($request->has('billing_address')) { 
        //         $billingAddress = Address::firstOrNew( [ 
        //             'user_id' => $user->id, 
        //             'type' => 'billing', 
        //             'street_and_number' => $request->billing_address['street_and_number'], 
        //         ], $request->billing_address );

        //         if (!$billingAddress->exists || $billingAddress->isDirty()) { 
        //             $billingAddress->type = 'billing';
        //             $billingAddress->fill($request->billing_address); 
        //             $billingAddress->save(); 
        //         } 
        //         $billingAddressId = $billingAddress->id; 
        //     } 
        //     // Create the order 
        //     Order::create([ 
        //         'user_id' => $user->id, 
        //         'total_price' => $amount, 
        //         'status' => 'completed', 
        //         'payment_method' => $request->payment_method, 
        //         'shipping_method' => $request->shipping_method, 
        //         'shipping_price' => 666, 
        //         'currency' => 'USD', 
        //         'shipping_address_id' => $shippingAddress->id, 
        //         'billing_address_id' => $billingAddressId, 
        //     ]); 
        //     // Commit the transaction 
        //     DB::commit(); 
        //     // Respond with an Inertia response 
            return inertia('Checkout/Success', ['message' => 'Payment successful']); 
        } catch (\Stripe\Exception\CardException $e) { 
            // Rollback the transaction 
            // DB::rollBack(); 
            return back()->withErrors(['error' => $e->getMessage()]);
            // return inertia('Checkout/Canceled', ['error' => $e->getMessage()]);
        } 
        catch (\Exception $e) { 
        //     // Rollback the transaction 
        //     // DB::rollBack(); 
        //     // Return back with error message 
            return back()->withErrors(['error' => $e->getMessage()]);
        //     // return inertia('Checkout/Canceled', ['error' => $e->getMessage()]);
        }
    }

    public function success(Request $request)
    {
        // dd('success '.$request->all());
        return inertia('Checkout/Success');
    }

    public function canceled(Request $request)
    {
        // dd('canceled '.$request->all());
        return inertia('Checkout/Canceled');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
