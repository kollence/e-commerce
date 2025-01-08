<?php

namespace App\Services;

use App\Contracts\PaymentGatewayContract;
use Illuminate\Http\Request;

class PaypalPaymentService implements PaymentGatewayContract
{

    public function __construct(protected $getCartItems)
    {}

    public function charge(Request $request)
    {
        dd($this->getCartItems);
    }
}