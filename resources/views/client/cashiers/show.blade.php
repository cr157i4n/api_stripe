@extends('client.layouts.master')

@section('title', 'Detalles de Caja')

@section('page-title', $cashier->name)
@section('page-subtitle', 'Gestiona los códigos y configuración de esta caja')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Información de la caja -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-start space-x-4">
                <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $cashier->name }}</h1>
                    @if($cashier->location)
                        <p class="text-gray-600 flex items-center mt-1">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $cashier->location }}
                        </p>
                    @endif
                    @if($cashier->description)
                        <p class="text-gray-700 mt-2">{{ $cashier->description }}</p>
                    @endif
                </div>
            </div>
            
            <!-- Estado de la caja -->
            <div class="text-right">
                @if($activeCode)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                        Código Activo
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                        Sin Código Activo
                    </span>
                @endif
            </div>
        </div>
        
        <!-- Información básica -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-gray-200">
            <div>
                <p class="text-sm font-medium text-gray-700">ID de Caja</p>
                <p class="text-lg text-gray-900">#{{ $cashier->id }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-700">Fecha de Creación</p>
                <p class="text-lg text-gray-900">{{ $cashier->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-700">Última Actividad</p>
                <p class="text-lg text-gray-900">
                    {{ $cashier->last_activity ? $cashier->last_activity->diffForHumans() : 'Sin actividad' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Código activo -->
    @if($activeCode)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Código Activo</h2>
            <div class="flex space-x-2">
                <button onclick="copyCode('{{ $activeCode->code }}')" 
                        class="inline-flex items-center px-3 py-2 bg-blue-100 border border-blue-300 rounded-md text-sm font-medium text-blue-700 hover:bg-blue-200 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Copiar
                </button>
                <form action="{{ route('client.cashiers.revoke-code', $activeCode) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('¿Estás seguro de revocar este código?')"
                            class="inline-flex items-center px-3 py-2 bg-red-100 border border-red-300 rounded-md text-sm font-medium text-red-700 hover:bg-red-200 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Revocar
                    </button>
                </form>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Código numérico -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 text-center">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Código de Vinculación</h3>
                <div class="text-5xl font-mono font-bold text-blue-600 mb-4 tracking-wider">
                    {{ $activeCode->code }}
                </div>
                <p class="text-sm text-gray-600">
                    Expira: {{ $activeCode->expires_at->format('d/m/Y H:i') }}
                    <span class="block text-red-600 font-medium">
                        ({{ $activeCode->expires_at->diffForHumans() }})
                    </span>
                </p>
            </div>
            
            <!-- Código QR -->
            <div class="bg-gray-50 rounded-xl p-6 text-center">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Código QR</h3>
                <div class="flex justify-center mb-4">
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <img src="data:image/png;base64,{{ $activeCode->image_qr }}" 
                             alt="Código QR" 
                             class="w-32 h-32 mx-auto">
                    </div>
                </div>
                <p class="text-sm text-gray-600">
                    Escanea desde el dispositivo
                </p>
            </div>
        </div>
        
        <!-- Instrucciones -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h4 class="text-sm font-medium text-blue-800 mb-2">Instrucciones de Vinculación</h4>
            <ol class="text-sm text-blue-700 space-y-1">
                <li>1. Abre la aplicación en tu dispositivo de punto de venta</li>
                <li>2. Busca la opción "Vincular con comercio" o "Conectar"</li>
                <li>3. Ingresa el código <strong>{{ $activeCode->code }}</strong> o escanea el QR</li>
                <li>4. Confirma la vinculación en tu dispositivo</li>
            </ol>
        </div>
    </div>
    @else
    <!-- No hay código activo -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4">
            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
            </svg>
        </div>
        
        <h3 class="text-xl font-bold text-gray-900 mb-2">Sin Código Activo</h3>
        <p class="text-gray-600 mb-6">
            Esta caja no tiene un código de vinculación activo. Genera uno para conectar un dispositivo.
        </p>
        
        <form action="{{ route('client.cashiers.generate-code', $cashier) }}" method="POST" class="inline">
            @csrf
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md text-base font-medium text-white hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                Generar Código
            </button>
        </form>
    </div>
    @endif

    <!-- Historial de códigos -->
    @if($cashier->qrDevices->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Historial de Códigos</h2>
        
        <div class="space-y-4">
            @foreach($cashier->qrDevices->sortByDesc('created_at') as $qrDevice)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="font-mono text-lg font-semibold text-gray-900">
                            {{ $qrDevice->code }}
                        </div>
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($qrDevice->status === 'active') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @if($qrDevice->status === 'active') Activo @else Expirado @endif
                            </span>
                        </div>
                    </div>
                    <div class="text-right text-sm text-gray-600">
                        <p>Creado: {{ $qrDevice->created_at->format('d/m/Y H:i') }}</p>
                        @if($qrDevice->expires_at)
                        <p>Expira: {{ $qrDevice->expires_at->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Acciones -->
    <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0 sm:space-x-4">
        <a href="{{ route('client.cashiers.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver a Cajas
        </a>
        
        <div class="flex space-x-3">
            <button onclick="editCashier({{ $cashier->id }}, '{{ $cashier->name }}', '{{ $cashier->location }}', '{{ $cashier->description }}')"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar Caja
            </button>
            
            @if(!$activeCode)
            <form action="{{ route('client.cashiers.generate-code', $cashier) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    Generar Código
                </button>
            </form>
            @endif
        </div>
    </div>
</div>

<!-- Modal para editar caja (reutilizar del index) -->
<div id="editCashierModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Editar Caja</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form id="editCashierForm" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="editCashierId" value="{{ $cashier->id }}">
                
                <div>
                    <label for="editName" class="block text-sm font-medium text-gray-700">Nombre de la Caja *</label>
                    <input type="text" id="editName" name="name" required
                           class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div id="editNameError" class="hidden mt-1 text-sm text-red-600"></div>
                </div>
                
                <div>
                    <label for="editLocation" class="block text-sm font-medium text-gray-700">Ubicación</label>
                    <input type="text" id="editLocation" name="location" 
                           class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="editDescription" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea id="editDescription" name="description" rows="3"
                              class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                
                <div class="flex items-center justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast notification -->
<div id="toast" class="hidden fixed top-4 right-4 z-50">
    <div class="bg-white border border-gray-200 rounded-lg shadow-lg p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-900">¡Código copiado al portapapeles!</p>
            </div>
            <button onclick="hideToast()" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg p-1.5">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
function copyCode(code) {
    navigator.clipboard.writeText(code).then(function() {
        showToast();
    }).catch(function(err) {
        console.error('Error al copiar: ', err);
        const textArea = document.createElement('textarea');
        textArea.value = code;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showToast();
    });
}

function editCashier(id, name, location, description) {
    document.getElementById('editCashierId').value = id;
    document.getElementById('editName').value = name;
    document.getElementById('editLocation').value = location || '';
    document.getElementById('editDescription').value = description || '';
    
    document.getElementById('editCashierModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editCashierModal').classList.add('hidden');
    const errorElements = document.querySelectorAll('[id$="Error"]');
    errorElements.forEach(el => el.classList.add('hidden'));
}

function showToast() {
    const toast = document.getElementById('toast');
    toast.classList.remove('hidden');
    
    setTimeout(() => {
        hideToast();
    }, 3000);
}

function hideToast() {
    document.getElementById('toast').classList.add('hidden');
}

document.getElementById('editCashierForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const cashierId = document.getElementById('editCashierId').value;
    const formData = new FormData(this);
    
    fetch(`/client/cashiers/${cashierId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeEditModal();
            location.reload();
        } else {
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    const errorElement = document.getElementById(`edit${field.charAt(0).toUpperCase() + field.slice(1)}Error`);
                    if (errorElement) {
                        errorElement.textContent = data.errors[field][0];
                        errorElement.classList.remove('hidden');
                    }
                });
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar la caja');
    });
});

window.addEventListener('click', function(e) {
    const editModal = document.getElementById('editCashierModal');
    if (e.target === editModal) {
        closeEditModal();
    }
});

@if($activeCode)
setInterval(function() {
 
}, 30000);
@endif
</script>
@endsection