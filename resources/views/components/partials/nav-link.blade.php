@props(['item'])

{{--
    Satu baris di daftar sumber.

    Yang aktif ditandai isian penuh, bukan warna teks saja: pil yang mengisi
    seluruh baris terbaca sebagai "kamu di sini", sementara teks yang sekadar
    berganti warna gampang tertukar dengan tautan yang sedang disorot.
--}}

<a href="{{ $item['url'] ?? '#' }}"
   title="{{ $item['label'] }}"
   data-rail="center"
   @if ($item['active'] ?? false) aria-current="page" @endif
   @class([
       'flex items-center gap-2.5 overflow-hidden rounded-md px-2.5 py-2 text-base whitespace-nowrap',
       'transition-colors duration-[--dur-fast] focus-visible:outline-none',
       'bg-accent text-accent-on font-semibold' => $item['active'] ?? false,
       'text-ink-secondary hover:bg-fill-3 hover:text-ink' => ! ($item['active'] ?? false),
   ])>
    @if ($item['icon'] ?? null)
        <x-ui.icon :name="$item['icon']" class="size-[17px] shrink-0" />
    @endif

    <span class="flex-1 truncate" data-rail="hide">{{ $item['label'] }}</span>

    @if ($item['badge'] ?? null)
        <span @class([
            'shrink-0 rounded-full px-[7px] py-px text-xs font-semibold',
            'bg-accent-on/20 text-accent-on' => $item['active'] ?? false,
            'bg-warning-soft text-warning' => ! ($item['active'] ?? false),
        ]) data-rail="hide">{{ $item['badge'] }}</span>
    @endif
</a>
