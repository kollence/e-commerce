<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PercentageCoupon extends Model
{
    use HasFactory;

    protected $fillable = ['percentage'];

    public function discount($amount)
    {
        return $amount * $this->percentage;
    }
}
