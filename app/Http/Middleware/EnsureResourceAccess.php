<?php

namespace App\Http\Middleware;

use App\Support\Resources\ResourceGate;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menjaga route dengan resource key.
 *
 *   ->middleware('resource:posts.view')                 satu key
 *   ->middleware('resource:posts.update|posts.publish') salah satu cukup (ATAU)
 *   ->middleware('resource:posts.view,posts.export')    harus punya keduanya (DAN)
 *
 * Ringkasnya: koma berarti DAN, garis tegak berarti ATAU. Keduanya boleh
 * dipakai bersamaan — tiap segmen yang dipisah koma wajib terpenuhi, dan
 * sebuah segmen terpenuhi bila salah satu key di dalamnya diizinkan.
 */
class EnsureResourceAccess
{
    public function __construct(private readonly ResourceGate $gate) {}

    public function handle(Request $request, Closure $next, string ...$segments): Response
    {
        if ($request->user() === null) {
            return $request->expectsJson()
                ? abort(401)
                : redirect()->guest(route('login'));
        }

        foreach ($segments as $segment) {
            $keys = array_filter(array_map('trim', explode('|', $segment)));

            if (! $this->gate->any($keys, $request->user())) {
                abort(403, 'Anda tidak punya akses ke halaman ini.');
            }
        }

        return $next($request);
    }
}
