<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationDocument extends Model
{
    protected $fillable = [
        'application_id',
        'document_name',
        'file_name',
        'file_path',
        'file_content',
        'file_type',
        'file_size',
        'status',
        'notes',
    ];

    protected $casts = [
        'file_content' => 'string',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
