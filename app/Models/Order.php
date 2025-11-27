<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_id',
        'product_id',
        'amount',
        'total',
        'status',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    // Auto-calculate total before saving
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($order) {
            if ($order->product_id && $order->amount) {
                $product = Product::find($order->product_id);
                if ($product) {
                    $order->total = $product->price * $order->amount;
                }
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
