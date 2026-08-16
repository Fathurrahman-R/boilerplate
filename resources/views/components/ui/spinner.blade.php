@props(['size' => 'md'])

@php
    $sizes = ['sm' => 'size-4', 'md' => 'size-6', 'lg' => 'size-10'];
@endphp

<span role="status" {{ $attributes->class(['inline-flex text-accent', $sizes[$size] ?? $sizes['md']]) }}>
    <x-ui.icon name="loader-circle" class="size-full animate-spin" />
    <span class="sr-only">Memuat…</span>
</span>
