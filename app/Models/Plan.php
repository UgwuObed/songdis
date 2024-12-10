<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'duration',
        'price',
        'features',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    protected $casts = [
        'features' => 'array', 
    ];
}
