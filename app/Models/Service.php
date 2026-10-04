<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'government_fee',
        'service_fee',
        'is_active',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(ServiceDocument::class)
            ->orderBy('sort_order');
    }
}