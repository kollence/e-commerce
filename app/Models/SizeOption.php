<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeOption extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sort_order'];

    public function productItems()
    {
        return $this->belongsToMany(ProductItem::class, 'product_item_size_option');
    }

    public function sizeCategory()
    {
        return $this->belongsTo(SizeCategory::class);
    }
}
