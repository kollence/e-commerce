<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'couponable_id', 'couponable_type'];

        /**
     * Get the parent couponable model (user or post).
     */
    public function couponable(): MorphTo
    {
        return $this->morphTo();
    }

    public function isExpired(): bool
    {
        return $this->expires_at < now();
    }

    public static function findByCode(string $code)
    {
        return self::where('code', $code)->first();
    }

    public function discount($amount): float
    {
        return $this->couponable()->discount($amount);
    }
}
