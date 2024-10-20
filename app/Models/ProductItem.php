<?php

namespace App\Models;

use App\Models\Traits\Imageable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductItem extends Model
{
    use HasFactory, Imageable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'color_id',
        'product_code',
        'original_price',
        'sale_price',
        // 'price'
    ];
    protected $appends = ['price'];
    protected function originalPrice(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => (int)($value * 100),
        );
    }
    protected function salePrice(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => (int)($value * 100),
        );
    }
    // price attribute based on sale_price and original_price (if sale_price is less than original_price)
    protected function price(): Attribute
    {
        if($this->sale_price > 0 && $this->sale_price < $this->original_price) {
            return Attribute::make(
                get: fn () => $this->sale_price,
            );
        } else {
            return Attribute::make(
                get: fn () => $this->original_price,
            );
        }
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function sizeOptions(): BelongsToMany
    {
        return $this->belongsToMany(SizeOption::class)->withPivot('id','in_stock', 'sku');
    }

    public function getSizeOptionById($sizeOptionId)
    {
        return $this->sizeOptions()
                    ->where('size_options.id', $sizeOptionId)
                    ->withPivot('id', 'in_stock', 'sku')
                    ->first();
    }
}
