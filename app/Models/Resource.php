<?php

namespace App\Models;

use App\Enums\ResourceAction;
use App\Support\Resources\ResourceKey;
use Database\Factories\ResourceFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property-read Collection<int, ResourcePermission> $mappings
 */
class Resource extends Model
{
    /** @use HasFactory<ResourceFactory> */
    use HasFactory;

    protected $fillable = [
        'key',
        'label',
        'group',
        'description',
        'is_locked',
    ];

    protected function casts(): array
    {
        return [
            'is_locked' => 'boolean',
        ];
    }

    public function mappings(): HasMany
    {
        return $this->hasMany(ResourcePermission::class);
    }

    /**
     * Aksi yang saat ini terdaftar untuk resource ini.
     *
     * @return Collection<int, ResourceAction>
     */
    public function actions(): Collection
    {
        return $this->mappings
            ->map(fn (ResourcePermission $mapping): ?ResourceAction => $mapping->action)
            ->filter()
            ->values();
    }

    public function keyFor(ResourceAction $action): ResourceKey
    {
        return ResourceKey::make($this->key, $action);
    }

    /** @return array<int, string> */
    public function keys(): array
    {
        return $this->actions()
            ->map(fn (ResourceAction $action): string => $this->keyFor($action)->value())
            ->all();
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($term) {
            $query->where('key', 'like', "%{$term}%")
                ->orWhere('label', 'like', "%{$term}%")
                ->orWhere('group', 'like', "%{$term}%");
        });
    }
}
