<?php

namespace App\Console\Commands;

use App\Models\Resource;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * Menulis ulang app/Support/Resources/ResourceKeys.php dari isi database.
 *
 * Resource dikelola dari UI, jadi daftarnya hidup di database — tapi kode yang
 * memakainya lebih aman kalau memakai konstanta: IDE bisa melengkapi otomatis
 * dan key yang sudah dihapus langsung terlihat sebagai error, bukan sebagai
 * penolakan akses yang membingungkan saat dijalankan.
 */
class ResourceKeysCommand extends Command
{
    protected $signature = 'resource:keys {--check : Hanya periksa apakah berkas sudah mutakhir, tanpa menulis}';

    protected $description = 'Membuat ulang berkas konstanta resource key dari database';

    public function handle(): int
    {
        $path = config('resources.generated_keys_path');

        $contents = $this->render($this->collectKeys());

        if ($this->option('check')) {
            $current = is_file($path) ? file_get_contents($path) : null;

            if ($current === $contents) {
                $this->components->info('ResourceKeys.php sudah mutakhir.');

                return self::SUCCESS;
            }

            $this->components->error('ResourceKeys.php tidak mutakhir. Jalankan `php artisan resource:keys`.');

            return self::FAILURE;
        }

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), recursive: true);
        }

        file_put_contents($path, $contents);

        $this->components->info('ResourceKeys.php ditulis ke '.$path);

        return self::SUCCESS;
    }

    /** @return array<string, string> nama konstanta => nilai key */
    private function collectKeys(): array
    {
        $keys = [];

        $resources = Resource::query()->with('mappings')->orderBy('key')->get();

        foreach ($resources as $resource) {
            foreach ($resource->mappings->sortBy(fn ($m) => $m->action->value) as $mapping) {
                $key = $resource->keyFor($mapping->action)->value();
                $keys[$this->constantName($key)] = $key;
            }
        }

        return $keys;
    }

    private function constantName(string $key): string
    {
        return Str::upper(Str::replace(['.', '-'], '_', $key));
    }

    /** @param  array<string, string>  $keys */
    private function render(array $keys): string
    {
        $lines = [];

        foreach ($keys as $constant => $key) {
            $lines[] = "    public const {$constant} = '{$key}';";
        }

        $body = $lines === []
            ? '    // Belum ada resource key. Buat lewat panel admin, lalu jalankan ulang perintah ini.'
            : implode("\n", $lines);

        return <<<PHP
        <?php

        namespace App\Support\Resources;

        /**
         * BERKAS INI DIGENERATE OTOMATIS — jangan diubah manual.
         *
         * Jalankan `php artisan resource:keys` setiap kali daftar resource
         * berubah. Berkas ini sengaja ikut di-commit supaya autocomplete tetap
         * jalan tanpa perlu menyiapkan database lebih dulu.
         */
        final class ResourceKeys
        {
        {$body}
        }

        PHP;
    }
}
