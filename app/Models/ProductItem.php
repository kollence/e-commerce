<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductItem extends Model
{
    use HasFactory;


    protected $fillable = [ 'product_code', 'original_price', 'sale_price', 'product_id', 'color_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->hasOne(Color::class);
    }

    public function sizeOptions()
    {
        return $this->belongsToMany(SizeOption::class, 'product_item_size_option');
    }
}
