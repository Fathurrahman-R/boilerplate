@props([
    'title' => null,
    'texture' => true,
    // 'page' — halaman publik: permukaan rata + grid + butiran noise.
    // 'shell' — di dalam aplikasi: semburat aksen + grid saja. Noise sengaja
    //           tidak ikut; di balik panel kaca butirannya hanya menambah
    //           dengung tanpa menolong keterbacaan.
    'backdrop' => 'page',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title.' — '.config('app.name') : config('app.name') }}</title>

    @include('layouts.partials.theme-script')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>
@php($shell = $backdrop === 'shell')

<body @class([
    'min-h-screen font-sans text-body leading-relaxed text-ink antialiased',
    'bg-shell' => $shell,
    'bg-surface' => ! $shell,
])>
    {{--
        Grid dan noise bukan hiasan: kaca hanya terbaca sebagai kaca kalau ada
        sesuatu di belakangnya untuk dibiaskan. Di atas warna rata, panel kaca
        cuma jadi kotak abu-abu.
    --}}
    @if ($texture)
        <div class="bg-grid pointer-events-none fixed inset-0 z-0" aria-hidden="true"></div>

        @unless ($shell)
            <div class="bg-noise pointer-events-none fixed inset-0 z-0" aria-hidden="true"></div>
        @endunless
    @endif

    {{--
        x-data kosong di pembungkus ini bukan formalitas: Alpine hanya
        memproses elemen yang punya leluhur ber-x-data. Tanpanya, setiap
        x-on:click="$dispatch(…)" yang berdiri sendiri — pemicu modal di tabel,
        tombol ciut sidebar, tombol ⌘K di topbar — diam saja tanpa error.
    --}}
    <div x-data class="relative z-10">
        {{ $slot }}
    </div>

    <x-ui.toast />

    @stack('scripts')
</body>
</html>
