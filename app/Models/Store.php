<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
    ];

    public function shoppingRecords(): HasMany
    {
        return $this->hasMany(ShoppingRecord::class);
    }
}
