<aside id="sidebar" 
    class="hidden md:flex flex-col w-64 bg-gradient-to-b from-steel-800 via-steel-800 to-steel-900 text-white shadow-xl overflow-y-auto">

    <!-- Perfil del Comercio -->
    <div class="p-4 border-b border-steel-700/50">
        <div class="flex items-center space-x-3 mb-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-ocean-400 to-ocean-600 flex items-center justify-center text-white shadow-lg">
                {{ substr(auth()->user()->business_name ?? auth()->user()->name ?? 'C', 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <p class="font-semibold text-white truncate">{{ auth()->user()->business_name ?? auth()->user()->name ?? 'Mi Comercio' }}</p>
                <p class="text-xs text-steel-300 truncate">{{ auth()->user()->name ?? 'Propietario' }}</p>
            </div>
        </div>
        
        <!-- Plan del comercio -->
        <div class="bg-steel-700/30 rounded-lg p-2">
            <div class="flex items-center justify-between">
                <span class="text-xs text-steel-200">Plan Actual</span>
                <span class="text-xs font-medium text-white bg-ocean-600 px-2 py-1 rounded">
                    {{ auth()->user()->merchant->plan ?? 'Básico' }}
                </span>
            </div>
            <div class="mt-1">
                <div class="text-xs text-steel-200">
                  cajas
                </div>
                <div class="w-full bg-steel-800 rounded-full h-1 mt-1">
                    @php
                        $merchant = auth()->user()->merchant;
                        $percentage = $merchant ? ($merchant->cashiers()->count() / max($merchant->max_cash_register, 1)) * 100 : 0;
                    @endphp
                    <div class="bg-ocean-400 h-1 rounded-full" style="width: {{ min($percentage, 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navegación Principal -->
    <nav class="flex-1 px-3 py-4 space-y-1">

        <!-- Dashboard -->
        <a href="{{ route('client.dashboard') }}"
            class="{{ request()->routeIs('client.dashboard') ? 'bg-ocean-500/20 text-white border border-ocean-500/30' : 'text-gray-300 hover:bg-steel-700/50 hover:text-white' }} flex items-center px-4 py-3 rounded-lg transition-all duration-200 group">
            <svg class="h-5 w-5 mr-3 {{ request()->routeIs('client.dashboard') ? 'text-ocean-400' : 'text-gray-400 group-hover:text-ocean-400' }} transition-colors duration-200" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="font-medium">Dashboard</span>
        </a>

        <!-- Gestión de Cajas -->
        <div class="py-2">
            <p class="px-4 text-xs font-semibold text-steel-300 uppercase tracking-wider mb-2">🏪 Mi Comercio</p>
            
            <!-- Mis Cajas -->
            <a href="{{ route('client.cashiers.index') }}"
                class="{{ request()->routeIs('cashiers.*') ? 'bg-ocean-500/20 text-white border border-ocean-500/30' : 'text-gray-300 hover:bg-steel-700/50 hover:text-white' }} flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 group">
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-3 {{ request()->routeIs('cashiers.*') ? 'text-ocean-400' : 'text-gray-400 group-hover:text-ocean-400' }} transition-colors duration-200" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <span class="font-medium">Mis Cajas</span>
                </div>
                @php
                    $activeCashiers = auth()->user()->merchant ? auth()->user()->merchant->cashiers()->count() : 0;
                @endphp
                <span class="bg-ocean-500/20 text-ocean-400 text-xs px-2 py-1 rounded-full">{{ $activeCashiers }}</span>
            </a>

            <!-- Crear Nueva Caja -->
            <a href="{{ route('client.cashiers.create') }}"
                class="{{ request()->routeIs('cashiers.create') ? 'bg-steel-500/20 text-white border border-steel-500/30' : 'text-gray-300 hover:bg-steel-700/50 hover:text-white' }} flex items-center px-4 py-3 rounded-lg transition-all duration-200 group">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('cashiers.create') ? 'text-steel-400' : 'text-gray-400 group-hover:text-steel-400' }} transition-colors duration-200" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span class="font-medium">Nueva Caja</span>
            </a>
        </div>

        <!-- Transacciones -->
        <div class="py-2">
            <p class="px-4 text-xs font-semibold text-steel-300 uppercase tracking-wider mb-2">💳 Ventas</p>
            
            <!-- Transacciones del Día -->
            <a href="{{ route('client.transactions.today') ?? '#' }}"
                class="{{ request()->routeIs('client.transactions.today') ? 'bg-ocean-500/20 text-white border border-ocean-500/30' : 'text-gray-300 hover:bg-steel-700/50 hover:text-white' }} flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 group">
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-3 {{ request()->routeIs('client.transactions.today') ? 'text-ocean-400' : 'text-gray-400 group-hover:text-ocean-400' }} transition-colors duration-200" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span class="font-medium">Hoy</span>
                </div>
                <div class="text-right">
                    <div class="text-xs text-ocean-400 font-medium">$2,450</div>
                    <div class="text-xs text-gray-400">15 ventas</div>
                </div>
            </a>

            <!-- Historial de Transacciones -->
            <a href="{{ route('client.transactions.index') ?? '#' }}"
                class="{{ request()->routeIs('client.transactions.index') ? 'bg-steel-500/20 text-white border border-steel-500/30' : 'text-gray-300 hover:bg-steel-700/50 hover:text-white' }} flex items-center px-4 py-3 rounded-lg transition-all duration-200 group">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('client.transactions.index') ? 'text-steel-400' : 'text-gray-400 group-hover:text-steel-400' }} transition-colors duration-200" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <span class="font-medium">Historial</span>
            </a>

            <!-- Transacciones Pendientes -->
            <a href="{{ route('client.transactions.pending') ?? '#' }}"
                class="{{ request()->routeIs('client.transactions.pending') ? 'bg-yellow-500/20 text-white border border-yellow-500/30' : 'text-gray-300 hover:bg-steel-700/50 hover:text-white' }} flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 group">
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-3 {{ request()->routeIs('client.transactions.pending') ? 'text-yellow-400' : 'text-gray-400 group-hover:text-yellow-400' }} transition-colors duration-200" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">Pendientes</span>
                </div>
                <span class="bg-yellow-500/20 text-yellow-400 text-xs px-2 py-1 rounded-full">3</span>
            </a>
        </div>

        <!-- Reportes -->
        <div class="py-2">
            <p class="px-4 text-xs font-semibold text-steel-300 uppercase tracking-wider mb-2">📊 Reportes</p>
            
            <!-- Ventas por Día -->
            <a href="{{ route('client.reports.daily') ?? '#' }}"
                class="{{ request()->routeIs('client.reports.daily') ? 'bg-ocean-500/20 text-white border border-ocean-500/30' : 'text-gray-300 hover:bg-steel-700/50 hover:text-white' }} flex items-center px-4 py-3 rounded-lg transition-all duration-200 group">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('client.reports.daily') ? 'text-ocean-400' : 'text-gray-400 group-hover:text-ocean-400' }} transition-colors duration-200" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="font-medium">Ventas Diarias</span>
            </a>

            <!-- Ventas por Caja -->
            <a href="{{ route('client.reports.by-device') ?? '#' }}"
                class="{{ request()->routeIs('client.reports.by-device') ? 'bg-ocean-500/20 text-white border border-ocean-500/30' : 'text-gray-300 hover:bg-steel-700/50 hover:text-white' }} flex items-center px-4 py-3 rounded-lg transition-all duration-200 group">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('client.reports.by-device') ? 'text-ocean-400' : 'text-gray-400 group-hover:text-ocean-400' }} transition-colors duration-200" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="font-medium">Por Caja</span>
            </a>

            <!-- Reporte Mensual -->
            <a href="{{ route('client.reports.monthly') ?? '#' }}"
                class="{{ request()->routeIs('client.reports.monthly') ? 'bg-ocean-500/20 text-white border border-ocean-500/30' : 'text-gray-300 hover:bg-steel-700/50 hover:text-white' }} flex items-center px-4 py-3 rounded-lg transition-all duration-200 group">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('client.reports.monthly') ? 'text-ocean-400' : 'text-gray-400 group-hover:text-ocean-400' }} transition-colors duration-200" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11m-6 0h6"/>
                </svg>
                <span class="font-medium">Mensual</span>
            </a>
        </div>

        <!-- Configuración -->
        <div class="py-2">
            <p class="px-4 text-xs font-semibold text-steel-300 uppercase tracking-wider mb-2">⚙️ Configuración</p>
            
            <!-- Perfil del Comercio -->
            <a href="{{ route('client.profile.edit') ?? '#' }}"
                class="{{ request()->routeIs('client.profile.*') ? 'bg-steel-500/20 text-white border border-steel-500/30' : 'text-gray-300 hover:bg-steel-700/50 hover:text-white' }} flex items-center px-4 py-3 rounded-lg transition-all duration-200 group">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('client.profile.*') ? 'text-steel-400' : 'text-gray-400 group-hover:text-steel-400' }} transition-colors duration-200" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span class="font-medium">Mi Comercio</span>
            </a>

            <!-- Configuración de Cajas -->
            <a href="{{ route('client.settings.devices') ?? '#' }}"
                class="{{ request()->routeIs('client.settings.devices') ? 'bg-steel-500/20 text-white border border-steel-500/30' : 'text-gray-300 hover:bg-steel-700/50 hover:text-white' }} flex items-center px-4 py-3 rounded-lg transition-all duration-200 group">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('client.settings.devices') ? 'text-steel-400' : 'text-gray-400 group-hover:text-steel-400' }} transition-colors duration-200" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="font-medium">Config. Cajas</span>
            </a>

            <!-- Notificaciones -->
            <a href="{{ route('client.settings.notifications') ?? '#' }}"
                class="{{ request()->routeIs('client.settings.notifications') ? 'bg-steel-500/20 text-white border border-steel-500/30' : 'text-gray-300 hover:bg-steel-700/50 hover:text-white' }} flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 group">
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-3 {{ request()->routeIs('client.settings.notifications') ? 'text-steel-400' : 'text-gray-400 group-hover:text-steel-400' }} transition-colors duration-200" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 17h5l-5-5h5l-5-5H9l5 5-5 5zm0 0v5"/>
                    </svg>
                    <span class="font-medium">Notificaciones</span>
                </div>
                @php
                    $pendingNotifications = 2; 
                @endphp
                @if($pendingNotifications > 0)
                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $pendingNotifications }}</span>
                @endif
            </a>
        </div>
    </nav>

    <!-- Estado de conexión -->
    <div class="px-3 py-2">
        <div class="bg-steel-700/30 rounded-lg p-3">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-steel-200">Estado del Sistema</span>
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-ocean-400 rounded-full animate-pulse"></div>
                    <span class="text-xs text-ocean-400 ml-1">En línea</span>
                </div>
            </div>
            <div class="text-xs text-steel-300">
                Última sincronización: <span class="text-white">{{ now()->format('H:i') }}</span>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="p-4 border-t border-steel-700/50">
        <div class="flex items-center p-2 bg-steel-700/30 rounded-lg">
            <div class="flex-shrink-0 mr-2">
                <svg class="h-4 w-4 text-ocean-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-white">Sistema POS</p>
                <p class="text-xs text-gray-400">v2.1.0</p>
            </div>
        </div>
    </div>
