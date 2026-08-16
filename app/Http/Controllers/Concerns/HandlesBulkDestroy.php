<?php

namespace App\Http\Controllers\Concerns;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Penghapusan massal dari daftar terpilih di tabel.
 *
 * Penjaga yang berlaku untuk satu baris harus berlaku juga untuk sepuluh baris,
 * jadi controller mengirimkan cara menghapusnya sebagai satu closure dan cara
 * yang sama dipakai `destroy()` maupun `bulkDestroy()`. Baris yang ditolak
 * dilewati dan alasannya dilaporkan — bukan membatalkan seluruh permintaan,
 * yang membuat pengguna harus menebak baris mana yang bermasalah.
 */
trait HandlesBulkDestroy
{
    /**
     * @param  class-string<Model>  $modelClass
     * @param  ?Closure(Model): ?string  $deleter  Menghapus satu baris dan
     *                                             mengembalikan null kalau berhasil, atau alasan penolakan kalau
     *                                             tidak. Baku: memanggil $model->delete() langsung.
     */
    protected function destroyMany(
        Request $request,
        string $modelClass,
        string $redirectRoute,
        ?Closure $deleter = null,
    ): RedirectResponse {
        $table = (new $modelClass)->getTable();

        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', Rule::exists($table, 'id')],
        ]);

        $deleter ??= function (Model $model): null {
            $model->delete();

            return null;
        };

        $deleted = 0;
        $reasons = [];

        foreach ($modelClass::query()->whereKey($validated['ids'])->get() as $model) {
            $reason = $deleter($model);

            if ($reason !== null) {
                $reasons[] = $reason;

                continue;
            }

            $deleted++;
        }

        $response = redirect()->route($redirectRoute);

        if ($deleted > 0) {
            $response->with('success', $deleted.' data dihapus.');
        }

        if ($reasons !== []) {
            $response->with($deleted > 0 ? 'warning' : 'error', implode(' ', array_unique($reasons)));
        }

        return $response;
    }
}
