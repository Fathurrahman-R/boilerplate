<?php

namespace App\Support\Resources\Exceptions;

use App\Enums\ResourceAction;
use InvalidArgumentException;

final class InvalidResourceKey extends InvalidArgumentException
{
    public static function badFormat(string $key): self
    {
        return new self("Resource key [{$key}] harus berbentuk \"resource.action\", tepat satu titik.");
    }

    public static function badResource(string $resource): self
    {
        return new self(
            "Nama resource [{$resource}] tidak valid. Gunakan huruf kecil, angka, tanda hubung, atau garis bawah."
        );
    }

    public static function unknownAction(string $action): self
    {
        $allowed = implode(', ', ResourceAction::values());

        return new self("Aksi [{$action}] tidak dikenal. Aksi yang tersedia: {$allowed}.");
    }
}
