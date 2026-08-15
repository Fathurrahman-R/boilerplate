<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->string('group')->nullable();
            $table->text('description')->nullable();

            // Resource inti (users, roles, dst). Tidak bisa dihapus lewat UI
            // supaya admin tidak bisa mengunci dirinya sendiri di luar panel.
            $table->boolean('is_locked')->default(false);

            $table->timestamps();

            $table->index('group');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
