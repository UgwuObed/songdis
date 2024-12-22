<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $fillable = ['code', 'is_active', 'duration_days', 'times_used', 'max_uses'];

    public function uses()
    {
        return $this->hasMany(PromoCodeUse::class);
    }

    public function isValid()
    {
        return $this->is_active && $this->times_used < $this->max_uses;
    }
}