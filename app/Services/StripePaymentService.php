<?php

namespace App\Services;

use App\Contracts\PaymentGatewayContract;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session;
use App\Exceptions\PaymentFailedException;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class StripePaymentService implements PaymentGatewayContract
{

    public function __construct(protected $getCartItems) 
    {}

    public function charge(Request $request)
    {
            $paymentMethodId = $request->payment_method_id;
            $amount = $request->amount * 100;
            $countCartItems = 0;
            $cartItems = collect($this->getCartItems)->map(function($item) use (&$countCartItems){ // Metadata values can have up to 500 characters
                $countCartItems++;
                return '{ product_sku: '.$item['product_item']['sku'] .', '. 'product_qty: '.$item['product_item']['quantity'].'}';
            })->values()->toJson();
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

            // 5. Create order record
            // $order = Order::create([
            //     'stripe_payment_intent_id' => $paymentIntent->id,
            //     'status' => $paymentIntent->status,
            //     'amount' => $request['amount'],
            //     'currency' => 'usd',
            //     'customer_name' => $request['name'],
            //     'customer_email' => $request['email'],
            //     'shipping_address' => $request['shipping_address'],
            //     'shipping_method' => $request['shipping_method'],
            //     'notes' => $request['notes'] ?? null,
            // ]);

    }

    // /**
    //  * Create a payment intent.
    //  *
    //  * @param  float  $amount
    //  * @param  string  $currency
    //  * @return \Stripe\PaymentIntent
    //  */
    // public function createPaymentIntent(float $amount, string $currency): PaymentIntent
    // {
    //     Stripe::setApiKey(config('services.stripe.secret'));

    //     return PaymentIntent::create([
    //         'amount' => $amount * 100,
    //         'currency' => $currency,
    //     ]);
    // }

    // /**
    //  * Create a checkout session.
    //  * @param  string  $paymentIntentId
    //  * @return \Stripe\Checkout\Session
    //  */
    // public function createCheckoutSession(string $paymentIntentId): Session
    // {
    //     Stripe::setApiKey(config('services.stripe.secret'));

    //     return Session::create([
    //         'payment_method_types' => ['card'],
    //         'line_items' => [
    //             [ 'price_data' => [
    //                 'currency' => 'usd',
    //                 'product_data' => [
    //                     'name' => 'Stubborn Attachments',
    //                 ],
    //                 'unit_amount' => 2000,
    //             ],
    //             'quantity' => 1,
    //             ],
    //         ],
    //         'mode' => 'payment',
    //         'success_url' => 'https://example.com/success',
    //         'cancel_url' => 'https://example.com/cancel',
    //         'payment_intent' => $paymentIntentId,
    //     ]);
    // }

    // /**
    //  * Confirm a payment intent.
    //  *
    //  * @param  string  $paymentIntentId
    //  * @return \Stripe\PaymentIntent
    //  */
    // public function confirmPaymentIntent(string $paymentIntentId): PaymentIntent
    // {
    //     Stripe::setApiKey(config('services.stripe.secret'));

    //     return PaymentIntent::retrieve($paymentIntentId)->confirm();
    // }

    // /**
    //  * Handle a failed payment.
    //  * @param  string  $paymentIntentId
    //  * @return void
    //  * */
    // public function handleFailedPayment(string $paymentIntentId): void
    // {
    //     // throw new PaymentFailedException('Payment failed.'); // TODO: Implement this method
    // }
}