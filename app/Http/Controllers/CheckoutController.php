<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\PaymentIntent;
use Stripe\Stripe;

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
        // dd($request->all());
        $paymentMethodId = $request->payment_method_id;
        $amount = $request->amount * 100;
        $countCartItems = 0;
        $getCartItems = $this->cartService->getCartItems();
        $cartItems = collect($getCartItems)->map(function($item) use (&$countCartItems){ // Metadata values can have up to 500 characters
            $countCartItems++;
            return '{ product_sku: '.$item['product_item']['sku'] .', '. 'product_qty: '.$item['product_item']['quantity'].'}';
        })->values()->toJson();

        try {
            // 2. Initialize Stripe with your secret key
            Stripe::setApiKey(config('services.stripe.secret'));

            // 3. Create a Payment Intent
            $paymentIntent = PaymentIntent::create([
                'payment_method' => $paymentMethodId,
                'amount' => (int) $amount, // Convert to cents
                'currency' => 'usd',
                // 'description' => 'Payment for order #'.$order->id,
                'confirmation_method' => 'manual',
                'confirm' => true,
                'return_url' => route('checkout.success'), // Add return URL for 3D Secure
                'metadata' => [
                    'customer_name' => $request['name'],
                    'customer_email' => $request['email'],
                    'cart_items' => $cartItems, // Metadata values can have up to 500 characters
                    'count_cart_items' => $countCartItems,
                ],
                'shipping' => [
                    'name' => $request['name'],
                    'address' => [
                        'line1' => $request['shipping_address']['street_and_number'],
                        'city' => $request['shipping_address']['city'],
                        'postal_code' => $request['shipping_address']['zip_code'],
                        'country' => $request['shipping_address']['country'],
                    ],
                    'phone' => $request['shipping_address']['phone_1'],
                ],
            ]);

            // // 4. Begin database transaction
            // DB::beginTransaction();

            // // 5. Create order record
            // $order = Order::create([
            //     'order_number' => $paymentIntent->id,
            //     'status' => $paymentIntent->status,
            //     'total_price' => $amount,
            //     'currency' => 'USD',
            //     'customer_name' => $request->name,
            //     'customer_email' => $request->email,
            //     'payment_method' => $request->payment_method, 
            //     'shipping_method' => $request->shipping_method,
            //     'shipping_price' => 666,
            //     'notes' => $request->notes ?? null,
            // ]);
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
            return back()->withErrors(['error' => $e->getMessage()])->withStatusCode(400); // card error with status code 400
            // return inertia('Checkout/Canceled', ['error' => $e->getMessage()]);
        } 
        catch (\Exception $e) { 
        //     // Rollback the transaction 
        //     // DB::rollBack(); 
        //     // Return back with error message 
            return back()->withErrors(['error' => $e->getMessage()])->withStatusCode(500); // server error with status code 500
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
