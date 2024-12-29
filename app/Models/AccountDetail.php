<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountDetail extends Model
{
    protected $fillable = ['user_id', 'account_number', 'bank_name', 'account_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
