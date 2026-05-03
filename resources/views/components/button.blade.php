{{--
    Button Component

    @props:
    - $variant (optional) - 'primary', 'secondary', 'danger', 'ghost', 'success' — default: 'primary'
    - $size (optional) - 'sm', 'md', 'lg' — default: 'md'
    - $icon (optional) - Lucide icon name
    - $href (optional) - If set, renders as <a> tag
    - $type (optional) - Button type, default: 'button'
--}}

@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'href' => null,
    'type' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 font-semibold rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white cursor-pointer';

    $variants = [
        'primary'   => 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500 shadow-sm',
        'secondary' => 'bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 shadow-sm focus:ring-slate-300',
        'danger'    => 'bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 focus:ring-red-500',
        'ghost'     => 'hover:bg-slate-100 text-slate-600 hover:text-slate-900 focus:ring-slate-300',
        'success'   => 'bg-emerald-600 hover:bg-emerald-700 text-white focus:ring-emerald-500 shadow-sm',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2.5 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
        @endif
        {{ $slot }}
    </button>
@endif
