<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Resource;
use App\Models\ResourcePermission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'stats' => [
                ['label' => 'Pengguna', 'value' => User::count(), 'icon' => 'users'],
                ['label' => 'Role', 'value' => Role::count(), 'icon' => 'shield-check'],
                ['label' => 'Permission', 'value' => Permission::count(), 'icon' => 'key'],
                ['label' => 'Resource', 'value' => Resource::count(), 'icon' => 'file-text'],
            ],
            // Key tanpa permission berarti ada pintu yang tertutup untuk semua
            // orang tanpa penjelasan — layak muncul di halaman depan.
            'unmappedCount' => ResourcePermission::whereNull('permission_id')->count(),
            'signups' => $this->monthlySignups(),
            'activity' => $this->recentActivity(),
        ]);
    }

    /**
     * Pengguna baru per bulan, 6 bulan terakhir. Dihitung per bulan lewat
     * `whereBetween`, bukan `groupBy` tanggal mentah, supaya hasilnya sama di
     * MySQL maupun SQLite (yang dipakai saat pengujian).
     *
     * @return array<int, array<string, mixed>>
     */
    private function monthlySignups(): array
    {
        $months = collect(range(5, 0))->map(fn (int $ago) => now()->subMonths($ago)->startOfMonth());

        return $months->map(fn (Carbon $month) => [
            'label' => $month->translatedFormat('M'),
            'value' => User::whereBetween('created_at', [$month, $month->copy()->endOfMonth()])->count(),
        ])->all();
    }

    /**
     * Catatan terbaru lintas modul, digabung dan diurutkan ulang di memori.
     * Bukan audit log sungguhan — cukup untuk menunjukkan sesuatu sedang
     * terjadi di aplikasi.
     *
     * @return array<int, array<string, string>>
     */
    private function recentActivity(): array
    {
        $entries = collect()
            ->concat(User::latest()->take(3)->get()->map(fn (User $user) => [
                'text' => "Pengguna {$user->name} ditambahkan",
                'at' => $user->created_at,
            ]))
            ->concat(Role::latest()->take(3)->get()->map(fn (Role $role) => [
                'text' => "Role {$role->name} dibuat",
                'at' => $role->created_at,
            ]))
            ->concat(Resource::latest()->take(3)->get()->map(fn (Resource $resource) => [
                'text' => "Resource {$resource->key} dibuat",
                'at' => $resource->created_at,
            ]));

        return $entries
            ->filter(fn (array $entry) => $entry['at'] !== null)
            ->sortByDesc('at')
            ->take(6)
            ->map(fn (array $entry) => [
                'text' => $entry['text'],
                'time' => $entry['at']->diffForHumans(),
            ])
            ->values()
            ->all();
    }
}
