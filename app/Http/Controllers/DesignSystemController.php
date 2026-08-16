<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Dokumentasi design system yang dirender dari komponen Blade yang
 * sesungguhnya — bukan salinan statis. Setiap perubahan pada
 * resources/views/components/ui langsung terlihat di sini.
 *
 * Seluruh route-nya hanya terdaftar kalau config('design-system.enabled')
 * bernilai true; lihat routes/web.php.
 */
class DesignSystemController extends Controller
{
    public function foundation(): View
    {
        return view('design-system.foundation');
    }

    public function components(): View
    {
        return view('design-system.components');
    }

    public function patterns(): View
    {
        return view('design-system.patterns');
    }

    public function screen(string $screen): View
    {
        $screens = config('design-system.screens', []);

        if (! isset($screens[$screen])) {
            throw new NotFoundHttpException("Layar contoh [{$screen}] tidak ada.");
        }

        return view("design-system.screens.{$screen}", [
            'screen' => $screen,
            'meta' => $screens[$screen],
        ]);
    }
}
