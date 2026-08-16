<?php

return [

    /*
    |---------------------------------------------------------------------
    | Halaman dokumentasi
    |---------------------------------------------------------------------
    |
    | Dokumentasi design system dan layar contohnya dirender dari komponen
    | Blade yang sama dengan yang dipakai aplikasi, jadi isinya selalu ikut
    | berubah saat komponennya berubah.
    |
    | Kalau dimatikan, route-nya tidak didaftarkan sama sekali — halamannya
    | membalas 404, bukan 403. Bawaannya aktif di mana pun kecuali produksi.
    |
    */

    // Catatan: berkas config dimuat sebelum environment aplikasi ditentukan,
    // jadi APP_ENV dibaca langsung dari env — bukan lewat app()->isProduction().
    'enabled' => env('DESIGN_SYSTEM_ENABLED', env('APP_ENV', 'production') !== 'production'),

    /*
    |---------------------------------------------------------------------
    | Layar contoh
    |---------------------------------------------------------------------
    |
    | Kunci array jadi bagian URL (/design-system/layar/{kunci}), nilainya
    | dipakai sebagai judul dan teks navigasi. Menambah layar baru cukup
    | menambah satu entri di sini dan satu berkas di
    | resources/views/design-system/screens/.
    |
    */

    'screens' => [
        'dashboard' => [
            'title' => 'Dashboard analitik',
            'summary' => 'Shell aplikasi · sidebar kaca · baris metrik · tabel',
        ],
        'landing' => [
            'title' => 'SaaS landing page',
            'summary' => 'Hero kaca · fitur · harga · penutup',
        ],
        'internal-tool' => [
            'title' => 'Internal tool',
            'summary' => 'Daftar + panel detail · kepadatan tinggi',
        ],
        'settings' => [
            'title' => 'Pengaturan',
            'summary' => 'Form panjang · tab · aksi berisiko',
        ],
        'auth' => [
            'title' => 'Masuk',
            'summary' => 'Kartu terpusat di atas latar bertekstur',
        ],
    ],

];
