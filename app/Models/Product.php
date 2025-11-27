<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'price',
        'description',
        'material',
        'sizes',
        'features',
        'stock',
        'color',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sizes' => 'array',
        'features' => 'array',
        'color' => 'array',
        'image' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
