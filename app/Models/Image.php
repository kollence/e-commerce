<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filename',
        'url',
        'is_active',
        'sort_order',
        'main',
        'imageable_id',
        'imageable_type',
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'main' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
