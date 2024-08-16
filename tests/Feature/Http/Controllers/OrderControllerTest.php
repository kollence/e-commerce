<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\OrderController
 */
final class OrderControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $orders = Order::factory()->count(3)->create();

        $response = $this->get(route('orders.index'));

        $response->assertOk();
        $response->assertViewIs('order.index');
        $response->assertViewHas('orders');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('orders.create'));

        $response->assertOk();
        $response->assertViewIs('order.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\OrderController::class,
            'store',
            \App\Http\Requests\OrderStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $user = User::factory()->create();
        $status = $this->faker->randomElement(/** enum_attributes **/);
        $total_price = $this->faker->numberBetween(-10000, 10000);
        $shipping_price = $this->faker->numberBetween(-10000, 10000);
        $shipping_method = $this->faker->word();
        $payment_method = $this->faker->word();
        $payment_status = $this->faker->word();
        $billing_address = $this->faker->text();
        $shipping_address = $this->faker->text();
        $currency = $this->faker->word();

        $response = $this->post(route('orders.store'), [
            'user_id' => $user->id,
            'status' => $status,
            'total_price' => $total_price,
            'shipping_price' => $shipping_price,
            'shipping_method' => $shipping_method,
            'payment_method' => $payment_method,
            'payment_status' => $payment_status,
            'billing_address' => $billing_address,
            'shipping_address' => $shipping_address,
            'currency' => $currency,
        ]);

        $orders = Order::query()
            ->where('user_id', $user->id)
            ->where('status', $status)
            ->where('total_price', $total_price)
            ->where('shipping_price', $shipping_price)
            ->where('shipping_method', $shipping_method)
            ->where('payment_method', $payment_method)
            ->where('payment_status', $payment_status)
            ->where('billing_address', $billing_address)
            ->where('shipping_address', $shipping_address)
            ->where('currency', $currency)
            ->get();
        $this->assertCount(1, $orders);
        $order = $orders->first();

        $response->assertRedirect(route('orders.index'));
        $response->assertSessionHas('order.id', $order->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $order = Order::factory()->create();

        $response = $this->get(route('orders.show', $order));

        $response->assertOk();
        $response->assertViewIs('order.show');
        $response->assertViewHas('order');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $order = Order::factory()->create();

        $response = $this->get(route('orders.edit', $order));

        $response->assertOk();
        $response->assertViewIs('order.edit');
        $response->assertViewHas('order');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\OrderController::class,
            'update',
            \App\Http\Requests\OrderUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $order = Order::factory()->create();
        $user = User::factory()->create();
        $status = $this->faker->randomElement(/** enum_attributes **/);
        $total_price = $this->faker->numberBetween(-10000, 10000);
        $shipping_price = $this->faker->numberBetween(-10000, 10000);
        $shipping_method = $this->faker->word();
        $payment_method = $this->faker->word();
        $payment_status = $this->faker->word();
        $billing_address = $this->faker->text();
        $shipping_address = $this->faker->text();
        $currency = $this->faker->word();

        $response = $this->put(route('orders.update', $order), [
            'user_id' => $user->id,
            'status' => $status,
            'total_price' => $total_price,
            'shipping_price' => $shipping_price,
            'shipping_method' => $shipping_method,
            'payment_method' => $payment_method,
            'payment_status' => $payment_status,
            'billing_address' => $billing_address,
            'shipping_address' => $shipping_address,
            'currency' => $currency,
        ]);

        $order->refresh();

        $response->assertRedirect(route('orders.index'));
        $response->assertSessionHas('order.id', $order->id);

        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals($status, $order->status);
        $this->assertEquals($total_price, $order->total_price);
        $this->assertEquals($shipping_price, $order->shipping_price);
        $this->assertEquals($shipping_method, $order->shipping_method);
        $this->assertEquals($payment_method, $order->payment_method);
        $this->assertEquals($payment_status, $order->payment_status);
        $this->assertEquals($billing_address, $order->billing_address);
        $this->assertEquals($shipping_address, $order->shipping_address);
        $this->assertEquals($currency, $order->currency);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $order = Order::factory()->create();

        $response = $this->delete(route('orders.destroy', $order));

        $response->assertRedirect(route('orders.index'));

        $this->assertModelMissing($order);
    }
}
