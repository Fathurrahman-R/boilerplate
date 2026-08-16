<?php

/*
 * Halaman dokumentasi merender seluruh komponen ui/ sekaligus, jadi test ini
 * sekaligus jadi jaring pengaman: satu komponen yang rusak — nama ikon salah,
 * prop hilang, slot berubah — langsung memerahkan salah satu test di sini.
 */

it('membuka halaman dokumentasi design system', function (string $route) {
    $this->get(route($route))->assertOk();
})->with([
    'design-system.foundation',
    'design-system.components',
    'design-system.patterns',
]);

// Daftarnya sengaja ditulis ulang di sini, bukan dibaca dari config: dataset
// Pest sudah harus ada sebelum aplikasi di-boot. Test terakhir di berkas ini
// yang menjaga keduanya tetap sama.
it('membuka setiap layar contoh', function (string $screen) {
    $this->get(route('design-system.screen', $screen))->assertOk();
})->with(['dashboard', 'landing', 'internal-tool', 'settings', 'auth']);

it('menguji seluruh layar yang terdaftar di config', function () {
    expect(array_keys(config('design-system.screens')))
        ->toEqualCanonicalizing(['dashboard', 'landing', 'internal-tool', 'settings', 'auth']);
});

it('menolak layar contoh yang tidak terdaftar', function () {
    $this->get(route('design-system.screen', 'tidak-ada'))->assertNotFound();
});

it('tidak butuh login — halamannya tidak menyentuh database maupun sesi', function () {
    $this->assertGuest();

    $this->get(route('design-system.foundation'))->assertOk();
    $this->get(route('design-system.screen', 'dashboard'))->assertOk();
});
