<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'cart_count' =>  function() use($request) {
                $cart =  $request->session()->get('cart'); // Retrieve the 'cart' from the session

            // Decode JSON to a PHP array (if stored as a JSON string)
                if (is_string($cart)) {
                    $cart = json_decode($cart, true); // Convert JSON string to an array
                }

                // Count the number of items in the cart
                $cartItemCount = is_array($cart) ? count($cart) : 0;
                return $cartItemCount;
            },
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
            ],
        ]);
    }
}
