@extends('client.layouts.master')

@section('title', 'Generar Código de Vinculación')

@section('page-title', 'Generar Código de Vinculación')
@section('page-subtitle', 'Crea un código para vincular una nueva caja registradora')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Estado actual -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Estado Actual de Cajas</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $commerce->max_cash_registers }}</div>
                <div class="text-sm text-gray-600">Total Permitidas</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ $activeDevices }}</div>
                <div class="text-sm text-gray-600">Activas</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-600">{{ $commerce->max_cash_registers - $activeDevices }}</div>
                <div class="text-sm text-gray-600">Disponibles</div>
            </div>
        </div>

        @if($commerce->max_cash_registers <= $activeDevices)
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Límite Alcanzado</h3>
                    <p class="text-sm text-red-700 mt-1">Has alcanzado el número máximo de cajas permitidas. Desactiva una caja existente para poder vincular una nueva.</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Formulario para generar código -->
    @if($commerce->max_cash_registers > $activeDevices)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Generar Nuevo Código</h3>
        
        <form action="{{ route('client.devices.generate-code') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="device_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de la Caja <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="device_name" 
                           name="device_name" 
                           value="{{ old('device_name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('device_name') border-red-500 @enderror"
                           placeholder="Ej: Caja Principal, Caja 1"
                           required>
                    @error('device_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                        Ubicación
                    </label>
                    <input type="text" 
                           id="location" 
                           name="location" 
                           value="{{ old('location') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ej: Entrada principal, Piso 2">
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Descripción (Opcional)
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Describe para qué usarás esta caja">{{ old('description') }}</textarea>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-blue-800">Instrucciones</h4>
                        <ul class="mt-1 text-sm text-blue-700 space-y-1">
                            <li>• El código generado será válido por 24 horas</li>
                            <li>• Ingresa este código en tu dispositivo/caja para vincularlo</li>
                            <li>• Una vez vinculado, el código se vuelve inválido</li>
                            <li>• Puedes generar múltiples códigos si necesitas</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4">
                <a href="{{ route('client.dashboard') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Generar Código
                </button>
            </div>
        </form>
    </div>
    @endif

    <!-- Códigos activos -->
    @if($activeCodes->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Códigos Activos</h3>
        
        <div class="space-y-4">
            @foreach($activeCodes as $code)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-4">
                            <div class="bg-blue-100 rounded-lg p-3">
                                <span class="text-2xl font-mono font-bold text-blue-800">{{ $code->code }}</span>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $code->device_name }}</h4>
                                @if($code->location)
                                    <p class="text-sm text-gray-600">📍 {{ $code->location }}</p>
                                @endif
                                <p class="text-sm text-gray-500">
                                    Expira: {{ $code->expires_at->format('d/m/Y H:i') }}
                                    ({{ $code->expires_at->diffForHumans() }})
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="copyCode('{{ $code->code }}')" 
                                class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200 transition-colors">
                            Copiar
                        </button>
                        <form action="{{ route('client.devices.revoke-code', $code->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('¿Estás seguro de revocar este código?')"
                                    class="px-3 py-2 bg-red-100 border border-red-300 rounded-md text-sm font-medium text-red-700 hover:bg-red-200 transition-colors">
                                Revocar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>

</script>
@endsection