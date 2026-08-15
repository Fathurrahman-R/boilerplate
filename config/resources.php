<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Role Super Admin
    |--------------------------------------------------------------------------
    |
    | Role dengan nama ini melewati seluruh pengecekan resource key. Role-nya
    | tidak bisa dihapus lewat UI, dan pengguna terakhir yang menyandangnya
    | tidak bisa dicabut rolenya — supaya aplikasi tidak pernah kehilangan
    | jalan masuk.
    |
    */

    'super_admin_role' => env('SUPER_ADMIN_ROLE', 'super-admin'),

    /*
    |--------------------------------------------------------------------------
    | Cache Peta Resource
    |--------------------------------------------------------------------------
    |
    | Peta "resource key → nama permission" dibaca hampir di setiap request
    | (menu, tombol, middleware), jadi disimpan di cache. Setiap penulisan
    | lewat ResourceManager membersihkannya secara otomatis. Setel ttl ke null
    | untuk menyimpan selamanya sampai ada perubahan.
    |
    */

    'cache' => [
        'key' => 'resources.map',
        'ttl' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Berkas Konstanta Hasil Generate
    |--------------------------------------------------------------------------
    |
    | `php artisan resource:keys` membaca daftar key dari database lalu menulis
    | ulang berkas ini sebagai konstanta, sehingga IDE bisa melengkapi otomatis
    | dan key yang salah ketik ketahuan sebelum dijalankan.
    |
    */

    'generated_keys_path' => app_path('Support/Resources/ResourceKeys.php'),

    /*
    |--------------------------------------------------------------------------
    | Perilaku Key Tak Dikenal
    |--------------------------------------------------------------------------
    |
    | Key yang tidak ada di database, atau ada tapi belum dipetakan ke
    | permission mana pun, selalu ditolak (fail-closed). Setel 'log' ke false
    | kalau peringatan di log dirasa terlalu berisik.
    |
    */

    'log_unknown_keys' => env('RESOURCES_LOG_UNKNOWN_KEYS', true),

];
