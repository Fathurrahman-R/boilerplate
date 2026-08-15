<?php

namespace App\Console\Commands;

use App\Models\Resource;
use Illuminate\Console\Command;

class ResourceListCommand extends Command
{
    protected $signature = 'resource:list
                            {--group= : Saring berdasarkan grup}
                            {--unmapped : Hanya tampilkan key yang belum dipetakan}';

    protected $description = 'Menampilkan seluruh resource key beserta permission yang terpasang';

    public function handle(): int
    {
        $resources = Resource::query()
            ->with('mappings.permission')
            ->when($this->option('group'), fn ($query, $group) => $query->where('group', $group))
            ->orderBy('key')
            ->get();

        $rows = [];

        foreach ($resources as $resource) {
            foreach ($resource->mappings->sortBy(fn ($m) => $m->action->value) as $mapping) {
                $mapped = $mapping->isMapped();

                if ($this->option('unmapped') && $mapped) {
                    continue;
                }

                $rows[] = [
                    $resource->keyFor($mapping->action)->value(),
                    $resource->group ?? '-',
                    $mapped ? $mapping->permission->name : '<fg=red>belum dipetakan</>',
                    $mapped ? (string) $mapping->permission->roles()->count() : '-',
                ];
            }
        }

        if ($rows === []) {
            $this->components->info('Tidak ada resource key yang cocok.');

            return self::SUCCESS;
        }

        $this->table(['Resource key', 'Grup', 'Permission', 'Dipakai role'], $rows);
        $this->newLine();
        $this->components->info(count($rows).' resource key ditampilkan.');

        return self::SUCCESS;
    }
}
