<div {{ $attributes->merge([
        'class' => 'rounded-lg bg-white p-5 shadow-sm',
    ]) }}>
    {{ $slot }}
</div>
