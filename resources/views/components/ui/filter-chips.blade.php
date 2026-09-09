@props([
    // Nama query string yang dikendalikan chip ini.
    'param' => 'status',
    // ['nilai' => 'Label']
    'options' => [],
    // Label untuk keadaan "tidak difilter".
    'all' => 'Semua',
])

{{--
    Filter dengan sedikit pilihan: semuanya terbaca sekaligus dan langsung
 berlaku begitu diklik. Tiap chip adalah tautan biasa yang mempertahankan
 query lain dan mengembalikan halaman ke satu — kalau tidak, filter baru
 bisa mendarat di halaman 7 yang kosong.

    Yang aktif menonjol keluar; yang tidak aktif duduk di dalam lekukan.
--}}

@php
    $current = request()->query($param);
    $base = request()->query();
 unset($base['page']);

    $link = function (?string $value) use ($base, $param) {
        $query = $base;

 if ($value === null) {
 unset($query[$param]);
        } else {
            $query[$param] = $value;
        }

 return request()->url().($query === [] ? '' : '?'.http_build_query($query));
    };

    $chip = 'inline-flex items-center rounded-full px-3 py-[5px] text-sm transition-all duration-[--dur-fast]';
    $on = 'bg-accent font-semibold text-accent-on shadow-sm';
    $off = 'border-[0.5px] border-line bg-fill-4 text-ink-secondary hover:text-ink';
@endphp

<div {{ $attributes->class('flex flex-wrap items-center gap-1.5') }}>
    <a href="{{ $link(null) }}" class="{{ $chip }} {{ blank($current) ? $on : $off }}">{{ $all }}</a>

    @foreach ($options as $value => $label)
        <a href="{{ $link((string) $value) }}"
 class="{{ $chip }} {{ (string) $current === (string) $value ? $on : $off }}">{{ $label }}</a>
    @endforeach
</div>
