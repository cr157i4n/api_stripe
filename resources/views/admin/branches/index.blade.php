@extends('admin.layouts.master')

@section('title', 'Comercios')

@section('page-title', 'Gestión de Comercios')
@section('page-subtitle', 'Administra los comercios registrados en la pasarela de pago')

@section('content')

    <div x-data="commerceModal()">
        <div x-show="showCommerceModal" x-cloak @keydown.escape.window="closeCommerceModal()"
            class="fixed inset-0 z-50 overflow-y-auto">

            <div x-show="showCommerceModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/30"
                @click="closeCommerceModal()"></div>

            <!-- Container del Modal -->
            <div class="flex min-h-full items-center justify-center p-4 relative z-10">
                <div x-show="showCommerceModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative w-full max-w-lg transform rounded-lg bg-white p-6 shadow-2xl border" @click.stop>

                    <!-- Botón de cerrar en la esquina -->
                    <button @click="closeCommerceModal()"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="flex items-start">
                        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-yellow-100">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.732 15.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>

                        <div class="ml-4 flex-1 pr-8">
                            <h3 class="text-lg font-medium text-gray-900"
                                x-text="'Confirmar ' + statusText + ' del Comercio'">
                            </h3>

                            <div x-show="statusText === 'Desactivación'" class="mt-2">
                                <p class="text-sm text-gray-500">
                                    ¿Estás seguro de que deseas desactivar el comercio?
                                </p>
                                <div class="mt-3 rounded-lg bg-gray-50 p-3">
                                    <p class="mb-1 text-xs text-gray-600">Esta acción:</p>
                                    <ul class="space-y-1 text-xs text-gray-700">
                                        <li>✅ Suspenderá el acceso a la API</li>
                                        <li>❌ No eliminará los datos del comercio</li>
                                        <li>❌ Puede revertirse luego (reactivar)</li>
                                    </ul>
                                </div>
                                <div class="mt-4">
                                    <label for="deactivationReason" class="block text-sm font-medium text-gray-700 mb-1">
                                        Motivo de la desactivación
                                    </label>
                                    <textarea id="deactivationReason" x-model="deactivationReason" rows="3"
                                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-navy-500 focus:ring-navy-500 sm:text-sm"
                                        placeholder="Ej. Incumplimiento de términos, solicitud del comercio, etc.">
                         </textarea>
                                    <p class="mt-1 text-xs text-gray-500">Este motivo será almacenado con la acción.</p>
                                </div>

                            </div>

                            <div x-show="statusText === 'Activación'" class="mt-2">
                                <p class="text-sm text-gray-500">
                                    ¿Estás seguro de que deseas activar el comercio?
                                </p>
                                <div class="mt-3 rounded-lg bg-gray-50 p-3">
                                    <p class="mb-1 text-xs text-gray-600">Esta acción:</p>
                                    <ul class="space-y-1 text-xs text-gray-700">
                                        <li>✅ Restaurará el acceso a la API</li>
                                        <li>✅ Permitirá generar nuevos QR de pago</li>
                                        <li>✅ Habilitará todas las funcionalidades</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <button @click="closeCommerceModal()" :disabled="isProcessing"
                            class="w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-navy-500 focus:ring-offset-2 sm:w-auto disabled:opacity-50 transition-colors">
                            Cancelar
                        </button>

                        <button @click="confirmModal()" :disabled="isProcessing"
                            class="w-full rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto disabled:opacity-50 transition-colors">
                            <span x-show="!isProcessing">Confirmar</span>
                            <span x-show="isProcessing" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Procesando...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <x-table-container title="Comercios Registrados" subtitle="Gestiona los comercios y su acceso a la pasarela de pago"
            createText="Agregar Comercio" createRoute="{{ route('admin.branches.create') }}">
            {{-- Filtros adicionales específicos --}}
            @slot('filters')
                <div class="flex gap-3 items-center">
                    <a href="{{ request()->url() }}"
                        class="px-3 py-1.5 rounded-full text-xs font-medium {{ !request('status') ? 'bg-navy-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                        Todos
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}"
                        class="px-3 py-1.5 rounded-full text-xs font-medium {{ request('status') === 'active' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                        Activos
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'inactive']) }}"
                        class="px-3 py-1.5 rounded-full text-xs font-medium {{ request('status') === 'inactive' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                        Inactivos
                    </a>
                </div>
            @endslot

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gradient-to-r from-navy-800 to-navy-700 text-white">
                            <x-table-header sortable field="business_name">
                                Nombre del Comercio
                            </x-table-header>
                            <x-table-header sortable field="nit">
                                NIT
                            </x-table-header>
                            <x-table-header sortable field="address">
                                Dirección
                            </x-table-header>
                            <x-table-header sortable field="phone">
                                Celular
                            </x-table-header>
                            <x-table-header>
                                Credenciales API
                            </x-table-header>
                            <x-table-header sortable field="created_at">
                                Fecha Registro
                            </x-table-header>
                            <x-table-header sortable field="is_active">
                                Estado
                            </x-table-header>
                            <x-table-header class="text-right">
                                Acciones
                            </x-table-header>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($data as $commerce)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-navy-800">{{ $commerce->business_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700 font-mono">{{ $commerce->nit }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-700">{{ Str::limit($commerce->address, 30) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700">{{ $commerce->phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <div class="text-xs text-gray-500">Usuario:</div>
                                        <div class="text-sm text-gray-700 font-mono">{{ $commerce->api_username }}</div>
                                        <div class="text-xs text-gray-500">Password:</div>
                                        <div class="text-sm text-gray-700 font-mono">
                                            {{ Str::limit($commerce->api_token, 20) }}***</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700">{{ $commerce->created_at->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($commerce->is_active)
                                        <span
                                            class="px-1 py-0 inline-flex items-center gap-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                            <button @click="openCommerceModal({{ $commerce->id }}, 'inactive')"
                                                class="p-1.5 rounded-lg text-green-600 hover:bg-green-50 hover:text-green-700 transition-colors"
                                                title="Desactivar">
                                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                                Activo
                                            </button>
                                        </span>
                                    @else
                                        <span
                                            class="px-1 py-0 inline-flex items-center gap-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                            <button @click="openCommerceModal({{ $commerce->id }}, 'active')"
                                                class="p-1.5 rounded-lg text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors"
                                                title="Activar">
                                                <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                                Inactivo
                                            </button>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex items-center justify-end space-x-2">
                                        <!-- Ver/Editar -->
                                        <a href="{{ route('admin.branches.edit', $commerce->id) }}"
                                            class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50 hover:text-blue-700 transition-colors"
                                            title="Editar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <!-- Regenerar Token -->
                                        <button onclick="regenerateToken({{ $commerce->id }})"
                                            class="p-1.5 rounded-lg text-purple-600 hover:bg-purple-50 hover:text-purple-700 transition-colors"
                                            title="Regenerar Token API">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        </button>

                                       
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-3"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <p class="text-gray-600 font-medium">No hay comercios registrados</p>
                                        <p class="text-gray-500 text-sm mt-1">Agrega un nuevo comercio para comenzar</p>
                                        <a href="{{ route('admin.branches.create') }}"
                                            class="mt-4 inline-flex items-center px-4 py-2 bg-navy-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-navy-700 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Agregar Primer Comercio
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @slot('pagination')
                {{ $data->links() }}
            @endslot
        </x-table-container>

    </div>
@endsection

@push('styles')
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function commerceModal() {
            return {
                showCommerceModal: false,
                selectedCommerceId: null,
                deactivationReason: '',
                isProcessing: false,
                statusText: '',

                openCommerceModal(commerceId, status) {
                    console.log('Abriendo modal para comercio:', commerceId);
                    console.log('Estado del modal:', status);
                    this.selectedCommerceId = commerceId;
                    this.deactivationReason = '';
                    this.showCommerceModal = true;
                    this.statusText = status === 'active' ? 'Activación' : 'Desactivación';
                },

                closeCommerceModal() {
                    if (!this.isProcessing) {
                        console.log('Cerrando modal');
                        this.showCommerceModal = false;
                        this.deactivationReason = '';
                        this.selectedCommerceId = null;
                    }
                },

                async confirmModal() {
                    if (this.isProcessing) return;

                    console.log('Confirmando cambio de estado para:', this.selectedCommerceId);
                    this.isProcessing = true;

                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]');
                        if (!csrfToken) {
                            throw new Error('CSRF token no encontrado');
                        }

                        const response = await fetch(`/admin/commerces/${this.selectedCommerceId}/toggle-status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                reason: this.deactivationReason
                            })
                        });

                        const data = await response.json();

                        if (response.ok) {
                            setTimeout(() => {
                                this.closeCommerceModal();
                                window.location.reload();
                            }, 1000);
                        } else {
                            throw new Error(data.message || 'Error al procesar la operación');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Error: ' + error.message);
                    } finally {
                        this.isProcessing = false;
                    }
                }
            }
        }

        function regenerateToken(commerceId) {
            if (confirm('¿Estás seguro de que deseas regenerar el token API? El token actual dejará de funcionar.')) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');

               
            }
        }

        document.addEventListener('alpine:init', () => {
            console.log('Alpine.js inicializado correctamente');
        });

        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM cargado');

            setTimeout(() => {
                console.log('Alpine está disponible:', typeof window.Alpine !== 'undefined');
            }, 1000);
        });
    </script>
@endpush
