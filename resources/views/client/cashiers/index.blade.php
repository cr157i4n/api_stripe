@extends('client.layouts.master')

@section('title', 'Gestionar Cajas')

@section('page-title', 'Gestionar Cajas')
@section('page-subtitle', 'Administra todas tus cajas registradoras')

@section('content')
<div class="space-y-6">
    <!-- Estadísticas y filtros -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <!-- Estadísticas -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['total_cashiers'] }}</div>
                    <div class="text-sm text-gray-600">Total Cajas</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['active_codes'] }}</div>
                    <div class="text-sm text-gray-600">Códigos Activos</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-600">{{ $stats['max_allowed'] }}</div>
                    <div class="text-sm text-gray-600">Máximo Permitido</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ $stats['available'] }}</div>
                    <div class="text-sm text-gray-600">Disponibles</div>
                </div>
            </div>
            
            <!-- Acciones -->
            <div class="flex items-center space-x-3">
                @if($stats['available'] > 0)
                <a href="{{ route('client.cashiers.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Nueva Caja
                </a>
                @else
                <span class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md text-sm font-medium text-gray-500 cursor-not-allowed">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Límite Alcanzado
                </span>
                @endif
                
                <button onclick="refreshPage()" 
                        class="inline-flex items-center px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
            </div>
        </div>
        
        @if($stats['available'] <= 0)
        <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Límite Alcanzado</h3>
                    <p class="text-sm text-yellow-700 mt-1">
                        Has alcanzado el número máximo de cajas permitidas ({{ $stats['max_allowed'] }}). 
                        Para agregar una nueva caja, primero debes eliminar una existente.
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Lista de cajas -->
    <div class="grid gap-6" id="cashiersContainer">
        @forelse($cashiers as $cashier)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 cashier-card">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4 flex-1">
                    <!-- Icono de caja -->
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Información de la caja -->
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $cashier->name }}</h3>
                            @if($cashier->qrDevices->isNotEmpty())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-1.5"></span>
                                    Código Activo
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Sin Código
                                </span>
                            @endif
                        </div>
                        
                        <div class="space-y-1 text-sm text-gray-600">
                            @if($cashier->location)
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $cashier->location }}
                            </p>
                            @endif
                            
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Creada: {{ $cashier->created_at->format('d/m/Y H:i') }}
                            </p>
                            
                            @if($cashier->last_activity)
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Última actividad: {{ $cashier->last_activity->diffForHumans() }}
                            </p>
                            @endif
                        </div>
                        
                        @if($cashier->description)
                        <p class="mt-2 text-sm text-gray-700">{{ $cashier->description }}</p>
                        @endif
                    </div>
                </div>
                
                <!-- Acciones -->
                <div class="flex items-center space-x-2">
                    @if($cashier->qrDevices->isEmpty())
                    <button onclick="generateCode({{ $cashier->id }})"
                            class="px-3 py-2 bg-green-100 border border-green-300 rounded-md text-sm font-medium text-green-700 hover:bg-green-200 transition-colors">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                        Generar Código
                    </button>
                    @else
                    <a href="{{ route('client.cashiers.show', $cashier) }}"
                       class="px-3 py-2 bg-blue-100 border border-blue-300 rounded-md text-sm font-medium text-blue-700 hover:bg-blue-200 transition-colors">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Ver Código
                    </a>
                    @endif
                    
                    <button onclick="editCashier({{ $cashier->id }}, '{{ $cashier->name }}', '{{ $cashier->location }}', '{{ $cashier->description }}')"
                            class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200 transition-colors">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </button>
                    
                    <button onclick="deleteCashier({{ $cashier->id }}, '{{ $cashier->name }}')"
                            class="px-3 py-2 bg-red-100 border border-red-300 rounded-md text-sm font-medium text-red-700 hover:bg-red-200 transition-colors">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay cajas registradas</h3>
            <p class="mt-1 text-sm text-gray-500">Comienza creando tu primera caja registradora.</p>
            @if($stats['available'] > 0)
            <div class="mt-6">
                <a href="{{ route('client.cashiers.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Nueva Caja
                </a>
            </div>
            @endif
        </div>
        @endforelse
    </div>
</div>

<!-- Modal para editar caja -->
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
                <input type="hidden" id="editCashierId">
                
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

<!-- Modal de confirmación para eliminar -->
<div id="deleteConfirmModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-3">¿Eliminar Caja?</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Esta acción eliminará permanentemente la caja "<span id="deleteCashierName"></span>" y todos sus códigos asociados.
                </p>
            </div>
            <div class="items-center px-4 py-3 space-x-3 flex justify-center">
                <button onclick="closeDeleteModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none">
                    Cancelar
                </button>
                <button onclick="confirmDelete()" 
                        class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let cashierToDelete = null;

function generateCode(cashierId) {
    if (confirm('¿Generar código para esta caja?')) {
        window.location.href = `/client/cashiers/${cashierId}/generate-code`;
    }
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

function deleteCashier(id, name) {
    cashierToDelete = id;
    document.getElementById('deleteCashierName').textContent = name;
    document.getElementById('deleteConfirmModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteConfirmModal').classList.add('hidden');
    cashierToDelete = null;
}

function confirmDelete() {
    if (!cashierToDelete) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/client/cashiers/${cashierToDelete}`;
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'DELETE';
    
    form.appendChild(csrfToken);
    form.appendChild(methodField);
    document.body.appendChild(form);
    form.submit();
}

function refreshPage() {
    location.reload();
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
            // Mostrar errores de validación
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
    const deleteModal = document.getElementById('deleteConfirmModal');
    
    if (e.target === editModal) {
        closeEditModal();
    }
    if (e.target === deleteModal) {
        closeDeleteModal();
    }
});
</script>
@endsection