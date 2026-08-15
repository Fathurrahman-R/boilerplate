<?php

namespace App\Models;

use App\Enums\ResourceAction;
use App\Support\Resources\ResourceKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Satu baris pemetaan: resource key → permission.
 *
 * Selalu dibuat lewat ResourceManager, tidak pernah langsung, supaya cache
 * permission dan cache peta ikut dibersihkan.
 */
class ResourcePermission extends Model
{
    protected $fillable = [
        'resource_id',
        'action',
        'permission_id',
        'is_locked',
    ];

    protected function casts(): array
    {
        return [
            'action' => ResourceAction::class,
            'is_locked' => 'boolean',
        ];
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

    public function key(): ResourceKey
    {
        return ResourceKey::make($this->resource->key, $this->action);
    }

    public function isMapped(): bool
    {
        return $this->permission_id !== null;
    }
}
