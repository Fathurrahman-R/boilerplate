<?php

use App\Enums\ResourceAction;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Admin\ResourceMappingController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::prefix('profil')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::post('/avatar', [ProfileController::class, 'updateAvatar'])->name('avatar');
        Route::delete('/avatar', [ProfileController::class, 'destroyAvatar'])->name('avatar.destroy');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Panel admin
    |--------------------------------------------------------------------------
    |
    | Setiap route dijaga resource key lewat middleware `resource`. Koma berarti
    | DAN, garis tegak berarti ATAU. Key-nya sama persis dengan yang dipakai di
    | Blade dan menu, jadi satu perubahan pemetaan berlaku di semua tempat.
    |
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('resource:'.rk('users', ResourceAction::View));
            Route::get('/create', 'create')->name('create')->middleware('resource:'.rk('users', ResourceAction::Create));
            Route::post('/', 'store')->name('store')->middleware('resource:'.rk('users', ResourceAction::Create));
            Route::get('/{user}/edit', 'edit')->name('edit')->middleware('resource:'.rk('users', ResourceAction::Update));
            Route::put('/{user}', 'update')->name('update')->middleware('resource:'.rk('users', ResourceAction::Update));
            Route::delete('/{user}', 'destroy')->name('destroy')->middleware('resource:'.rk('users', ResourceAction::Delete));
            Route::get('/export', 'export')->name('export')->middleware('resource:'.rk('users', ResourceAction::Export));
        });

        Route::controller(RoleController::class)->prefix('roles')->name('roles.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('resource:'.rk('roles', ResourceAction::View));
            Route::get('/create', 'create')->name('create')->middleware('resource:'.rk('roles', ResourceAction::Create));
            Route::post('/', 'store')->name('store')->middleware('resource:'.rk('roles', ResourceAction::Create));
            Route::get('/{role}/edit', 'edit')->name('edit')->middleware('resource:'.rk('roles', ResourceAction::Update));
            Route::put('/{role}', 'update')->name('update')->middleware('resource:'.rk('roles', ResourceAction::Update));
            Route::delete('/{role}', 'destroy')->name('destroy')->middleware('resource:'.rk('roles', ResourceAction::Delete));
        });

        Route::controller(PermissionController::class)->prefix('permissions')->name('permissions.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('resource:'.rk('permissions', ResourceAction::View));
            Route::get('/create', 'create')->name('create')->middleware('resource:'.rk('permissions', ResourceAction::Create));
            Route::post('/', 'store')->name('store')->middleware('resource:'.rk('permissions', ResourceAction::Create));
            Route::get('/{permission}/edit', 'edit')->name('edit')->middleware('resource:'.rk('permissions', ResourceAction::Update));
            Route::put('/{permission}', 'update')->name('update')->middleware('resource:'.rk('permissions', ResourceAction::Update));
            Route::delete('/{permission}', 'destroy')->name('destroy')->middleware('resource:'.rk('permissions', ResourceAction::Delete));
        });

        Route::controller(ResourceController::class)->prefix('resources')->name('resources.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('resource:'.rk('resources', ResourceAction::View));
            Route::get('/create', 'create')->name('create')->middleware('resource:'.rk('resources', ResourceAction::Create));
            Route::post('/', 'store')->name('store')->middleware('resource:'.rk('resources', ResourceAction::Create));
            Route::get('/{resource}', 'show')->name('show')->middleware('resource:'.rk('resources', ResourceAction::View));
            Route::get('/{resource}/edit', 'edit')->name('edit')->middleware('resource:'.rk('resources', ResourceAction::Update));
            Route::put('/{resource}', 'update')->name('update')->middleware('resource:'.rk('resources', ResourceAction::Update));
            Route::delete('/{resource}', 'destroy')->name('destroy')->middleware('resource:'.rk('resources', ResourceAction::Delete));
        });

        Route::controller(ResourceMappingController::class)->prefix('mappings')->name('mappings.')->group(function () {
            Route::get('/', 'index')->name('index')->middleware('resource:'.rk('mappings', ResourceAction::View));
            Route::put('/{mapping}', 'update')->name('update')->middleware('resource:'.rk('mappings', ResourceAction::Update));
            Route::delete('/{mapping}', 'destroy')->name('destroy')->middleware('resource:'.rk('mappings', ResourceAction::Update));
            Route::post('/auto', 'autoMap')->name('auto')->middleware('resource:'.rk('mappings', ResourceAction::Update));
        });

        // Modul contoh. Otorisasinya lewat policy (PostPolicy), bukan middleware,
        // untuk menunjukkan cara kedua memakai resource key yang sama.
        Route::resource('posts', PostController::class)->except('show');
        Route::get('posts-export', [PostController::class, 'export'])->name('posts.export');
    });
});

// Route auth (login, register, reset password, verifikasi email, 2FA)
// didaftarkan otomatis oleh Fortify — lihat config/fortify.php untuk
// menyalakan atau mematikan fiturnya.
