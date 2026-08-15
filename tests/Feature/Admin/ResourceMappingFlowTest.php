<?php

use App\Enums\ResourceAction;
use App\Models\Permission;
use App\Models\Resource;
use App\Models\ResourcePermission;
use App\Models\Role;
use App\Models\User;
use App\Support\Resources\ResourceGate;
use App\Support\Resources\ResourceMap;
use Database\Seeders\ResourceSeeder;
use Database\Seeders\RoleSeeder;

beforeEach(function () {
    $this->seed([ResourceSeeder::class, RoleSeeder::class]);

    $this->superAdmin = User::factory()->create(['email_verified_at' => now()])
        ->assignRole(config('resources.super_admin_role'));
});

it('membuat resource beserta permission dan pemetaannya lewat form', function () {
    $this->actingAs($this->superAdmin)
        ->post(route('admin.resources.store'), [
            'key' => 'Laporan Bulanan',   // sengaja pakai spasi dan huruf besar
            'label' => 'Laporan Bulanan',
            'group' => 'Keuangan',
            'actions' => [ResourceAction::View->value, ResourceAction::Export->value],
        ])
        ->assertRedirect();

    $resource = Resource::where('key', 'laporan_bulanan')->firstOrFail();

    expect($resource->mappings)->toHaveCount(2);
    expect(Permission::where('name', 'laporan_bulanan.view')->exists())->toBeTrue();
    expect(Permission::where('name', 'laporan_bulanan.export')->exists())->toBeTrue();
});

it('menolak nama aksi di luar enum', function () {
    $this->actingAs($this->superAdmin)
        ->post(route('admin.resources.store'), [
            'key' => 'laporan',
            'label' => 'Laporan',
            'actions' => ['hapus_semua'],
        ])
        ->assertSessionHasErrors('actions.0');

    expect(Resource::where('key', 'laporan')->exists())->toBeFalse();
});

it('mengarahkan key ke permission lain tanpa mengubah kode', function () {
    $editor = Role::create(['name' => 'editor']);
    $user = User::factory()->create(['email_verified_at' => now()])->assignRole($editor);

    $gate = app(ResourceGate::class);
    expect($gate->allows('posts.export', $user))->toBeFalse();

    // Permission baru, dibuat lewat modul Permission.
    $this->actingAs($this->superAdmin)
        ->post(route('admin.permissions.store'), ['name' => 'akses-laporan'])
        ->assertRedirect();

    $permission = Permission::where('name', 'akses-laporan')->firstOrFail();
    $editor->givePermissionTo($permission);

    // Key posts.export sekarang menunjuk permission itu.
    $mapping = ResourcePermission::whereHas('resource', fn ($q) => $q->where('key', 'posts'))
        ->where('action', ResourceAction::Export->value)
        ->firstOrFail();

    $this->actingAs($this->superAdmin)
        ->put(route('admin.mappings.update', $mapping), ['permission_id' => $permission->id])
        ->assertRedirect();

    expect(app(ResourceGate::class)->allows('posts.export', $user->fresh()))->toBeTrue();
});

it('menutup akses saat pemetaan dilepas', function () {
    $editor = Role::create(['name' => 'editor']);
    $editor->givePermissionTo(Permission::where('name', 'posts.view')->firstOrFail());
    $user = User::factory()->create(['email_verified_at' => now()])->assignRole($editor);

    expect(app(ResourceGate::class)->allows('posts.view', $user))->toBeTrue();

    $mapping = ResourcePermission::whereHas('resource', fn ($q) => $q->where('key', 'posts'))
        ->where('action', ResourceAction::View->value)
        ->firstOrFail();

    $this->actingAs($this->superAdmin)
        ->delete(route('admin.mappings.destroy', $mapping))
        ->assertRedirect();

    expect(app(ResourceGate::class)->allows('posts.view', $user->fresh()))->toBeFalse();
    expect($mapping->fresh()->permission_id)->toBeNull();
});

it('memetakan ulang seluruh key kosong dengan satu aksi', function () {
    ResourcePermission::query()->update(['permission_id' => null]);
    app(ResourceMap::class)->flush();

    $this->actingAs($this->superAdmin)
        ->post(route('admin.mappings.auto'))
        ->assertRedirect();

    expect(ResourcePermission::whereNull('permission_id')->count())->toBe(0);
});

it('melindungi resource inti dari penghapusan', function () {
    $resource = Resource::where('key', 'roles')->firstOrFail();

    $this->actingAs($this->superAdmin)
        ->delete(route('admin.resources.destroy', $resource))
        ->assertSessionHas('error');

    expect(Resource::where('key', 'roles')->exists())->toBeTrue();
});

it('menghapus resource tanpa menghapus permission-nya', function () {
    $resource = Resource::where('key', 'posts')->firstOrFail();

    $this->actingAs($this->superAdmin)
        ->delete(route('admin.resources.destroy', $resource))
        ->assertRedirect();

    expect(Resource::where('key', 'posts')->exists())->toBeFalse();
    expect(Permission::where('name', 'posts.view')->exists())->toBeTrue();
});

it('menyembunyikan menu yang key-nya tidak dimiliki', function () {
    $editor = Role::create(['name' => 'editor']);
    $editor->givePermissionTo(Permission::where('name', 'posts.view')->firstOrFail());
    $user = User::factory()->create(['email_verified_at' => now()])->assignRole($editor);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee('Artikel');
    $response->assertDontSee('Manajemen Akses');
});
