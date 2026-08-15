<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Permission dan role di boilerplate ini dikelola lewat UI, jadi keduanya
 * butuh metadata yang layak ditampilkan ke manusia: label, pengelompokan,
 * dan penanda "inti" yang melindunginya dari penghapusan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->permissionsTable(), function (Blueprint $table) {
            $table->string('label')->nullable()->after('guard_name');
            $table->string('group')->nullable()->after('label');
            $table->text('description')->nullable()->after('group');
            $table->boolean('is_locked')->default(false)->after('description');
        });

        Schema::table($this->rolesTable(), function (Blueprint $table) {
            $table->string('label')->nullable()->after('guard_name');
            $table->text('description')->nullable()->after('label');
            $table->boolean('is_locked')->default(false)->after('description');
        });
    }

    public function down(): void
    {
        Schema::table($this->permissionsTable(), function (Blueprint $table) {
            $table->dropColumn(['label', 'group', 'description', 'is_locked']);
        });

        Schema::table($this->rolesTable(), function (Blueprint $table) {
            $table->dropColumn(['label', 'description', 'is_locked']);
        });
    }

    private function permissionsTable(): string
    {
        return config('permission.table_names.permissions', 'permissions');
    }

    private function rolesTable(): string
    {
        return config('permission.table_names.roles', 'roles');
    }
};
