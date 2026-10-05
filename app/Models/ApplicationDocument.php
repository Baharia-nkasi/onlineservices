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


    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Read PostgreSQL bytea content safely when PDO returns it as a stream.
     */
    public function binaryContent(): ?string
    {
        $value = $this->getRawOriginal('file_content');

        if ($value === null) {
            return null;
        }

        if (is_resource($value)) {
            $value = stream_get_contents($value);
        }

        return is_string($value) ? $value : null;
    }

    /**
     * Determine whether the actual uploaded file is available for viewing.
     */
    public function hasAvailableFile(): bool
    {
        return $this->binaryContent() !== null
            || ($this->file_path
                && ! str_starts_with($this->file_path, 'database://')
                && Storage::disk('local')->exists($this->file_path));
    }
}
