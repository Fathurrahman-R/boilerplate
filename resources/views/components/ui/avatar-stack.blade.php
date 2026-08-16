@props([
    // Koleksi user (apa pun yang punya avatarUrl() dan name).
    'users' => [],
    'limit' => 4,
])

@php
    $users = collect($users);
    $shown = $users->take($limit);
    $rest = $users->count() - $shown->count();
@endphp

{{-- Cincin seukuran permukaan di belakangnya, bukan garis: itu yang membuat
     tumpukan terbaca sebagai kartu yang saling menimpa. --}}

<div {{ $attributes->class('flex items-center') }}>
    @foreach ($shown as $user)
        <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" title="{{ $user->name }}"
             class="size-[34px] rounded-full object-cover ring-2 ring-surface-raised {{ $loop->first ? '' : '-ms-2.5' }}">
    @endforeach

    @if ($rest > 0)
        <span class="-ms-2.5 flex size-[34px] items-center justify-center rounded-full bg-accent-soft text-[12px] font-semibold text-accent ring-2 ring-surface-raised">
            +{{ $rest }}
        </span>
    @endif
</div>
