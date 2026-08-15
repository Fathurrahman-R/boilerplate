<?php

namespace App\Support\Resources;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Satu-satunya tempat keputusan "boleh atau tidak" diambil.
 *
 * Middleware route, directive Blade, komponen <x-can>, policy, dan pembangun
 * menu semuanya memanggil kelas ini, sehingga aturannya tidak pernah berbeda
 * antar lapisan.
 *
 * Alur satu key:
 *   1. Tidak ada pengguna login              → tolak.
 *   2. Pengguna menyandang role super admin  → izinkan, tanpa lihat peta.
 *   3. Key tidak ada di peta                 → tolak, catat peringatan.
 *   4. Key ada tapi belum dipetakan          → tolak.
 *   5. Selain itu                            → cek permission hasil pemetaan.
 */
class ResourceGate
{
    /** @var array<string, true> */
    private array $warned = [];

    public function __construct(private readonly ResourceMap $map) {}

    public function allows(string $key, ?Authorizable $user = null): bool
    {
        $user ??= Auth::user();

        if ($user === null) {
            return false;
        }

        if ($this->isSuperAdmin($user)) {
            return true;
        }

        if (! $this->map->has($key)) {
            $this->warnUnknown($key);

            return false;
        }

        $permission = $this->map->permissionFor($key);

        if ($permission === null) {
            return false;
        }

        return $user->can($permission);
    }

    public function denies(string $key, ?Authorizable $user = null): bool
    {
        return ! $this->allows($key, $user);
    }

    /**
     * Izinkan bila pengguna memiliki setidaknya satu dari key yang diberikan.
     *
     * @param  array<int, string>|string  $keys
     */
    public function any(array|string $keys, ?Authorizable $user = null): bool
    {
        foreach ((array) $keys as $key) {
            if ($this->allows($key, $user)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Izinkan hanya bila pengguna memiliki seluruh key yang diberikan.
     *
     * @param  array<int, string>|string  $keys
     */
    public function all(array|string $keys, ?Authorizable $user = null): bool
    {
        $keys = (array) $keys;

        if ($keys === []) {
            return false;
        }

        foreach ($keys as $key) {
            if (! $this->allows($key, $user)) {
                return false;
            }
        }

        return true;
    }

    public function isSuperAdmin(?Authorizable $user = null): bool
    {
        $user ??= Auth::user();

        if ($user === null || ! method_exists($user, 'hasRole')) {
            return false;
        }

        return $user->hasRole(config('resources.super_admin_role'));
    }

    /**
     * Key yang tidak dikenal hampir selalu berarti salah ketik di route atau
     * resource yang sudah dihapus. Ditolak diam-diam akan sulit dilacak, jadi
     * dicatat sekali per key per request.
     */
    private function warnUnknown(string $key): void
    {
        if (! config('resources.log_unknown_keys', true) || isset($this->warned[$key])) {
            return;
        }

        $this->warned[$key] = true;

        Log::warning("Resource key [{$key}] tidak terdaftar. Akses ditolak.", [
            'hint' => 'Jalankan `php artisan resource:doctor` untuk melihat key yang bermasalah.',
        ]);
    }
}
