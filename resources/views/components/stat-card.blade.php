{{--
    Stat Card Component

    @props:
    - $title - Stat label
    - $value - Stat value
    - $icon - Lucide icon name
    - $trend (optional) - 'up' or 'down'
    - $trendValue (optional) - e.g. '+12%'
    - $color (optional) - 'brand', 'emerald', 'amber', 'red' — default: 'brand'
--}}

@props([
    'title',
    'value',
    'icon',
    'trend' => null,
    'trendValue' => null,
    'color' => 'brand',
])

@php
    $colors = [
        'brand'   => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'ring' => 'ring-blue-100'],
        'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'ring' => 'ring-emerald-100'],
        'amber'   => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'ring' => 'ring-amber-100'],
        'red'     => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'ring' => 'ring-red-100'],
        'purple'  => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'ring' => 'ring-purple-100'],
    ];
    $c = $colors[$color] ?? $colors['brand'];
@endphp

<div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow duration-300 group">
    <div class="flex items-start justify-between mb-4">
        <div class="w-11 h-11 {{ $c['bg'] }} rounded-xl flex items-center justify-center ring-1 {{ $c['ring'] }} group-hover:scale-110 transition-transform duration-300">
            <i data-lucide="{{ $icon }}" class="w-5 h-5 {{ $c['text'] }}"></i>
        </div>
        @if($trend)
            <div class="flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium {{ $trend === 'up' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                <i data-lucide="{{ $trend === 'up' ? 'trending-up' : 'trending-down' }}" class="w-3 h-3"></i>
                {{ $trendValue }}
            </div>
        @endif
    </div>
    <p class="text-2xl font-bold text-slate-800 tracking-tight">{{ $value }}</p>
    <p class="text-sm text-slate-500 mt-1">{{ $title }}</p>
</div>
