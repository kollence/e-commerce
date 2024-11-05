<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    }

    /**
     * Apply coupon code
     */
    public function apply(Request $request)
    {   // if request is received but coupon code is empty (NULL)
        if(!$request->coupon_code) return redirect()->back()->withErrors("You need to enter coupon code first.", "error");

        $couponCode = Coupon::findByCode($request->coupon_code);
        if (!$couponCode) {                           //VALUE                               // KEY
            return redirect()->back()->withErrors("Coupon code is invalid. Try again.", "error");
        }
        $coupon = $couponCode->couponable;
        $cart = new Cart();
        $subtotal = $cart->totalSubtotal();
        // dd($subtotal);
        $discount = $coupon->discount($subtotal);
        $request->session()->put("coupon", [
            "code" => $couponCode->code,
            "discount" => $discount,
        ]);
        return redirect()->back()->with("message", "Coupon applied successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(Coupon $coupon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function forget(Coupon $coupon)
    {
        session()->forget("coupon");
        return redirect()->back()->with("message", "Coupon removed successfully");
    }
}
