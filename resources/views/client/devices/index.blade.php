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
            
            <a href="{{ route('client.devices.create-code') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Generar Código
            </a>
        </div>
    </div>

    <!-- Lista de dispositivos -->
    <div class="grid gap-6">
        @forelse($devices as $device)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 device-card" 
             data-status="{{ $device->status }}" 
             data-name="{{ strtolower($device->device_name) }}" 
             data-location="{{ strtolower($device->location ?? '') }}">
            
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
                        Desactivar
                    </button>
                    @elseif($device->status === 'inactive')
                    <button onclick="toggleDeviceStatus({{ $device->id }}, 'active')"
                            class="px-3 py-2 bg-green-100 border border-green-300 rounded-md text-sm font-medium text-green-700 hover:bg-green-200 transition-colors">
                        Activar
                    </button>
                    @endif
                    
                    <button onclick="editDevice({{ $device->id }})"
                            class="px-3 py-2 bg-blue-100 border border-blue-300 rounded-md text-sm font-medium text-blue-700 hover:bg-blue-200 transition-colors">
                        Editar
                    </button>
                    
                    <button onclick="removeDevice({{ $device->id }})"
                            class="px-3 py-2 bg-red-100 border border-red-300 rounded-md text-sm font-medium text-red-700 hover:bg-red-200 transition-colors">
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

<script>
// Filtros
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
            if (data.success) {
                location.reload();
            } else {
                alert('Error al cambiar el estado del dispositivo');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cambiar el estado del dispositivo');
        });
    }
}

function closeEditModal() {
    document.getElementById('editDeviceModal').classList.add('hidden');
}


function removeDevice(deviceId) {

}
</script>
@endsection