<?php

use App\Enums\ResourceAction;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Support\Resources\ResourceManager;
use Illuminate\Support\Facades\Blade;

beforeEach(function () {
    app(ResourceManager::class)->createResource(
        ['key' => 'posts', 'label' => 'Artikel'],
        [ResourceAction::View, ResourceAction::Update, ResourceAction::Delete],
    );

    $role = Role::create(['name' => 'editor']);
    $role->syncPermissions(Permission::whereIn('name', ['posts.view', 'posts.update'])->get());

    $this->editor = User::factory()->create()->assignRole($role);
});

it('merender isi @resource hanya untuk yang punya key', function () {
    $template = <<<'BLADE'
    @resource('posts.update')
    boleh
    @elseresource
    tidak
    @endresource
    BLADE;

    $this->actingAs($this->editor);
    expect(trim(Blade::render($template)))->toBe('boleh');

    auth()->logout();
    expect(trim(Blade::render($template)))->toBe('tidak');
});

it('mendukung @anyresource dan @allresource', function () {
    $this->actingAs($this->editor);

    $any = fn (string $keys): string => trim(Blade::render(<<<BLADE
    @anyresource([{$keys}])
    ya
    @endanyresource
    BLADE));

    $all = fn (string $keys): string => trim(Blade::render(<<<BLADE
    @allresource([{$keys}])
    ya
    @endallresource
    BLADE));

    expect($any("'posts.delete', 'posts.update'"))->toBe('ya');
    expect($any("'posts.delete'"))->toBe('');
    expect($all("'posts.view', 'posts.update'"))->toBe('ya');
    expect($all("'posts.view', 'posts.delete'"))->toBe('');
});

it('menyembunyikan komponen x-can tanpa izin', function () {
    $this->actingAs($this->editor);

    expect(trim(Blade::render('<x-can resource="posts.update">tombol</x-can>')))->toBe('tombol');
    expect(trim(Blade::render('<x-can resource="posts.delete">tombol</x-can>')))->toBe('');
    expect(trim(Blade::render('<x-can :any="[\'posts.delete\', \'posts.view\']">tombol</x-can>')))->toBe('tombol');
});
