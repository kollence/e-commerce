<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_item_id',
        'order_id',
        'quantity',
        'color_id',
        'size_option_id',
        'product_code',
        'original_price',
        'sale_price',
        'total',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'product_item_id' => 'integer',
        'order_id' => 'integer',
        'color_id' => 'integer',
        'size_option_id' => 'integer',
    ];

    public function productItem(): BelongsTo
    {
        return $this->belongsTo(ProductItem::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function sizeOption(): BelongsTo
    {
        return $this->belongsTo(SizeOption::class);
    }
}
