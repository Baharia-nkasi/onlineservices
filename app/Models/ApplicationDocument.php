<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Determine whether the actual uploaded file is available for viewing.
     */
    public function hasAvailableFile(): bool
    {
        return $this->file_content !== null
            || ($this->file_path
                && ! str_starts_with($this->file_path, 'database://')
                && Storage::disk('local')->exists($this->file_path));
    }
}
