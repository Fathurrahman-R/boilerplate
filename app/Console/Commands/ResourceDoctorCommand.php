<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\ResourcePermission;
use Illuminate\Console\Command;

/**
 * Audit kesehatan RBAC. Tiga hal yang paling sering bikin bingung saat
 * pemetaan dikelola dari UI:
 *
 *   1. Resource key ada tapi tidak menunjuk permission — aksesnya tertutup
 *      untuk semua orang, dan tidak ada pesan error yang menjelaskan kenapa.
 *   2. Permission tidak dipakai resource key mana pun — dicentang di role,
 *      tapi tidak menjaga apa-apa.
 *   3. Permission tidak dimiliki role mana pun — key-nya efektif mati.
 */
class ResourceDoctorCommand extends Command
{
    protected $signature = 'resource:doctor';

    protected $description = 'Memeriksa resource key, permission, dan pemetaan yang bermasalah';

    public function handle(): int
    {
        $problems = 0;

        $problems += $this->reportUnmappedKeys();
        $problems += $this->reportOrphanPermissions();
        $problems += $this->reportUnusedPermissions();

        $this->newLine();

        if ($problems === 0) {
            $this->components->info('Tidak ditemukan masalah.');

            return self::SUCCESS;
        }

        $this->components->warn("{$problems} temuan. Perbaiki lewat menu Resource / Permission / Pemetaan.");

        return self::FAILURE;
    }

    private function reportUnmappedKeys(): int
    {
        $mappings = ResourcePermission::query()
            ->whereNull('permission_id')
            ->with('resource')
            ->get();

        if ($mappings->isEmpty()) {
            return 0;
        }

        $this->components->error('Resource key tanpa permission (akses tertutup untuk semua):');

        foreach ($mappings as $mapping) {
            $this->line('  - '.$mapping->resource->key.'.'.$mapping->action->value);
        }

        $this->line('  Perbaiki cepat: <fg=cyan>php artisan resource:sync</>');

        return $mappings->count();
    }

    private function reportOrphanPermissions(): int
    {
        $permissions = Permission::query()->doesntHave('mappings')->orderBy('name')->get();

        if ($permissions->isEmpty()) {
            return 0;
        }

        $this->newLine();
        $this->components->warn('Permission yang tidak dipakai resource key mana pun:');

        foreach ($permissions as $permission) {
            $this->line('  - '.$permission->name);
        }

        return $permissions->count();
    }

    private function reportUnusedPermissions(): int
    {
        $permissions = Permission::query()->has('mappings')->doesntHave('roles')->orderBy('name')->get();

        if ($permissions->isEmpty()) {
            return 0;
        }

        $this->newLine();
        $this->components->warn('Permission yang belum dimiliki role mana pun:');

        foreach ($permissions as $permission) {
            $this->line('  - '.$permission->name);
        }

        return $permissions->count();
    }
}
