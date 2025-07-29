@extends('admin.layouts.master')

@section('title', isset($commerce) ? 'Editar Comercio' : 'Crear Comercio')

@section('page-title', isset($commerce) ? 'Editar Comercio' : 'Crear Nuevo Comercio')
@section('page-subtitle', isset($commerce) ? 'Modifica la información del comercio' : 'Agrega un nuevo comercio a la pasarela de pago')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ isset($commerce) ? 'Editar Comercio' : 'Nuevo Comercio' }}
                </h3>
                <a href="{{ route('admin.branches.index') }}" 
                   class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver
                </a>
            </div>
        </div>

        <form action="{{ isset($commerce) ? route('admin.branches.update', $commerce->id) : route('admin.branches.store') }}" 
              method="POST" class="p-6">
            @csrf
            @if(isset($commerce))
                @method('PUT')
            @endif

           <!-- Información del Comercio -->
            <div class="mb-8">
                <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Información del Comercio
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nombre del Comercio -->
                    <div class="md:col-span-2">
                        <label for="business_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre del Comercio <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="business_name" 
                               name="business_name" 
                               value="{{ old('business_name', $commerce->business_name ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-500 focus:border-navy-500 @error('business_name') border-red-500 @enderror"
                               placeholder="Ingrese el nombre del comercio"
                               required>
                        @error('business_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIT -->
                    <div>
                        <label for="nit" class="block text-sm font-medium text-gray-700 mb-2">
                            NIT <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nit" 
                               name="nit" 
                               value="{{ old('nit', $commerce->nit ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-500 focus:border-navy-500 @error('nit') border-red-500 @enderror"
                               placeholder="Ingrese el NIT"
                               required>
                        @error('nit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Celular -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Número de Celular <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $commerce->phone ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-500 focus:border-navy-500 @error('phone') border-red-500 @enderror"
                               placeholder="Ej: 70123456"
                               required>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dirección -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Dirección <span class="text-red-500">*</span>
                        </label>
                        <textarea id="address" 
                                  name="address" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-500 focus:border-navy-500 @error('address') border-red-500 @enderror"
                                  placeholder="Ingrese la dirección completa del comercio"
                                  required>{{ old('address', $commerce->address ?? '') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NUEVOS CAMPOS DE CAJAS REGISTRADORAS -->
                    
                    <!-- Cantidad Máxima de Cajas -->
                    <div>
                        <label for="max_cash_registers" class="block text-sm font-medium text-gray-700 mb-2">
                            Cajas Permitidas <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="max_cash_registers" 
                                   name="max_cash_registers" 
                                   value="{{ old('max_cash_registers', $commerce->max_cash_registers ?? 1) }}"
                                   min="1" 
                                   max="50"
                                   class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-500 focus:border-navy-500 @error('max_cash_registers') border-red-500 @enderror"
                                   placeholder="Ej: 5"
                                   required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        @error('max_cash_registers')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Máximo de cajas que podrá usar (1-50)
                        </p>
                    </div>

                    <!-- Cajas Actualmente Activas -->
                    <div>
                        <label for="active_cash_registers" class="block text-sm font-medium text-gray-700 mb-2">
                            Cajas Activas
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="active_cash_registers" 
                                   name="active_cash_registers" 
                                   value="{{ old('active_cash_registers', $commerce->active_cash_registers ?? 0) }}"
                                   min="0" 
                                   max="50"
                                   class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-500 focus:border-navy-500 @error('active_cash_registers') border-red-500 @enderror"
                                   placeholder="Ej: 2">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        @error('active_cash_registers')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Cajas actualmente en uso
                        </p>
                    </div>
                </div>
                
                <!-- Información adicional sobre las cajas -->
                <div class="mt-4 bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="ml-3">
                            <h5 class="text-sm font-medium text-amber-800">Información sobre las cajas registradoras</h5>
                            <ul class="mt-1 text-xs text-amber-700 space-y-1">
                                <li>• El número de cajas permitidas define cuántas pueden estar registradas</li>
                                <li>• Las cajas activas son las que están funcionando actualmente</li>
                                <li>• El comercio podrá activar/desactivar cajas según su operación</li>
                                <li>• Cada caja puede generar QR de pago de forma independiente</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Credenciales de  -->
            <div class="mb-8">
                <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    Credenciales de Acceso al Sistema
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Usuario  -->
                    <div>
                        <label for="api_username" class="block text-sm font-medium text-gray-700 mb-2">
                            Usuario  <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="api_username" 
                                   name="api_username" 
                                   value="{{ old('api_username', $commerce->api_username ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-500 focus:border-navy-500 @error('api_username') border-red-500 @enderror"
                                   placeholder="Usuario para acceso API"
                                   required>
                            @if(!isset($commerce))
                                <button type="button" 
                                        onclick="generateUsername()" 
                                        class="absolute right-2 top-2 px-2 py-1 text-xs bg-navy-100 text-navy-700 rounded hover:bg-navy-200 transition-colors">
                                    Generar
                                </button>
                            @endif
                        </div>
                        @error('api_username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contraseña  -->
                    <div>
                        <label for="api_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Contraseña  
                            @if(!isset($commerce))
                                <span class="text-red-500">*</span>
                            @else
                                <span class="text-sm text-gray-500">(Dejar vacío para mantener la actual)</span>
                            @endif
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="api_password" 
                                   name="api_password" 
                                   class="w-full px-3 py-2 {{ isset($commerce) ? 'pr-16' : 'pr-24' }} border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-500 focus:border-navy-500 @error('api_password') border-red-500 @enderror"
                                   placeholder="Contraseña para acceso API"
                                   {{ !isset($commerce) ? 'required' : '' }}>
                            
                            <!-- Contenedor de botones -->
                            <div class="absolute right-2 top-2 flex items-center space-x-1">
                                <!-- Botón ver/ocultar contraseña -->
                                <button type="button" 
                                        onclick="togglePasswordVisibility('api_password')" 
                                        class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors"
                                        title="Ver/Ocultar contraseña">
                                    <svg id="eye-icon-api_password" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                
                                <!-- Botón copiar -->
                                <button type="button" 
                                        onclick="copyPasswordToClipboard('api_password')" 
                                        class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors"
                                        title="Copiar contraseña">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                                
                                @if(!isset($commerce))
                                    <!-- Botón generar -->
                                    <button type="button" 
                                            onclick="generatePassword()" 
                                            class="px-2 py-1 text-xs bg-navy-100 text-navy-700 rounded hover:bg-navy-200 transition-colors"
                                            title="Generar nueva contraseña">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                        @error('api_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="mt-1 text-xs text-gray-500 flex items-center gap-4">
                            <span> Ver/Ocultar</span>
                            <span>📋 Copiar</span>
                            @if(!isset($commerce))
                                <span>🔄 Generar nueva</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Password -->
                @if(isset($commerce))
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Password (Solo lectura)
                    </label>
                    <div class="flex items-center space-x-2">
                        <input type="text" 
                               value="{{ $commerce->api_token ?? 'No generado' }}" 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-600 font-mono text-sm"
                               readonly>
                        <button type="button" 
                                onclick="copyToClipboard('{{ $commerce->api_token ?? '' }}')"
                                class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200 transition-colors">
                            Copiar
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Este token es único y se usa para autenticar las solicitudes API.</p>
                </div>

                <!-- Contraseña actual -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Contraseña 
                    </label>
                    <div class="flex items-center space-x-2">
                        <input type="password" 
                               id="current_api_password"
                               value="{{ $commerce->api_password_plain ?? 'Sin mostrar por seguridad' }}" 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-600 font-mono text-sm"
                               readonly>
                        <button type="button" 
                                onclick="togglePasswordVisibility('current_api_password')" 
                                class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200 transition-colors">
                            <svg id="eye-icon-current_api_password" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                        <button type="button" 
                                onclick="copyPasswordToClipboard('current_api_password')"
                                class="px-3 py-2 bg-blue-100 border border-blue-200 rounded-md text-sm font-medium text-blue-700 hover:bg-blue-200 transition-colors">
                            Copiar
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        Esta es la contraseña actual almacenada. Completa el campo superior solo si deseas cambiarla.
                    </p>
                </div>
                @endif
            </div>

            <!-- Estado -->
            @if(isset($commerce))
            <div class="mb-8">
                <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Estado del Comercio
                </h4>
                
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="is_active" 
                           name="is_active" 
                           value="1"
                           {{ old('is_active', $commerce->is_active ?? false) ? 'checked' : '' }}
                           class="h-4 w-4 text-navy-600 focus:ring-navy-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                        Comercio activo (puede usar la API)
                    </label>
                </div>
                <p class="mt-1 text-xs text-gray-500">
                    Los comercios inactivos no pueden generar QR de pago ni realizar transacciones.
                </p>
            </div>
            @endif

            <!-- Información adicional -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="ml-3">
                        <h5 class="text-sm font-medium text-blue-800">Información sobre las credenciales</h5>
                        <ul class="mt-1 text-xs text-blue-700 space-y-1">
                            <li>• El usuario y contraseña serán utilizados para autenticación básica al sistema</li>
                            <li>• El password se genera automáticamente y debe incluirse en todas las solicitudes</li>
                            <li>• Mantén estas credenciales seguras y compártelas solo con el comercio</li>
                            @if(!isset($commerce))
                                <li>• Puedes generar credenciales automáticamente usando los botones "Generar"</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.branches.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-navy-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-navy-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy-500 transition-colors">
                    {{ isset($commerce) ? 'Actualizar Comercio' : 'Crear Comercio' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nitInput = document.getElementById('nit');
    if (nitInput) {
        nitInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9-]/g, '');
        });
    }

    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 8);
        });
    }

    const businessNameInput = document.getElementById('business_name');
    const apiUsernameInput = document.getElementById('api_username');
    
    if (businessNameInput && apiUsernameInput && !apiUsernameInput.value) {
        businessNameInput.addEventListener('input', function(e) {
            if (!document.getElementById('api_username').value) {
                const businessName = e.target.value.toLowerCase()
                    .replace(/[^a-z0-9]/g, '')
                    .slice(0, 10);
                if (businessName) {
                    document.getElementById('api_username').value = businessName + '_api';
                }
            }
        });
    }
    
    attachCopyButtonListeners();
});

