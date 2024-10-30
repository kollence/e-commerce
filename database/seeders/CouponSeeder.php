<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\FixedValueCoupon;
use App\Models\PercentageCoupon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $percent = PercentageCoupon::create([
            'percentage' => 0.1,
        ]);

        $fixedValue = FixedValueCoupon::create([
            'value' => 2000,
        ]);

        Coupon::create([
            'code' => 'PERCENT',
            'couponable_id' => $percent->id,
            'couponable_type' => PercentageCoupon::class,
            'expires_at' => now()->addDays(30)
        ]);

        Coupon::create([
            'code' => 'FIXED_VALUE',
            'couponable_id' => $fixedValue->id,
            'couponable_type' => FixedValueCoupon::class,
            'expires_at' => now()->addDays(3)
        ]);
    }
}
