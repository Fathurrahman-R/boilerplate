<?php

namespace App\Support\Resources\Exceptions;

use RuntimeException;

/**
 * Dilempar saat ada usaha mengubah atau menghapus data inti yang ditandai
 * terkunci. Ini pengaman terakhir di lapisan service; UI sudah menyembunyikan
 * tombolnya lebih dulu.
 */
final class LockedRecord extends RuntimeException
{
    public static function resource(string $key): self
    {
        return new self("Resource [{$key}] adalah resource inti dan tidak bisa dihapus.");
    }

    public static function permission(string $name): self
    {
        return new self("Permission [{$name}] adalah permission inti dan tidak bisa dihapus.");
    }

    public static function role(string $name): self
    {
        return new self("Role [{$name}] adalah role inti dan tidak bisa dihapus.");
    }

    public static function mapping(string $key): self
    {
        return new self("Pemetaan untuk key [{$key}] terkunci dan tidak bisa diubah.");
    }
}
