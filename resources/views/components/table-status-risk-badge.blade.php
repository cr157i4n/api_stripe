@props(['risk' => null])

@if ($risk === 'low')
    <span
        class="px-3 py-1 inline-flex items-center gap-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
        Bajo
    </span>
@elseif($risk === 'medium')
    <span
        class="px-3 py-1 inline-flex items-center gap-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800 border border-orange-200">
        <span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>
        Medium
    </span>
@elseif($risk === 'high')
    <span
        class="px-3 py-1 inline-flex items-center gap-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
        Alto
    </span>
@else
    <span
        class="px-3 py-1 inline-flex items-center gap-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
        <span class="h-1.5 w-1.5 rounded-full bg-gray-500"></span>
        Desconocido
    </span>
@endif
