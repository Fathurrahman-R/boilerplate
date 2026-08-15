<?php

use App\Enums\ResourceAction;
use App\Support\Resources\ResourceGate;
use App\Support\Resources\ResourceKey;

if (! function_exists('rk')) {
    /**
     * Menyusun resource key dari nama resource dan aksi.
     *
     *   rk('users', ResourceAction::Create) // "users.create"
     *
     * Aksinya wajib berupa case enum (atau nilainya), jadi salah ketik nama
     * aksi langsung gagal saat itu juga, bukan diam-diam menolak akses nanti.
     */
    function rk(string $resource, ResourceAction|string $action): string
    {
        return ResourceKey::make($resource, $action)->value();
    }
}

if (! function_exists('resource_allows')) {
    /** Apakah pengguna yang sedang login boleh melakukan key ini? */
    function resource_allows(string $key): bool
    {
        return app(ResourceGate::class)->allows($key);
    }
}

if (! function_exists('resource_gate')) {
    function resource_gate(): ResourceGate
    {
        return app(ResourceGate::class);
    }
}
