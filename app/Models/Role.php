<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'label',
        'description',
        'is_locked',
    ];

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'is_locked' => 'boolean',
        ]);
    }

    public function isSuperAdmin(): bool
    {
        return $this->name === config('resources.super_admin_role');
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
                ->orWhere('label', 'like', "%{$term}%");
        });
    }
}
