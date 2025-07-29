@extends('client.layouts.master')

@section('title', 'Gestionar Cajas')

@section('page-title', 'Gestionar Cajas')
@section('page-subtitle', 'Administra todas tus cajas registradoras vinculadas')

@section('content')
<div class="space-y-6">
    <!-- Filtros y acciones -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <select id="statusFilter" class="appearance-none bg-white border border-gray-300 rounded-md px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">Todos los estados</option>
                        <option value="active">Activos</option>
                        <option value="inactive">Inactivos</option>
                        <option value="pending">Pendientes</option>
                    </select>
                    <svg class="absolute right-2 top-3 h-4 w-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                
                <div class="relative">
                    <input type="text" 
                           id="searchDevices" 
                           placeholder="Buscar por nombre o ubicación..." 
                           class="w-64 px-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <svg class="absolute right-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="{{ route('client.devices.create-code') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Generar Código
                </a>
                
                <button onclick="refreshDevices()" 
                        class="inline-flex items-center px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Resumen rápido -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">Total</p>
                    <p class="text-lg font-semibold text-gray-900" id="totalDevices">{{ $devices->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">Activos</p>
                    <p class="text-lg font-semibold text-green-600" id="activeDevices">{{ $devices->where('status', 'active')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">Pendientes</p>
                    <p class="text-lg font-semibold text-yellow-600" id="pendingDevices">{{ $devices->where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">Inactivos</p>
                    <p class="text-lg font-semibold text-gray-600" id="inactiveDevices">{{ $devices->where('status', 'inactive')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de dispositivos -->
    <div class="grid gap-6" id="devicesContainer">
        @forelse($devices as $device)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 device-card" 
             data-status="{{ $device->status }}" 
             data-name="{{ strtolower($device->device_name) }}" 
             data-location="{{ strtolower($device->location ?? '') }}"
             data-device-id="{{ $device->id }}">
            
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4">
                    <!-- Icono de estado -->
                    <div class="flex-shrink-0">
                        @if($device->status === 'active')
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        @elseif($device->status === 'inactive')
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        @else
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Información del dispositivo -->
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $device->device_name }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($device->status === 'active') bg-green-100 text-green-800
                                @elseif($device->status === 'inactive') bg-gray-100 text-gray-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                @if($device->status === 'active') Activo
                                @elseif($device->status === 'inactive') Inactivo
                                @else Pendiente @endif
                            </span>
                        </div>
                        
                        <div class="space-y-1 text-sm text-gray-600">
                            @if($device->location)
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $device->location }}
                            </p>
                            @endif
                            
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11m-6 0h6"/>
                                </svg>
                                ID: {{ $device->device_id }}
                            </p>
                            
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Vinculado: {{ $device->created_at->format('d/m/Y H:i') }}
                            </p>
                            
                            @if($device->last_activity)
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Última actividad: {{ $device->last_activity->diffForHumans() }}
                            </p>
                            @endif
                        </div>
                        
                        @if($device->description)
                        <p class="mt-2 text-sm text-gray-700">{{ $device->description }}</p>
                        @endif
                    </div>
                </div>
                
                <!-- Acciones -->
                <div class="flex items-center space-x-2">
                    @if($device->status === 'active')
                    <button onclick="toggleDeviceStatus({{ $device->id }}, 'inactive')"
                            class="px-3 py-2 bg-yellow-100 border border-yellow-300 rounded-md text-sm font-medium text-yellow-700 hover:bg-yellow-200 transition-colors">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Desactivar
                    </button>
                    @elseif($device->status === 'inactive')
                    <button onclick="toggleDeviceStatus({{ $device->id }}, 'active')"
                            class="px-3 py-2 bg-green-100 border border-green-300 rounded-md text-sm font-medium text-green-700 hover:bg-green-200 transition-colors">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293H15"/>
                        </svg>
                        Activar
                    </button>
                    @endif
                    
                    <button onclick="editDevice({{ $device->id }})"
                            class="px-3 py-2 bg-blue-100 border border-blue-300 rounded-md text-sm font-medium text-blue-700 hover:bg-blue-200 transition-colors">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </button>
                    
                    <button onclick="removeDevice({{ $device->id }})"
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
            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay cajas vinculadas</h3>
            <p class="mt-1 text-sm text-gray-500">Comienza generando un código para vincular tu primera caja.</p>
            <div class="mt-6">
                <a href="{{ route('client.devices.create-code') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Generar Código
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal para editar dispositivo -->
<div id="editDeviceModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
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
            
            <form id="editDeviceForm" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="editDeviceId">
                
                <div>
                    <label for="editDeviceName" class="block text-sm font-medium text-gray-700">Nombre de la Caja</label>
                    <input type="text" id="editDeviceName" name="device_name" 
                           class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div id="editDeviceNameError" class="hidden mt-1 text-sm text-red-600"></div>
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
                    <button type="submit" id="saveDeviceBtn"
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
                    Esta acción no se puede deshacer. La caja será desvinculada permanentemente de tu comercio.
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

<!-- Toast notifications -->
<div id="toast" class="hidden fixed top-4 right-4 z-50">
    <div class="bg-white border border-gray-200 rounded-lg shadow-lg p-4 max-w-sm">
        <div class="flex items-center">
            <div id="toastIcon" class="flex-shrink-0"></div>
            <div class="ml-3">
                <p id="toastMessage" class="text-sm font-medium text-gray-900"></p>
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
let deviceToDelete = null;

document.getElementById('statusFilter').addEventListener('change', filterDevices);
document.getElementById('searchDevices').addEventListener('input', filterDevices);

function filterDevices() {
    const statusFilter = document.getElementById('statusFilter').value;
    const searchTerm = document.getElementById('searchDevices').value.toLowerCase();
    const deviceCards = document.querySelectorAll('.device-card');
    
    deviceCards.forEach(card => {
        const status = card.dataset.status;
        const name = card.dataset.name;
        const location = card.dataset.location;
        
        const statusMatch = statusFilter === 'all' || status === statusFilter;
        const searchMatch = searchTerm === '' || name.includes(searchTerm) || location.includes(searchTerm);
        
        if (statusMatch && searchMatch) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function toggleDeviceStatus(deviceId, newStatus) {
    const action = newStatus === 'active' ? 'activar' : 'desactivar';
    
    if (confirm(`¿Estás seguro de ${action} esta caja?`)) {
        showLoading();
        
        fetch(`/commerce/devices/${deviceId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showToast('Estado actualizado correctamente', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Error al cambiar el estado del dispositivo', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showToast('Error al cambiar el estado del dispositivo', 'error');
        });
    }
}

function editDevice(deviceId) {
    const deviceCard = document.querySelector(`[data-device-id="${deviceId}"]`);
    if (!deviceCard) return;
    
    const deviceName = deviceCard.querySelector('h3').textContent;
    const location = deviceCard.querySelector('[data-location]')?.textContent || '';
    const description = deviceCard.querySelector('.text-gray-700')?.textContent || '';
    
    document.getElementById('editDeviceId').value = deviceId;
    document.getElementById('editDeviceName').value = deviceName;
    document.getElementById('editLocation').value = location;
    document.getElementById('editDescription').value = description;
    
    document.getElementById('editDeviceModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editDeviceModal').classList.add('hidden');
    const errorElements = document.querySelectorAll('[id$="Error"]');
    errorElements.forEach(el => el.classList.add('hidden'));
}

function removeDevice(deviceId) {
    deviceToDelete = deviceId;
    document.getElementById('deleteConfirmModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteConfirmModal').classList.add('hidden');
    deviceToDelete = null;
}

function confirmDelete() {
    if (!deviceToDelete) return;
    
    showLoading();
    
    fetch(`/commerce/devices/${deviceToDelete}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        closeDeleteModal();
        if (data.success) {
            showToast('Caja eliminada correctamente', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Error al eliminar la caja', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        closeDeleteModal();
        console.error('Error:', error);
        showToast('Error al eliminar la caja', 'error');
    });
}

function refreshDevices() {
    showLoading();
    location.reload();
}

document.getElementById('editDeviceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const deviceId = document.getElementById('editDeviceId').value;
    const formData = new FormData(this);
    
    showLoading();
    
    fetch(`/commerce/devices/${deviceId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            closeEditModal();
            showToast('Caja actualizada correctamente', 'success');
            setTimeout(() => location.reload(), 1000);
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
            } else {
                showToast('Error al actualizar la caja', 'error');
            }
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showToast('Error al actualizar la caja', 'error');
    });
});

function showToast(message, type = 'info') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    const toastIcon = document.getElementById('toastIcon');
    
    toastMessage.textContent = message;
    
    if (type === 'success') {
        toastIcon.innerHTML = '<svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
    } else if (type === 'error') {
        toastIcon.innerHTML = '<svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
    }
    
    toast.classList.remove('hidden');
    
    setTimeout(() => {
        hideToast();
    }, 3000);
}

function hideToast() {
    document.getElementById('toast').classList.add('hidden');
}

function showLoading() {
    document.body.style.cursor = 'wait';
}

function hideLoading() {
    document.body.style.cursor = 'default';
}

window.addEventListener('click', function(e) {
    const editModal = document.getElementById('editDeviceModal');
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