function attachCopyButtonListeners() {
    const copyButtons = document.querySelectorAll('button[onclick*="copyPasswordToClipboard"]');
    
    copyButtons.forEach(button => {
        const newButton = button.cloneNode(true);
        newButton.removeAttribute('onclick');
        
        const onclickAttr = button.getAttribute('onclick');
        const inputIdMatch = onclickAttr.match(/copyPasswordToClipboard\('([^']+)'\)/);
        
        if (inputIdMatch) {
            const inputId = inputIdMatch[1];
            
            newButton.addEventListener('click', function(e) {
                e.preventDefault();
                copyPasswordImproved(inputId, this);
            });
            
            button.parentNode.replaceChild(newButton, button);
        }
    });
}

function copyPasswordImproved(inputId, buttonElement) {
    const input = document.getElementById(inputId);
    
    if (!input) {
        console.error('Input no encontrado:', inputId);
        alert('Error: Campo de contraseña no encontrado');
        return;
    }
    
    const password = input.value.trim();
    
    if (!password) {
        alert('No hay contraseña para copiar');
        return;
    }
    
    console.log('Intentando copiar contraseña de longitud:', password.length);
    
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(password)
            .then(() => {
                console.log('Copiado exitosamente con Clipboard API');
                showCopySuccessImproved(buttonElement);
            })
            .catch(err => {
                console.error('Error con Clipboard API:', err);
                fallbackCopyImproved(password, buttonElement);
            });
    } else {
        console.log('Clipboard API no disponible, usando fallback');
        fallbackCopyImproved(password, buttonElement);
    }
}

