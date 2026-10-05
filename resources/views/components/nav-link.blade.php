@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-blue-600 text-sm font-bold leading-5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-semibold leading-5 text-slate-500 hover:text-blue-700 hover:border-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes, 'aria-current' => ($active ?? false) ? 'page' : null]) }}>
    {{ $slot }}
</a>
