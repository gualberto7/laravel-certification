<button {{ $attributes->merge([
        'class' => 'rounded-md bg-sky-500 text-white py-1 px-3 cursor-pointer hover:bg-sky-600',
    ]) }}>
    {{ $slot }}
</button>
