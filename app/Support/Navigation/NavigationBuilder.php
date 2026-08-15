<?php

namespace App\Support\Navigation;

use App\Support\Resources\ResourceGate;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

/**
 * Menyusun menu sidebar dari config/navigation.php, membuang item yang
 * resource key-nya tidak dimiliki pengguna.
 *
 * Induk yang seluruh anaknya tersembunyi ikut hilang — kalau tidak, pengguna
 * melihat grup menu kosong yang tidak bisa dibuka.
 */
class NavigationBuilder
{
    public function __construct(private readonly ResourceGate $gate) {}

    /** @return array<int, array<string, mixed>> */
    public function build(): array
    {
        return $this->filter(config('navigation', []));
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     * @return array<int, array<string, mixed>>
     */
    private function filter(array $items): array
    {
        $result = [];

        foreach ($items as $item) {
            if (! $this->visible($item)) {
                continue;
            }

            $children = $this->filter($item['children'] ?? []);

            if (isset($item['children']) && $children === []) {
                continue;
            }

            $result[] = [
                'label' => $item['label'],
                'icon' => $item['icon'] ?? null,
                'url' => $this->url($item),
                'active' => $this->isActive($item, $children),
                'children' => $children,
                'badge' => $item['badge'] ?? null,
            ];
        }

        return $result;
    }

    /** @param  array<string, mixed>  $item */
    private function visible(array $item): bool
    {
        if (isset($item['resource'])) {
            return $this->gate->any((array) $item['resource']);
        }

        // Item tanpa resource key selalu tampil (mis. Dashboard).
        return true;
    }

    /** @param  array<string, mixed>  $item */
    private function url(array $item): ?string
    {
        if (isset($item['url'])) {
            return $item['url'];
        }

        if (isset($item['route']) && Route::has($item['route'])) {
            return route($item['route'], $item['route_params'] ?? []);
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $item
     * @param  array<int, array<string, mixed>>  $children
     */
    private function isActive(array $item, array $children): bool
    {
        foreach ($children as $child) {
            if ($child['active']) {
                return true;
            }
        }

        if (isset($item['active'])) {
            return Request::is($item['active']);
        }

        if (isset($item['route'])) {
            return Request::routeIs($item['route']);
        }

        return false;
    }
}
