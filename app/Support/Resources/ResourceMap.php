<?php

namespace App\Support\Resources;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * Peta "resource key → nama permission", dibaca dari tabel resources dan
 * resource_permissions.
 *
 * Dibaca berkali-kali per request (setiap tombol, setiap item menu), jadi
 * hasilnya disimpan di cache aplikasi sekaligus di memori selama request
 * berlangsung. ResourceManager membersihkan keduanya setiap kali ada
 * perubahan resource, permission, atau pemetaan.
 */
class ResourceMap
{
    /**
     * Nilai null berarti key terdaftar tapi belum menunjuk permission apa pun.
     *
     * @var array<string, string|null>|null
     */
    private ?array $map = null;

    /** @return array<string, string|null> */
    public function all(): array
    {
        return $this->map ??= $this->remember();
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->all());
    }

    /** Nama permission untuk sebuah key, atau null bila belum dipetakan. */
    public function permissionFor(string $key): ?string
    {
        return $this->all()[$key] ?? null;
    }

    /** @return array<int, string> key yang terdaftar tapi belum punya permission. */
    public function unmappedKeys(): array
    {
        return array_keys(array_filter($this->all(), static fn (?string $name): bool => $name === null));
    }

    public function flush(): void
    {
        $this->map = null;

        Cache::forget($this->cacheKey());
    }

    /** @return array<string, string|null> */
    private function remember(): array
    {
        $ttl = config('resources.cache.ttl');

        $loader = fn (): array => $this->load();

        try {
            return $ttl === null
                ? Cache::rememberForever($this->cacheKey(), $loader)
                : Cache::remember($this->cacheKey(), $ttl, $loader);
        } catch (Throwable) {
            // Cache belum siap (misalnya saat migrate pertama kali membuat
            // tabel cache). Membaca langsung dari database tetap benar.
            return $this->load();
        }
    }

    /** @return array<string, string|null> */
    private function load(): array
    {
        if (! $this->tablesReady()) {
            return [];
        }

        $permissions = config('permission.table_names.permissions', 'permissions');

        try {
            $rows = DB::table('resource_permissions')
                ->join('resources', 'resources.id', '=', 'resource_permissions.resource_id')
                ->leftJoin($permissions, "{$permissions}.id", '=', 'resource_permissions.permission_id')
                ->select([
                    'resources.key as resource_key',
                    'resource_permissions.action as action',
                    "{$permissions}.name as permission_name",
                ])
                ->get();
        } catch (QueryException) {
            return [];
        }

        $map = [];

        foreach ($rows as $row) {
            $map["{$row->resource_key}.{$row->action}"] = $row->permission_name;
        }

        return $map;
    }

    private function tablesReady(): bool
    {
        try {
            return Schema::hasTable('resources') && Schema::hasTable('resource_permissions');
        } catch (Throwable) {
            return false;
        }
    }

    private function cacheKey(): string
    {
        return config('resources.cache.key', 'resources.map');
    }
}
