<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesBulkDestroy;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Resource;
use App\Models\Role;
use App\Support\Resources\Exceptions\LockedRecord;
use App\Support\Resources\ResourceManager;
use App\Support\Table\TableBuilder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use HandlesBulkDestroy;

    public function __construct(private readonly ResourceManager $manager) {}

    public function index(): View
    {
        $table = TableBuilder::for(Role::query()->withCount(['permissions', 'users']))
            ->searchable(['name', 'label'])
            ->sortable(['name', 'created_at'], default: 'name');

        return view('admin.roles.index', ['roles' => $table->paginate(), 'table' => $table]);
    }

    public function create(): View
    {
        return view('admin.roles.create', $this->permissionMatrix());
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $role = $this->manager->createRole(
            $request->safe()->only(['name', 'label', 'description']),
            $request->validated('permissions', []),
        );

        return redirect()->route('admin.roles.index')->with('success', "Role {$role->name} dibuat.");
    }

    public function edit(Role $role): View
    {
        return view('admin.roles.edit', [
            'role' => $role->load('permissions'),
            ...$this->permissionMatrix(),
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $this->manager->updateRole(
            $role,
            $request->safe()->only(['name', 'label', 'description']),
            $request->validated('permissions', []),
        );

        return redirect()->route('admin.roles.index')->with('success', "Role {$role->name} diperbarui.");
    }

    /** Fragmen panel detail yang diambil drawer saat baris tabel diklik. */
    public function panel(Role $role): View
    {
        return view('admin.roles.panel', ['role' => $role->load('permissions')]);
    }

    public function destroy(Role $role): RedirectResponse
    {
        try {
            $this->manager->deleteRole($role);
        } catch (LockedRecord $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role dihapus.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        return $this->destroyMany($request, Role::class, 'admin.roles.index', function (Role $role): ?string {
            try {
                $this->manager->deleteRole($role);
            } catch (LockedRecord $exception) {
                return $exception->getMessage();
            }

            return null;
        });
    }

    /**
     * Matriks centang untuk form role: baris = resource, kolom = aksi.
     *
     * Permission yang tidak dipetakan resource key mana pun tetap ditampilkan
     * terpisah — permission itu sah dipakai, cuma tidak menjaga key apa pun.
     *
     * @return array<string, mixed>
     */
    private function permissionMatrix(): array
    {
        $resources = Resource::query()
            ->with('mappings.permission')
            ->orderBy('group')
            ->orderBy('key')
            ->get();

        return [
            'resources' => $resources,
            'loosePermissions' => Permission::query()
                ->doesntHave('mappings')
                ->orderBy('name')
                ->get(),
        ];
    }
}
