<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesBulkDestroy;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Support\Table\TableBuilder;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserController extends Controller
{
    use HandlesBulkDestroy;

    public function index(): View
    {
        $table = TableBuilder::for(User::query()->with('roles'))
            ->searchable(['name', 'email'])
            ->sortable(['name', 'email', 'created_at'], default: 'created_at', direction: 'desc')
            ->filter('role', fn (Builder $query, string $value) => $query->whereHas(
                'roles',
                fn (Builder $roles) => $roles->where('name', $value)
            ))
            ->filter('status', fn (Builder $query, string $value) => $query->where('is_active', $value === 'aktif'));

        return view('admin.users.index', [
            'users' => $table->paginate(),
            'table' => $table,
            'roles' => Role::orderBy('name')->pluck('name', 'name'),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', ['roles' => Role::orderBy('name')->get()]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
            'is_active' => $request->boolean('is_active'),
            'email_verified_at' => now(),
        ]);

        $user->syncRoles($request->validated('roles', []));

        return redirect()->route('admin.users.index')->with('success', "Pengguna {$user->name} dibuat.");
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user->load('roles'),
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->update([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->validated('password'))]);
        }

        $this->syncRolesSafely($user, $request->validated('roles', []));

        return redirect()->route('admin.users.index')->with('success', "Pengguna {$user->name} diperbarui.");
    }

    /**
     * Fragmen panel detail yang diambil drawer saat baris tabel diklik.
     * Tanpa layout — pemanggilnya yang sudah punya bingkai.
     */
    public function panel(User $user): View
    {
        return view('admin.users.panel', ['user' => $user->load('roles')]);
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($reason = $this->deletionBlocker($user)) {
            return back()->with('error', $reason);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna dihapus.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        return $this->destroyMany($request, User::class, 'admin.users.index', function (User $user): ?string {
            if ($reason = $this->deletionBlocker($user)) {
                return $reason;
            }

            $user->delete();

            return null;
        });
    }

    /**
     * Alasan sebuah akun tidak boleh dihapus, atau null kalau boleh. Dipakai
     * penghapusan satuan maupun massal supaya keduanya tidak bisa berbeda.
     */
    private function deletionBlocker(User $user): ?string
    {
        if ($user->is(auth()->user())) {
            return 'Anda tidak bisa menghapus akun sendiri.';
        }

        if ($this->isLastSuperAdmin($user)) {
            return 'Ini super admin terakhir. Tunjuk penggantinya lebih dulu.';
        }

        return null;
    }

    public function export(): StreamedResponse
    {
        $table = TableBuilder::for(User::query()->with('roles'))
            ->searchable(['name', 'email'])
            ->sortable(['name', 'email', 'created_at'], default: 'created_at', direction: 'desc');

        return $table->download(fn (User $user): array => [
            'Nama' => $user->name,
            'Email' => $user->email,
            'Role' => $user->roles->pluck('name')->implode(', '),
            'Status' => $user->is_active ? 'Aktif' : 'Nonaktif',
            'Dibuat' => $user->created_at?->format('Y-m-d H:i'),
        ], 'pengguna-'.now()->format('Ymd-His').'.csv');
    }

    /**
     * Mencabut role super admin dari orang terakhir yang memilikinya akan
     * mengunci semua orang di luar panel, jadi perubahannya diabaikan.
     *
     * @param  array<int, string>  $roles
     */
    private function syncRolesSafely(User $user, array $roles): void
    {
        $superAdmin = config('resources.super_admin_role');

        if ($this->isLastSuperAdmin($user) && ! in_array($superAdmin, $roles, true)) {
            $roles[] = $superAdmin;

            session()->flash('warning', 'Role super admin dipertahankan: ini pemegang terakhirnya.');
        }

        $user->syncRoles($roles);
    }

    private function isLastSuperAdmin(User $user): bool
    {
        $superAdmin = config('resources.super_admin_role');

        if (! $user->hasRole($superAdmin)) {
            return false;
        }

        return User::role($superAdmin)->count() <= 1;
    }
}
