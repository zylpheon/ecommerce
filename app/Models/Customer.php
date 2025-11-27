<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'total_orders',
        'total_spent',
    ];

    protected $casts = [
        'total_spent' => 'decimal:2',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
