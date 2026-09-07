@props([
    'variant' => 'primary',
])

@php
    $classes = match ($variant) {
        'primary' => 'rounded-md bg-blue-500 text-white py-1 px-3 cursor-pointer hover:bg-blue-600',
        'danger' => 'rounded-md bg-red-500 text-white py-1 px-3 cursor-pointer hover:bg-red-600',
        default => '',
    };
@endphp

<button {{ $attributes->merge([
        'class' => $classes,
    ]) }}>
    {{ $slot }}
</button>
