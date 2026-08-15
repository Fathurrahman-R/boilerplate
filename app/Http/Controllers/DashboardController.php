<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Resource;
use App\Models\ResourcePermission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'stats' => [
                ['label' => 'Pengguna', 'value' => User::count(), 'icon' => 'users'],
                ['label' => 'Role', 'value' => Role::count(), 'icon' => 'shield'],
                ['label' => 'Permission', 'value' => Permission::count(), 'icon' => 'key'],
                ['label' => 'Resource', 'value' => Resource::count(), 'icon' => 'document'],
            ],
            // Key tanpa permission berarti ada pintu yang tertutup untuk semua
            // orang tanpa penjelasan — layak muncul di halaman depan.
            'unmappedCount' => ResourcePermission::whereNull('permission_id')->count(),
        ]);
    }
}
