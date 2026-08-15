<?php

namespace App\Support\Resources;

use App\Enums\ResourceAction;
use App\Models\Permission;
use App\Models\Resource;
use App\Models\ResourcePermission;
use App\Models\Role;
use App\Support\Resources\Exceptions\LockedRecord;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

/**
 * Semua penulisan yang menyangkut resource, permission, dan pemetaannya lewat
 * kelas ini.
 *
 * Alasannya satu: setiap perubahan harus dibungkus transaksi dan diakhiri
 * pembersihan dua cache sekaligus (cache permission milik Spatie dan cache
 * peta resource). Kalau controller menulis sendiri-sendiri lewat Eloquent,
 * cepat atau lambat ada jalur yang lupa membersihkan salah satunya dan
 * pengguna melihat izin basi.
 */
class ResourceManager
{
    public function __construct(private readonly ResourceMap $map) {}

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<int, ResourceAction|string>  $actions
     */
    public function createResource(array $attributes, array $actions = []): Resource
    {
        return $this->transaction(function () use ($attributes, $actions): Resource {
            $resource = Resource::create($attributes);

            $this->syncActions($resource, $actions);

            return $resource->fresh(['mappings.permission']);
        });
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<int, ResourceAction|string>|null  $actions  null berarti daftar aksi tidak diubah.
     */
    public function updateResource(Resource $resource, array $attributes, ?array $actions = null): Resource
    {
        return $this->transaction(function () use ($resource, $attributes, $actions): Resource {
            $resource->update($attributes);

            if ($actions !== null) {
                $this->syncActions($resource, $actions);
            }

            return $resource->fresh(['mappings.permission']);
        });
    }

    public function deleteResource(Resource $resource): void
    {
        if ($resource->is_locked) {
            throw LockedRecord::resource($resource->key);
        }

        $this->transaction(function () use ($resource): void {
            // Pemetaannya ikut terhapus lewat cascade di level database.
            // Permission-nya sengaja dibiarkan hidup: bisa jadi masih dipakai
            // resource key lain, dan kalau pun tidak, penghapusannya adalah
            // keputusan sadar lewat modul Permission.
            $resource->delete();
        });
    }

    /**
     * Menyamakan daftar aksi sebuah resource dengan daftar yang diberikan.
     *
     * Aksi baru langsung dibuatkan permission bernama sama dengan key-nya lalu
     * dipetakan. Aksi yang hilang dari daftar akan dihapus pemetaannya.
     *
     * @param  array<int, ResourceAction|string>  $actions
     */
    public function syncActions(Resource $resource, array $actions): void
    {
        $wanted = collect($actions)
            ->map(fn (ResourceAction|string $action): ResourceAction => $action instanceof ResourceAction
                ? $action
                : ResourceAction::from($action))
            ->unique()
            ->values();

        $this->transaction(function () use ($resource, $wanted): void {
            $existing = $resource->mappings()->get()->keyBy(fn (ResourcePermission $m): string => $m->action->value);

            foreach ($wanted as $action) {
                if ($existing->has($action->value)) {
                    continue;
                }

                $key = ResourceKey::make($resource->key, $action);

                $resource->mappings()->create([
                    'action' => $action->value,
                    'permission_id' => $this->permissionNamed($key->value(), $resource)->getKey(),
                ]);
            }

            $removed = $existing->reject(
                fn (ResourcePermission $mapping): bool => $wanted->contains($mapping->action)
            );

            foreach ($removed as $mapping) {
                if ($mapping->is_locked) {
                    throw LockedRecord::mapping($mapping->key()->value());
                }

                $mapping->delete();
            }
        });
    }

    /** Mengarahkan sebuah key ke permission lain, atau melepasnya bila null. */
    public function remap(ResourcePermission $mapping, ?Permission $permission): ResourcePermission
    {
        if ($mapping->is_locked) {
            throw LockedRecord::mapping($mapping->key()->value());
        }

        return $this->transaction(function () use ($mapping, $permission): ResourcePermission {
            $mapping->update(['permission_id' => $permission?->getKey()]);

            return $mapping->fresh('permission');
        });
    }

    /**
     * Membuatkan permission untuk setiap key yang belum terpetakan.
     *
     * @return int jumlah key yang berhasil dipetakan.
     */
    public function autoMapMissing(): int
    {
        return $this->transaction(function (): int {
            $mappings = ResourcePermission::query()
                ->whereNull('permission_id')
                ->with('resource')
                ->get();

            foreach ($mappings as $mapping) {
                $key = ResourceKey::make($mapping->resource->key, $mapping->action);

                $mapping->update([
                    'permission_id' => $this->permissionNamed($key->value(), $mapping->resource)->getKey(),
                ]);
            }

            return $mappings->count();
        });
    }

    /** @param  array<string, mixed>  $attributes */
    public function createPermission(array $attributes): Permission
    {
        return $this->transaction(fn (): Permission => Permission::create($attributes + [
            'guard_name' => config('auth.defaults.guard', 'web'),
        ]));
    }

    /** @param  array<string, mixed>  $attributes */
    public function updatePermission(Permission $permission, array $attributes): Permission
    {
        return $this->transaction(function () use ($permission, $attributes): Permission {
            // Nama boleh berubah tanpa memutus pemetaan: relasinya lewat
            // permission_id, bukan lewat nama.
            $permission->update($attributes);

            return $permission->fresh();
        });
    }

    public function deletePermission(Permission $permission): void
    {
        if ($permission->is_locked) {
            throw LockedRecord::permission($permission->name);
        }

        $this->transaction(function () use ($permission): void {
            // permission_id di tabel pemetaan memakai nullOnDelete, jadi
            // key-key yang menunjuk permission ini tetap ada dan berubah
            // status jadi "tak terpetakan".
            $permission->delete();
        });
    }

    /** @param  array<string, mixed>  $attributes */
    public function createRole(array $attributes, array $permissionIds = []): Role
    {
        return $this->transaction(function () use ($attributes, $permissionIds): Role {
            $role = Role::create($attributes + ['guard_name' => config('auth.defaults.guard', 'web')]);
            $role->syncPermissions(Permission::whereKey($permissionIds)->get());

            return $role->fresh('permissions');
        });
    }

    /** @param  array<string, mixed>  $attributes */
    public function updateRole(Role $role, array $attributes, ?array $permissionIds = null): Role
    {
        return $this->transaction(function () use ($role, $attributes, $permissionIds): Role {
            $role->update($attributes);

            if ($permissionIds !== null) {
                $role->syncPermissions(Permission::whereKey($permissionIds)->get());
            }

            return $role->fresh('permissions');
        });
    }

    public function deleteRole(Role $role): void
    {
        if ($role->is_locked || $role->isSuperAdmin()) {
            throw LockedRecord::role($role->name);
        }

        $this->transaction(fn () => $role->delete());
    }

    public function flushCaches(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->map->flush();
    }

    /**
     * Mengambil permission dengan nama tertentu, membuatnya bila belum ada.
     * Label dan grupnya diisi dari resource supaya daftar permission tetap
     * enak dibaca manusia.
     */
    private function permissionNamed(string $name, ?Resource $resource = null): Permission
    {
        $permission = Permission::where('name', $name)
            ->where('guard_name', config('auth.defaults.guard', 'web'))
            ->first();

        if ($permission !== null) {
            return $permission;
        }

        return Permission::create([
            'name' => $name,
            'guard_name' => config('auth.defaults.guard', 'web'),
            'label' => $resource?->label ? $resource->label.' — '.$name : null,
            'group' => $resource?->group ?? $resource?->label,
        ]);
    }

    /**
     * Membungkus operasi dalam transaksi dan membersihkan cache setelahnya.
     * Transaksi bersarang aman: DB::transaction memakai savepoint.
     */
    private function transaction(callable $callback): mixed
    {
        $result = DB::transaction($callback);

        $this->flushCaches();

        return $result;
    }
}
