<?php

namespace App\Enums;

/**
 * Daftar tertutup nama aksi yang boleh muncul di sebuah resource key.
 *
 * Ini satu-satunya sumber kebenaran penamaan aksi. Admin memilih dari daftar
 * ini lewat centang di UI (tidak pernah mengetik namanya), dan developer
 * memakai case enum-nya di kode. Dua-duanya bermuara ke string yang sama,
 * jadi typo tidak punya jalan masuk.
 */
enum ResourceAction: string
{
    case View = 'view';
    case Create = 'create';
    case Update = 'update';
    case Delete = 'delete';
    case Restore = 'restore';
    case ForceDelete = 'force_delete';
    case Export = 'export';
    case Import = 'import';
    case Approve = 'approve';
    case Reject = 'reject';
    case Publish = 'publish';
    case Assign = 'assign';
    case Print = 'print';
    case Manage = 'manage';

    public function label(): string
    {
        return match ($this) {
            self::View => 'Lihat',
            self::Create => 'Tambah',
            self::Update => 'Ubah',
            self::Delete => 'Hapus',
            self::Restore => 'Pulihkan',
            self::ForceDelete => 'Hapus Permanen',
            self::Export => 'Ekspor',
            self::Import => 'Impor',
            self::Approve => 'Setujui',
            self::Reject => 'Tolak',
            self::Publish => 'Terbitkan',
            self::Assign => 'Tugaskan',
            self::Print => 'Cetak',
            self::Manage => 'Kelola',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::View => 'Membuka daftar dan detail data.',
            self::Create => 'Membuat data baru.',
            self::Update => 'Mengubah data yang sudah ada.',
            self::Delete => 'Memindahkan data ke tempat sampah.',
            self::Restore => 'Mengembalikan data yang sudah dihapus.',
            self::ForceDelete => 'Menghapus data secara permanen, tidak bisa dibatalkan.',
            self::Export => 'Mengunduh data sebagai berkas.',
            self::Import => 'Mengunggah data dari berkas.',
            self::Approve => 'Menyetujui pengajuan.',
            self::Reject => 'Menolak pengajuan.',
            self::Publish => 'Menerbitkan data sehingga terlihat publik.',
            self::Assign => 'Menugaskan data ke pengguna lain.',
            self::Print => 'Mencetak data.',
            self::Manage => 'Akses penuh atas resource ini.',
        };
    }

    /**
     * Aksi yang efeknya sulit atau tidak bisa dibatalkan. Dipakai UI untuk
     * memberi warna peringatan pada centang aksi.
     */
    public function isDestructive(): bool
    {
        return in_array($this, [self::Delete, self::ForceDelete], strict: true);
    }

    /**
     * Aksi yang biasanya dipakai modul CRUD standar, dipakai sebagai centang
     * awal di form pembuatan resource.
     */
    public function isDefault(): bool
    {
        return in_array($this, [self::View, self::Create, self::Update, self::Delete], strict: true);
    }

    /** @return array<int, string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /** @return array<string, string> nilai => label */
    public static function options(): array
    {
        return array_combine(self::values(), array_map(
            static fn (self $action): string => $action->label(),
            self::cases(),
        ));
    }
}
