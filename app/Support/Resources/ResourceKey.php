<?php

namespace App\Support\Resources;

use App\Enums\ResourceAction;
use App\Support\Resources\Exceptions\InvalidResourceKey;
use Stringable;

/**
 * Representasi sebuah resource key: gabungan nama resource dan satu aksi,
 * misalnya "users.create".
 *
 * Key inilah yang dipakai kode (route, Blade, policy, menu). Nama permission
 * yang berada di baliknya tidak pernah muncul di kode — pemetaannya hidup di
 * database dan bisa diubah lewat UI.
 */
final readonly class ResourceKey implements Stringable
{
    /**
     * Nama resource: huruf kecil, boleh dipisah tanda hubung atau garis bawah.
     * Titik sengaja dilarang supaya key selalu terpecah tepat jadi dua bagian.
     */
    public const RESOURCE_PATTERN = '/^[a-z][a-z0-9]*(?:[-_][a-z0-9]+)*$/';

    private function __construct(
        public string $resource,
        public ResourceAction $action,
    ) {}

    public static function make(string $resource, ResourceAction|string $action): self
    {
        $resource = trim($resource);

        if (preg_match(self::RESOURCE_PATTERN, $resource) !== 1) {
            throw InvalidResourceKey::badResource($resource);
        }

        if (is_string($action)) {
            $action = ResourceAction::tryFrom($action) ?? throw InvalidResourceKey::unknownAction($action);
        }

        return new self($resource, $action);
    }

    /**
     * @throws InvalidResourceKey bila format salah atau aksinya tidak dikenal.
     */
    public static function parse(string $key): self
    {
        $parts = explode('.', trim($key));

        if (count($parts) !== 2) {
            throw InvalidResourceKey::badFormat($key);
        }

        return self::make($parts[0], $parts[1]);
    }

    public static function tryParse(string $key): ?self
    {
        try {
            return self::parse($key);
        } catch (InvalidResourceKey) {
            return null;
        }
    }

    public static function isValid(string $key): bool
    {
        return self::tryParse($key) !== null;
    }

    public function value(): string
    {
        return $this->resource.'.'.$this->action->value;
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
