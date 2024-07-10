<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function sizeOptions()
    {
        return $this->hasMany(SizeOption::class);
    }
}
