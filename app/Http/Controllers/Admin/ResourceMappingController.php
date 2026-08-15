<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateMappingRequest;
use App\Models\Permission;
use App\Models\ResourcePermission;
use App\Support\Resources\Exceptions\LockedRecord;
use App\Support\Resources\ResourceManager;
use App\Support\Table\TableBuilder;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;

/**
 * Layar pemetaan resource key → permission.
 *
 * Di sinilah abstraksi resource key jadi terlihat gunanya: kode memakai key,
 * halaman ini menentukan permission mana yang berada di baliknya. Mengganti
 * permission sebuah key berlaku seketika di route, Blade, policy, dan menu.
 */
class ResourceMappingController extends Controller
{
    public function __construct(private readonly ResourceManager $manager) {}

    public function index(): View
    {
        $table = TableBuilder::for(
            ResourcePermission::query()->with(['resource', 'permission'])
        )
            ->searchable(['resource.key', 'permission.name'])
            ->sortable(['action', 'created_at'], default: 'created_at')
            ->filter('status', function (Builder $query, string $value) {
                return $value === 'unmapped'
                    ? $query->whereNull('permission_id')
                    : $query->whereNotNull('permission_id');
            })
            ->filter('resource', fn (Builder $query, string $value) => $query->whereHas(
                'resource',
                fn (Builder $resource) => $resource->where('key', $value)
            ))
            ->perPage(25);

        return view('admin.mappings.index', [
            'mappings' => $table->paginate(),
            'table' => $table,
            'permissions' => Permission::orderBy('name')->pluck('name', 'id'),
            'unmappedCount' => ResourcePermission::whereNull('permission_id')->count(),
        ]);
    }

    public function update(UpdateMappingRequest $request, ResourcePermission $mapping): RedirectResponse
    {
        $permission = $request->filled('permission_id')
            ? Permission::findOrFail($request->validated('permission_id'))
            : null;

        try {
            $this->manager->remap($mapping, $permission);
        } catch (LockedRecord $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with('success', $permission
            ? "{$mapping->key()} kini memakai permission {$permission->name}."
            : "{$mapping->key()} dilepas dari permission mana pun. Aksesnya tertutup.");
    }

    public function destroy(ResourcePermission $mapping): RedirectResponse
    {
        try {
            $this->manager->remap($mapping, null);
        } catch (LockedRecord $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with('warning', "{$mapping->key()} sekarang tak terpetakan dan aksesnya tertutup.");
    }

    public function autoMap(): RedirectResponse
    {
        $count = $this->manager->autoMapMissing();

        return back()->with(
            $count > 0 ? 'success' : 'info',
            $count > 0
                ? "{$count} key dipetakan ke permission baru bernama sama dengan key-nya."
                : 'Tidak ada key yang perlu dipetakan.'
        );
    }
}
