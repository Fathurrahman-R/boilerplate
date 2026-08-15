<?php

use App\Enums\ResourceAction;
use App\Support\Resources\Exceptions\InvalidResourceKey;
use App\Support\Resources\ResourceKey;

it('menyusun key dari nama resource dan aksi', function () {
    expect(ResourceKey::make('users', ResourceAction::Create)->value())->toBe('users.create');
    expect(rk('users', ResourceAction::Create))->toBe('users.create');
    expect(rk('users', 'create'))->toBe('users.create');
});

it('memecah key yang valid', function () {
    $key = ResourceKey::parse('laporan_bulanan.export');

    expect($key->resource)->toBe('laporan_bulanan');
    expect($key->action)->toBe(ResourceAction::Export);
});

it('menolak key tanpa tepat satu titik', function (string $key) {
    ResourceKey::parse($key);
})->with(['users', 'users.create.extra', ''])->throws(InvalidResourceKey::class);

it('menolak nama resource yang tidak sesuai format', function (string $resource) {
    ResourceKey::make($resource, ResourceAction::View);
})->with(['Users', '1users', 'users--', '-users', 'users.posts'])->throws(InvalidResourceKey::class);

it('mengabaikan spasi di ujung nama resource', function () {
    expect(ResourceKey::make('  users  ', ResourceAction::View)->value())->toBe('users.view');
});

it('menolak aksi di luar enum', function () {
    ResourceKey::parse('users.hapus_semua');
})->throws(InvalidResourceKey::class, 'Aksi [hapus_semua] tidak dikenal');

it('mengembalikan null saat tryParse gagal', function () {
    expect(ResourceKey::tryParse('users'))->toBeNull();
    expect(ResourceKey::isValid('users.create'))->toBeTrue();
    expect(ResourceKey::isValid('users.ngawur'))->toBeFalse();
});

it('menampilkan nilai saat dijadikan string', function () {
    expect((string) ResourceKey::make('posts', ResourceAction::Publish))->toBe('posts.publish');
});
