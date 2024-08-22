<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'is_active',
        'parent_category_id',
        'size_category_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function sizeCategory(): BelongsTo
    {
        return $this->belongsTo(SizeCategory::class);
    }
    
    // Parents < > children relationships
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_category_id');
    }
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }
    public function allParents()
    {
        return $this->parent()->with('allParents');
    }

}