function fallbackCopyImproved(text, button) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'absolute';
    textArea.style.left = '-9999px';
    textArea.style.top = '0';
    textArea.setAttribute('readonly', '');
    
    document.body.appendChild(textArea);
    
    try {
        textArea.select();
        textArea.setSelectionRange(0, 99999); 
        
        const successful = document.execCommand('copy');
        
        if (successful) {
            console.log('Copiado exitosamente con execCommand');
            showCopySuccessImproved(button);
        } else {
            throw new Error('execCommand retornó false');
        }
    } catch (err) {
        console.error('Error en fallback:', err);
        
        const modal = confirm(
            `No se pudo copiar automáticamente.\n\n` +
            `Contraseña: ${text}\n\n` +
            `¿Deseas intentar de nuevo?`
        );
        
        if (modal) {
            setTimeout(() => fallbackCopyImproved(text, button), 100);
        }
    } finally {
        document.body.removeChild(textArea);
    }
}

function showCopySuccessImproved(button) {
    if (!button) return;
    
    const originalHTML = button.innerHTML;
    const originalClasses = button.className;
    
    button.innerHTML = `
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    `;
    
    button.className = 'px-2 py-1 text-xs bg-green-100 text-green-700 rounded hover:bg-green-200 transition-colors';
    button.disabled = true;
    
    setTimeout(() => {
        button.innerHTML = originalHTML;
        button.className = originalClasses;
        button.disabled = false;
    }, 2000);
}

