<?php

namespace App\Console\Commands;

use App\Support\Resources\ResourceManager;
use Illuminate\Console\Command;

class ResourceSyncCommand extends Command
{
    protected $signature = 'resource:sync';

    protected $description = 'Membuatkan permission untuk setiap resource key yang belum dipetakan';

    public function handle(ResourceManager $manager): int
    {
        $mapped = $manager->autoMapMissing();

        if ($mapped === 0) {
            $this->components->info('Semua resource key sudah terpetakan.');

            return self::SUCCESS;
        }

        $this->components->info("{$mapped} resource key dipetakan ke permission baru.");
        $this->components->warn('Permission baru belum dimiliki role mana pun. Berikan lewat menu Role.');

        return self::SUCCESS;
    }
}
