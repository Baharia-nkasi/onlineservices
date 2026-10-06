<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpDeskContact extends Model
{
    protected $fillable = [
        'network',
        'phone',
        'label',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
