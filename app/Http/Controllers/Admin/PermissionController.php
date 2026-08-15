<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePermissionRequest;
use App\Http\Requests\Admin\UpdatePermissionRequest;
use App\Models\Permission;
use App\Support\Resources\Exceptions\LockedRecord;
use App\Support\Resources\ResourceManager;
use App\Support\Table\TableBuilder;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;

/**
 * CRUD permission.
 *
 * Permission di sini berdiri sendiri: boleh dibuat tanpa resource key, boleh
 * diganti namanya tanpa memutus pemetaan (relasinya lewat id), dan boleh
 * dipakai banyak key sekaligus.
 */
class PermissionController extends Controller
{
    public function __construct(private readonly ResourceManager $manager) {}

    public function index(): View
    {
        $table = TableBuilder::for(
            Permission::query()->withCount(['roles', 'mappings'])->with('mappings.resource')
        )
            ->searchable(['name', 'label', 'group'])
            ->sortable(['name', 'group', 'created_at'], default: 'name')
            ->filter('group', fn (Builder $query, string $value) => $query->where('group', $value))
            ->filter('status', function (Builder $query, string $value) {
                return $value === 'yatim'
                    ? $query->doesntHave('mappings')
                    : $query->has('mappings');
            });

        return view('admin.permissions.index', [
            'permissions' => $table->paginate(),
            'table' => $table,
            'groups' => Permission::query()->whereNotNull('group')->distinct()->orderBy('group')->pluck('group', 'group'),
        ]);
    }

    public function create(): View
    {
        return view('admin.permissions.create');
    }

    public function store(StorePermissionRequest $request): RedirectResponse
    {
        $permission = $this->manager->createPermission($request->validated());

        return redirect()->route('admin.permissions.index')
            ->with('success', "Permission {$permission->name} dibuat.");
    }

    public function edit(Permission $permission): View
    {
        return view('admin.permissions.edit', [
            'permission' => $permission->load('mappings.resource', 'roles'),
        ]);
    }

    public function update(UpdatePermissionRequest $request, Permission $permission): RedirectResponse
    {
        $this->manager->updatePermission($permission, $request->validated());

        return redirect()->route('admin.permissions.index')
            ->with('success', "Permission {$permission->name} diperbarui.");
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        // Dihitung sebelum dihapus: setelah permission hilang, pemetaannya
        // di-null-kan sehingga relasinya tidak bisa dihitung lagi.
        $affected = $permission->mappings()->count();

        try {
            $this->manager->deletePermission($permission);
        } catch (LockedRecord $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('admin.permissions.index')->with(
            $affected > 0 ? 'warning' : 'success',
            $affected > 0
                ? "Permission dihapus. {$affected} resource key kini tak terpetakan dan aksesnya tertutup."
                : 'Permission dihapus.'
        );
    }
}
