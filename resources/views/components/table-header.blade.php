@props(['sortable' => false, 'field' => '', 'direction' => 'asc'])

@php
    $classes = 'px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider';
    $defaultDirection = $direction == 'asc' ? 'desc' : 'asc';
    
    $currentField = request('sort_by');
    $currentDirection = request('sort_dir', 'asc');
    
    $isActive = $sortable && $currentField === $field;
@endphp

<th scope="col" {{ $attributes->merge(['class' => $classes]) }}>
    @if($sortable)
        <a href="{{ request()->fullUrlWithQuery([
            'sort_by' => $field, 
            'sort_dir' => ($isActive ? ($currentDirection === 'asc' ? 'desc' : 'asc') : $defaultDirection)
        ]) }}" 
           class="group flex items-center justify-between">
            <span>{{ $slot }}</span>
            <span class="ml-2 flex items-center">
                @if($isActive)
                    @if($currentDirection === 'asc')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    @endif
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 opacity-0 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4" />
                    </svg>
                @endif
            </span>
        </a>
    @else
        {{ $slot }}
    @endif
</th>