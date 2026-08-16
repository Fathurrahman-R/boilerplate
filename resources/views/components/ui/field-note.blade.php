@props([
    'id' => null,
    'error' => null,
    'hint' => null,
])

{{--
    Baris di bawah field. Error menggantikan hint, bukan menumpuk di atasnya —
    saat ada yang salah, satu-satunya yang perlu dibaca adalah cara membetulkannya.
--}}

@if ($error)
    <p @if ($id) id="{{ $id }}" @endif
       class="mt-1.5 flex items-center gap-1.5 text-[12.5px] text-danger">
        <x-ui.icon name="circle-alert" class="size-3.5 shrink-0" />
        {{ $error }}
    </p>
@elseif ($hint)
    <p class="mt-1.5 text-[12.5px] text-ink-muted">{{ $hint }}</p>
@endif
