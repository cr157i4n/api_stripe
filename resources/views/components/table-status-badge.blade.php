@props(['active' => false])

@if($active)
    <span class="px-3 py-1 inline-flex items-center gap-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
        Activo
    </span>
@else
    <span class="px-3 py-1 inline-flex items-center gap-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
        Inactivo
    </span>
@endif