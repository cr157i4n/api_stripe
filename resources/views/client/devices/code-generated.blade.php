@extends('client.layouts.master')

@section('title', 'Código Generado')

@section('page-title', 'Código de Vinculación Generado')
@section('page-subtitle', 'Tu código ha sido creado exitosamente')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Mensaje de éxito -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">¡Código generado exitosamente!</h3>
                    <p class="text-sm text-green-700 mt-1">El código de vinculación ha sido creado y está listo para usar.
                    </p>
                </div>
            </div>
        </div>

        <!-- Código generado -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <div class="text-center">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Código de Vinculación</h2>
                    <p class="text-gray-600">Usa este código en tu dispositivo para vincularlo</p>
                </div>

                <!-- Código destacado -->
                <div class="bg-blue-50 border-2 border-blue-200 rounded-2xl p-8 mb-6">
                    <div class="text-6xl font-mono font-bold text-blue-600 mb-4 tracking-wider">
                        {{ $linkingCode->code }}
                    </div>
                    <button onclick="copyCode('{{ $linkingCode->code }}')" id="copyButton"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Copiar Código
                    </button>
                </div>

                <!-- Información del dispositivo -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información del Dispositivo</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre:</label>
                            <p class="text-gray-900">{{ $linkingCode->device_name }}</p>
                        </div>
                        @if ($linkingCode->location)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ubicación:</label>
                                <p class="text-gray-900">{{ $linkingCode->location }}</p>
                            </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Creación:</label>
                            <p class="text-gray-900">{{ $linkingCode->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expira:</label>
                            <p class="text-gray-900">{{ $linkingCode->expires_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @if ($linkingCode->description)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción:</label>
                            <p class="text-gray-900">{{ $linkingCode->description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Instrucciones -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Instrucciones de Vinculación
            </h3>

            <ol class="space-y-4">
                <li class="flex items-start">
                    <span
                        class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium mr-3">1</span>
                    <div>
                        <h4 class="font-medium text-gray-900">Accede a tu dispositivo/caja</h4>
                        <p class="text-sm text-gray-600 mt-1">Abre la aplicación o sistema de punto de venta en tu
                            dispositivo.</p>
                    </div>
                </li>

                <li class="flex items-start">
                    <span
                        class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium mr-3">2</span>
                    <div>
                        <h4 class="font-medium text-gray-900">Busca la opción de vinculación</h4>
                        <p class="text-sm text-gray-600 mt-1">Busca la opción "Vincular dispositivo", "Conectar a comercio"
                            o similar.</p>
                    </div>
                </li>

                <li class="flex items-start">
                    <span
                        class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium mr-3">3</span>
                    <div>
                        <h4 class="font-medium text-gray-900">Ingresa el código</h4>
                        <p class="text-sm text-gray-600 mt-1">Introduce el código <strong
                                class="font-mono">{{ $linkingCode->code }}</strong> cuando te lo solicite.</p>
                    </div>
                </li>

                <li class="flex items-start">
                    <span
                        class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium mr-3">4</span>
                    <div>
                        <h4 class="font-medium text-gray-900">Confirma la vinculación</h4>
                        <p class="text-sm text-gray-600 mt-1">El dispositivo se conectará automáticamente y aparecerá en tu
                            panel de administración.</p>
                    </div>
                </li>
            </ol>
        </div>

        <!-- Información importante -->
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-amber-800">Información Importante</h4>
                    <ul class="mt-1 text-sm text-amber-700 space-y-1">
                        <li>• Este código expira el {{ $linkingCode->expires_at->format('d/m/Y') }} a las
                            {{ $linkingCode->expires_at->format('H:i') }}</li>
                        <li>• Una vez usado, el código se vuelve inválido automáticamente</li>
                        <li>• Solo un dispositivo puede usar este código</li>
                        <li>• Si tienes problemas, puedes generar un nuevo código</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <div class="flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4">
            <a href="{{ route('commerce.devices.index') }}"
                class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Ver Mis Cajas
            </a>

            <a href="{{ route('commerce.devices.create-code') }}"
                class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Generar Otro Código
            </a>

            <a href="{{ route('commerce.dashboard') }}"
                class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Ir al Dashboard
            </a>
        </div>
    </div>

    <script>
        function copyCode(code) {
            const button = document.getElementById('copyButton');

        }
    </script>
@endsection
