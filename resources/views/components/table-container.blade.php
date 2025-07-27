@props(['title', 'subtitle', 'createRoute', 'createText'])

<div {{ $attributes }}>
    <!-- Panel Superior -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-navy-800">{{ $title }}</h2>
            <p class="text-gray-600 mt-1">{{ $subtitle }}</p>
        </div>
        @if(isset($createRoute))
        <a href="{{ $createRoute }}" class="px-5 py-2.5 bg-gradient-to-r from-teal-500 to-teal-600 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            {{ $createText ?? 'Crear nuevo' }}
        </a>
        @endif
    </div>
    
    <!-- Filtros y búsqueda -->
    <div class="mb-6 p-4 bg-white rounded-lg shadow-sm border border-gray-100">
        <div class="flex flex-wrap gap-4 items-center">
            <div class="flex-1 min-w-[250px]">
                <form action="{{ url()->current() }}" method="GET" id="search-form">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" name="search" placeholder="Buscar..." value="{{ request('search') }}" 
                              class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        
                        @if(request('sort_by'))
                            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                        @endif
                        
                        @if(request('sort_dir'))
                            <input type="hidden" name="sort_dir" value="{{ request('sort_dir') }}">
                        @endif
                    </div>
                </form>
            </div>
            
            <!-- Slot para filtros adicionales específicos de la tabla -->
            @if(isset($filters))
                {{ $filters }}
            @endif
            
            <button type="submit" form="search-form" class="p-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
            
            @if(request('search') || request('status') || request('sort_by'))
                <a href="{{ url()->current() }}" class="p-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            @endif
        </div>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        {{ $slot }}
        
        <!-- Paginación -->
        @if(isset($pagination))
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                {{ $pagination }}
            </div>
        @endif
    </div>
</div>