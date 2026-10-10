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
        return ServiceDocument::withTrashed()->where('service_id', $this->service_id)->orderBy('sort_order')->get()->filter(function (ServiceDocument $requirement) {
            if (! $requirement->created_at || ! $this->created_at) {
                return $requirement->is_active;
            }

            // Compare persisted timestamps explicitly so SQLite test data and
            // production database timestamps follow the same historical cutoff.
            $createdBeforeApplication = $requirement->created_at->getTimestamp() <= $this->created_at->getTimestamp();
            $changedAfterApplication = $requirement->updated_at
                && $requirement->updated_at->getTimestamp() > $this->created_at->getTimestamp();

            return $createdBeforeApplication && ($requirement->is_active || $changedAfterApplication);
        });
    }
}