</aside>

<!-- Sidebar móvil (overlay) -->
<div id="mobile-sidebar" class="md:hidden fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-gray-600 bg-opacity-75" onclick="toggleMobileSidebar()"></div>
    
    <!-- Sidebar content -->
    <div class="relative flex flex-col w-64 h-full bg-gradient-to-b from-steel-800 via-steel-800 to-steel-900 text-white shadow-xl">
        <!-- Header móvil -->
        <div class="flex items-center justify-between p-4 border-b border-steel-700/50">
            <h2 class="text-lg font-semibold text-white">{{ auth()->user()->business_name ?? auth()->user()->name ?? 'Mi Comercio' }}</h2>
            <button onclick="toggleMobileSidebar()" class="text-gray-300 hover:text-white">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <!-- Navegación móvil-->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <!-- Dashboard -->
            <a href="{{ route('client.dashboard') }}"
                class="{{ request()->routeIs('client.dashboard') ? 'bg-ocean-500/20 text-white border border-ocean-500/30' : 'text-gray-300 hover:bg-steel-700/50 hover:text-white' }} flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <svg class="h-5 w-5 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <!-- Cajas -->
            <div class="py-2">
                <p class="px-4 text-xs font-semibold text-steel-300 uppercase tracking-wider mb-2">🏪 Mi Comercio</p>
                
                <a href="{{ route('client.cashiers.index') }}"
                    class="{{ request()->routeIs('cashiers.*') ? 'bg-ocean-500/20 text-white border border-ocean-500/30' : 'text-gray-300 hover:bg-steel-700/50 hover:text-white' }} flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <span class="font-medium">Mis Cajas</span>
                    </div>
                    @php
                        $activeCashiers = auth()->user()->merchant ? auth()->user()->merchant->cashiers()->count() : 0;
                    @endphp
                    <span class="bg-ocean-500/20 text-ocean-400 text-xs px-2 py-1 rounded-full">{{ $activeCashiers }}</span>
                </a>

                <a href="{{ route('client.cashiers.create') }}"
                    class="{{ request()->routeIs('client.cashiers.create') ? 'bg-steel-500/20 text-white border border-steel-500/30' : 'text-gray-300 hover:bg-steel-700/50 hover:text-white' }} flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                    <svg class="h-5 w-5 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span class="font-medium">Nueva Caja</span>
                </a>
            </div>
        </nav>
    </div>
</div>

<script>
function toggleMobileSidebar() {
    const sidebar = document.getElementById('mobile-sidebar');
    sidebar.classList.toggle('hidden');
}

window.addEventListener('resize', function() {
    if (window.innerWidth >= 768) { // md breakpoint
        document.getElementById('mobile-sidebar').classList.add('hidden');
    }
});

</script>