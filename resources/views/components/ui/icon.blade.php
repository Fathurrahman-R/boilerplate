@props([
    'name',
    'class' => 'size-5',
])

{{--
    Pembungkus tipis di atas mallardduck/blade-lucide-icons.

    `name` adalah nama ikon Lucide apa adanya — lihat https://lucide.dev/icons.
    Semuanya dirender sebagai SVG inline, jadi tidak ada request tambahan dan
    warnanya ikut `currentColor`. Nama yang salah melempar exception saat
    render, bukan diam-diam kosong.

    Ukuran mengikuti sistem: 16 (size-4), 20 (size-5), 24 (size-6).
--}}

<x-dynamic-component :component="'lucide-'.$name"
                     {{ $attributes->class($class) }}
                     stroke-width="1.5"
                     aria-hidden="true" />
