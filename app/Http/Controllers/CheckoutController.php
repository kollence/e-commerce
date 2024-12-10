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
        // // Validate the request 
        $request->validate([ 
            'payment_method_id' => 'required|string', 
            'amount' => 'required|numeric|min:1', 
            // 'shipping_address.street_and_number' => 'required|string', 
            // 'billing_address.street_and_number' => 'nullable|string' 
        ]);
        $paymentMethodId = $request->payment_method_id;
        $amount = $request->amount * 100;

        // DB::beginTransaction();

        try {
            // $user = new User;
            (new User)->charge($amount, $paymentMethodId, ['return_url' => route('checkout.success')]);
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
        } catch (\Exception $e) { 
            // Rollback the transaction 
            // DB::rollBack(); 
            return inertia('Checkout/Canceled', ['error' => $e->getMessage()]);
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
