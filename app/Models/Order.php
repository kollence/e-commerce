<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'shipping_price',
        'shipping_method',
        'payment_method',
        'payment_status',
        'status',
        'currency',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems() : HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
        
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
