<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Catalog extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'parent_id',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Catalog::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Catalog::class, 'parent_id')->with('children');
    }
}
