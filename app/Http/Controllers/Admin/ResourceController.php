<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ResourceAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreResourceRequest;
use App\Http\Requests\Admin\UpdateResourceRequest;
use App\Models\Resource;
use App\Support\Resources\Exceptions\LockedRecord;
use App\Support\Resources\ResourceManager;
use App\Support\Table\TableBuilder;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;

/**
 * CRUD resource.
 *
 * Membuat resource sekaligus membuatkan permission untuk tiap aksi yang
 * dicentang, lalu memetakannya. Pemetaan itu bisa diubah belakangan lewat
 * ResourceMappingController tanpa menyentuh kode.
 */
class ResourceController extends Controller
{
    public function __construct(private readonly ResourceManager $manager) {}

    public function index(): View
    {
        $table = TableBuilder::for(Resource::query()->with('mappings.permission')->withCount('mappings'))
            ->searchable(['key', 'label', 'group'])
            ->sortable(['key', 'label', 'group', 'created_at'], default: 'key')
            ->filter('group', fn (Builder $query, string $value) => $query->where('group', $value));

        return view('admin.resources.index', [
            'resources' => $table->paginate(),
            'table' => $table,
            'groups' => Resource::query()->whereNotNull('group')->distinct()->orderBy('group')->pluck('group', 'group'),
        ]);
    }

    public function create(): View
    {
        return view('admin.resources.create', ['actions' => ResourceAction::cases()]);
    }

    public function store(StoreResourceRequest $request): RedirectResponse
    {
        $resource = $this->manager->createResource(
            $request->safe()->only(['key', 'label', 'group', 'description']),
            $request->validated('actions', []),
        );

        return redirect()->route('admin.resources.show', $resource)
            ->with('success', "Resource {$resource->key} dibuat beserta permission-nya.");
    }

    public function show(Resource $resource): View
    {
        return view('admin.resources.show', [
            'resource' => $resource->load('mappings.permission.roles'),
        ]);
    }

    public function edit(Resource $resource): View
    {
        return view('admin.resources.edit', [
            'resource' => $resource->load('mappings'),
            'actions' => ResourceAction::cases(),
        ]);
    }

    public function update(UpdateResourceRequest $request, Resource $resource): RedirectResponse
    {
        try {
            $this->manager->updateResource(
                $resource,
                $request->safe()->only(['key', 'label', 'group', 'description']),
                $request->validated('actions', []),
            );
        } catch (LockedRecord $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('admin.resources.show', $resource)
            ->with('success', "Resource {$resource->key} diperbarui.");
    }

    public function destroy(Resource $resource): RedirectResponse
    {
        try {
            $this->manager->deleteResource($resource);
        } catch (LockedRecord $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('admin.resources.index')
            ->with('success', 'Resource dihapus. Permission-nya dibiarkan tetap ada.');
    }
}
