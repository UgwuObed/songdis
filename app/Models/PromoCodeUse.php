<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCodeUse extends Model
{
    protected $fillable = ['user_id', 'promo_code_id', 'expires_at'];

    protected $dates = ['expires_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }
}
