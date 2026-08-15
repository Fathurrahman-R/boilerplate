<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel pemetaan: satu resource key (resource + aksi) menunjuk tepat satu
 * permission Spatie.
 *
 * Kolom permission_id sengaja nullable. Menghapus permission tidak menghapus
 * baris ini, hanya mengosongkan penunjuknya — resource key-nya tetap ada,
 * berstatus "tak terpetakan", dan aksesnya tertutup. Kalau baris ini ikut
 * terhapus, key akan lenyap diam-diam dan route yang memakainya berubah
 * jadi "key tidak dikenal" tanpa jejak.
 */
return new class extends Migration
{
    public function up(): void
    {
        $permissionsTable = config('permission.table_names.permissions', 'permissions');

        Schema::create('resource_permissions', function (Blueprint $table) use ($permissionsTable) {
            $table->id();

            $table->foreignId('resource_id')->constrained('resources')->cascadeOnDelete();
            $table->string('action');

            $table->foreignId('permission_id')->nullable()
                ->constrained($permissionsTable)
                ->nullOnDelete();

            $table->boolean('is_locked')->default(false);
            $table->timestamps();

            $table->unique(['resource_id', 'action']);
            $table->index('permission_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_permissions');
    }
};
