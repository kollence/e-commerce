<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface PaymentGatewayContract {

    public function charge(Request $request);
    
}