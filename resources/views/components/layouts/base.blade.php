@props([
    'title' => null,
    // 'page'  — halaman publik dan auth: permukaan rata.
    // 'shell' — di dalam aplikasi: semburat aksen lembut, supaya material
    //           punya sesuatu yang bergradasi untuk dibiaskan.
    'backdrop' => 'page',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth" data-theme="light">
<head>
    <meta charset="utf-8">
    {{-- viewport-fit=cover membuat env(safe-area-inset-*) punya nilai, jadi
         toolbar dan bar seleksi tidak tertimpa notch atau home indicator. --}}
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title.' — '.config('app.name') : config('app.name') }}</title>

    @include('layouts.partials.theme-script')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>
@php($shell = $backdrop === 'shell')

<body @class([
    'min-h-screen font-sans text-body text-ink antialiased',
    'bg-shell' => $shell,
    'bg-surface' => ! $shell,
])>
    {{--
        x-data kosong di pembungkus ini bukan formalitas: Alpine hanya
        memproses elemen yang punya leluhur ber-x-data. Tanpanya, setiap
        x-on:click="$dispatch(…)" yang berdiri sendiri — pemicu modal di
        tabel, tombol ciut sidebar, tombol ⌘K di toolbar — diam saja tanpa
        error.

        data-app-root menandai lapisan yang harus didorong mundur dan
        dinonaktifkan saat lembar yang memblokir terbuka.
    --}}
    <div x-data data-app-root class="relative">
        {{ $slot }}
    </div>

    <x-ui.toast />

    @stack('scripts')
</body>
</html>
