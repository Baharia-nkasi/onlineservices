<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    protected $fillable = [
        'user_id',
        'service_id',
        'status',
        'notes',
        'approval_remark',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ApplicationDocument::class);
    }

    /**
     * Requirements that applied when this application was created.
     *
     * A requirement created after the application is ignored, while a requirement
     * deactivated after the application was created remains part of its history.
     */
    public function effectiveServiceRequirements()
    {
        return $this->service->documents->filter(function (ServiceDocument $requirement) {
            if (! $requirement->created_at || ! $this->created_at) {
                return $requirement->is_active;
            }

            return $requirement->created_at <= $this->created_at
                && ($requirement->is_active || $requirement->updated_at > $this->created_at);
        });
    }
}