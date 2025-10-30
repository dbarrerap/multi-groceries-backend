<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'brand',
        'unit_of_measure',
        'barcode',
        'category_id',
        'unit_of_measure_id',
    ];

    public function shoppingItems(): HasMany
    {
        return $this->hasMany(ShoppingItem::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Catalog::class, 'category_id');
    }

    public function unitOfMeasure(): BelongsTo
    {
        return $this->belongsTo(Catalog::class, 'unit_of_measure_id');
    }
}
