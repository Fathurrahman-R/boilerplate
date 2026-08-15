<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * Permission Spatie dengan metadata tambahan supaya layak dikelola lewat UI.
 *
 * Didaftarkan di config/permission.php agar seluruh paket Spatie memakai
 * kelas ini, bukan kelas bawaannya.
 */
class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'guard_name',
        'label',
        'group',
        'description',
        'is_locked',
    ];

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'is_locked' => 'boolean',
        ]);
    }

    /** Resource key mana saja yang saat ini menunjuk permission ini. */
    public function mappings(): HasMany
    {
        return $this->hasMany(ResourcePermission::class);
    }

    public function displayName(): string
    {
        return $this->label ?: $this->name;
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($term) {
            $query->where('name', 'like', "%{$term}%")
                ->orWhere('label', 'like', "%{$term}%")
                ->orWhere('group', 'like', "%{$term}%");
        });
    }
}
