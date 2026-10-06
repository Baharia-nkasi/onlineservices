@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-extrabold text-slate-800']) }}>
    {{ $value ?? $slot }}
</label>
