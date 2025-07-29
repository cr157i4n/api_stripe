@extends('client.layouts.master')

@section('title', 'Nueva Caja')

@section('page-title', 'Crear Nueva Caja')
@section('page-subtitle', 'Registra una nueva caja registradora para tu comercio')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Estado actual -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Estado Actual de Cajas</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $merchant->max_cash_register }}</div>
                <div class="text-sm text-gray-600">Total Permitidas</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ $merchant->active_cash_register }}</div>
                <div class="text-sm text-gray-600">Activas</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600">{{ $merchant->max_cash_register - $merchant->active_cash_register }}</div>
                <div class="text-sm text-gray-600">Disponibles</div>
            </div>
        </div>

        @if($merchant->max_cash_register <= $merchant->active_cash_register)
        <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Límite Alcanzado</h3>
                    <p class="text-sm text-red-700 mt-1">Has alcanzado el número máximo de cajas permitidas. Elimina una caja existente para poder crear una nueva.</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Formulario para crear caja -->
    @if($merchant->max_cash_register > $merchant->active_cash_register)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Información de la Nueva Caja</h3>
        
        <form action="{{ route('client.cashiers.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de la Caja <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                           placeholder="Ej: Caja Principal, Caja 1, Mostrador"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Este nombre te ayudará a identificar la caja en el sistema.</p>
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                        Ubicación
                    </label>
                    <input type="text" 
                           id="location" 
                           name="location" 
                           value="{{ old('location') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('location') border-red-500 @enderror"
                           placeholder="Ej: Entrada principal, Piso 2, Sucursal Centro">
                    @error('location')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Especifica dónde estará ubicada físicamente la caja.</p>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción (Opcional)
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                              placeholder="Describe las características especiales de esta caja o su uso específico...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-blue-800">¿Qué sucede después?</h4>
                        <ul class="mt-1 text-sm text-blue-700 space-y-1">
                            <li>• Se creará la caja en tu sistema</li>
                            <li>• Podrás generar un código QR para vincular el dispositivo</li>
                            <li>• El código tendrá una validez de 24 horas</li>
                            <li>• Una vez vinculado, podrás empezar a procesar pagos</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <a href="{{ route('client.cashiers.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver a Cajas
                </a>
                
                <button type="submit" 
                        class="inline-flex items-center px-6 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Crear Caja
                </button>
            </div>
        </form>
    </div>
    @else
    <!-- Mensaje cuando se alcanza el límite -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
            <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
        </div>
        
        <h3 class="text-xl font-bold text-gray-900 mb-2">Límite de Cajas Alcanzado</h3>
        <p class="text-gray-600 mb-6">
            Has alcanzado el límite máximo de {{ $merchant->max_cash_register }} cajas permitidas en tu plan actual.
        </p>
        
        <div class="flex flex-col sm:flex-row items-center justify-center space-y-3 sm:space-y-0 sm:space-x-4">
            <a href="{{ route('client.cashiers.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Gestionar Cajas Existentes
            </a>
            
            <a href="{{ route('client.profile.upgrade') ?? '#' }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                </svg>
                Actualizar Plan
            </a>
        </div>
    </div>
    @endif

    <!-- Información adicional -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">¿Necesitas ayuda?</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <h5 class="font-medium text-gray-900 mb-2">📝 Nomenclatura sugerida:</h5>
                <ul class="space-y-1 text-gray-600">
                    <li>• Caja 1, Caja 2, Caja 3...</li>
                    <li>• Mostrador Principal</li>
                    <li>• Caja Express</li>
                    <li>• Autoservicio</li>
                </ul>
            </div>
            <div>
                <h5 class="font-medium text-gray-900 mb-2">📍 Ejemplos de ubicación:</h5>
                <ul class="space-y-1 text-gray-600">
                    <li>• Entrada principal</li>
                    <li>• Piso 2 - Sección A</li>
                    <li>• Sucursal Centro</li>
                    <li>• Área de farmacia</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('name').addEventListener('input', function() {
    const value = this.value.trim();
    if (value.length < 3) {
        this.classList.add('border-red-500');
        this.classList.remove('border-gray-300');
    } else {
        this.classList.remove('border-red-500');
        this.classList.add('border-gray-300');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('name').focus();
});
</script>
@endsection