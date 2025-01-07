<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session;
use App\Exceptions\PaymentFailedException;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class StripePaymentService
{
    public function charge(Request $request)
    {
        // 1. Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'shipping_address.country' => 'required|string|size:2',
            'shipping_address.city' => 'required|string',
            'shipping_address.street_and_number' => 'required|string',
            'shipping_address.zip_code' => 'required|string',
            'shipping_address.phone_1' => 'required|string',
            'shipping_address.phone_2' => 'nullable|string',
            'notes' => 'nullable|string',
            'shipping_method' => 'required|string|in:express,standard',
            'payment_method' => 'required|string|in:card',
            'payment_method_id' => 'required|string|starts_with:pm_',
            'amount' => 'required|numeric|min:0.50'
        ]);

        try {
            // 2. Initialize Stripe with your secret key
            Stripe::setApiKey(config('services.stripe.secret'));

            // 3. Create a Payment Intent
            $paymentIntent = PaymentIntent::create([
                'amount' => (int)($validated['amount'] * 100), // Convert to cents
                'currency' => 'usd',
                'payment_method' => $validated['payment_method_id'],
                'confirmation_method' => 'manual',
                'confirm' => true,
                'metadata' => [
                    'customer_name' => $validated['name'],
                    'customer_email' => $validated['email'],
                ],
                'shipping' => [
                    'name' => $validated['name'],
                    'address' => [
                        'line1' => $validated['shipping_address']['street_and_number'],
                        'city' => $validated['shipping_address']['city'],
                        'postal_code' => $validated['shipping_address']['zip_code'],
                        'country' => $validated['shipping_address']['country'],
                    ],
                    'phone' => $validated['shipping_address']['phone_1'],
                ],
            ]);

            // 4. Begin database transaction
            DB::beginTransaction();

            // 5. Create order record
            $order = Order::create([
                'stripe_payment_intent_id' => $paymentIntent->id,
                'status' => $paymentIntent->status,
                'amount' => $validated['amount'],
                'currency' => 'usd',
                'customer_name' => $validated['name'],
                'customer_email' => $validated['email'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_method' => $validated['shipping_method'],
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();

            // 6. Return success response
            return response()->json([
                'success' => true,
                'order' => $order,
                'client_secret' => $paymentIntent->client_secret,
            ]);

        } catch (\Stripe\Exception\CardException $e) {
            DB::rollBack();
            return response()->json([
                'error' => [
                    'message' => $e->getMessage(),
                    'type' => 'card_error',
                ]
            ], 400);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => [
                    'message' => 'An error occurred while processing your payment.',
                    'type' => 'server_error',
                ]
            ], 500);
        }
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