// Generar usuario aleatorio
function generateUsername() {
    const prefixes = ['comercio', 'tienda', 'negocio', 'empresa'];
    const prefix = prefixes[Math.floor(Math.random() * prefixes.length)];
    const randomNum = Math.floor(Math.random() * 9999) + 1000;
    document.getElementById('api_username').value = prefix + randomNum;
}

// Generar contraseña segura
function generatePassword() {
    const length = 12;
    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
    let password = "";
    for (let i = 0; i < length; i++) {
        password += charset.charAt(Math.floor(Math.random() * charset.length));
    }
    document.getElementById('api_password').value = password;
}

// Toggle visibilidad de contraseña
function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    const eyeIcon = document.getElementById('eye-icon-' + inputId);
    
    if (input.type === 'password') {
        input.type = 'text';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
        `;
    } else {
        input.type = 'password';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        `;
    }
}

function copyPasswordToClipboard(inputId) {
    const input = document.getElementById(inputId);
    const password = input.value;
    
    if (!password) {
        alert('No hay contraseña para copiar');
        return;
    }
    
    const buttons = document.querySelectorAll(`button[onclick*="${inputId}"]`);
    let copyButton = null;
    
    buttons.forEach(button => {
        if (button.getAttribute('onclick').includes('copyPasswordToClipboard')) {
            copyButton = button;
        }
    });
    
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(password).then(function() {
            showCopySuccess(copyButton);
        }).catch(function(err) {
            console.error('Error al copiar con Clipboard API: ', err);
            fallbackCopyToClipboard(password, copyButton);
        });
    } else {
        fallbackCopyToClipboard(password, copyButton);
    }
}

function showCopySuccess(button) {
    if (!button) return;
    
    const originalHTML = button.innerHTML;
    const originalClasses = button.className;
    
    button.innerHTML = `
        <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    `;
    button.className = button.className.replace(/bg-blue-\d+/, 'bg-green-100')
                                     .replace(/text-blue-\d+/, 'text-green-700')
                                     .replace(/hover:bg-blue-\d+/, 'hover:bg-green-200');
    
    setTimeout(function() {
        button.innerHTML = originalHTML;
        button.className = originalClasses;
    }, 2000);
}

function fallbackCopyToClipboard(text, button) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    document.body.appendChild(textArea);
    
    try {
        textArea.focus();
        textArea.select();
        const successful = document.execCommand('copy');
        if (successful) {
            showCopySuccess(button);
        } else {
            throw new Error('execCommand falló');
        }
    } catch (err) {
        console.error('Error al copiar: ', err);
        alert('Error al copiar la contraseña. Intenta seleccionar y copiar manualmente.');
    } finally {
        document.body.removeChild(textArea);
    }
}

function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() {
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = 'Copiado!';
            button.classList.add('bg-green-100', 'text-green-700');
            
            setTimeout(function() {
                button.textContent = originalText;
                button.classList.remove('bg-green-100', 'text-green-700');
            }, 2000);
        }).catch(function(err) {
            console.error('Error al copiar: ', err);
            alert('Error al copiar el token');
        });
    } else {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        try {
            document.execCommand('copy');
            alert('Token copiado al portapapeles');
        } catch (err) {
            alert('Error al copiar el token');
        }
        document.body.removeChild(textArea);
    }
}
</script>
@endpush