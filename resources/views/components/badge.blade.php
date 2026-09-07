@props([
'active' => false,])

<div class="rounded-md {{ $active ? 'bg-blue-500 text-white' : 'bg-slate-200 text-slate-800' }} text-xs px-2 py-1">
    {{ $slot }}
</div>