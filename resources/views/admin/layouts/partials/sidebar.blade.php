<aside id="sidebar"
    class="hidden md:flex flex-col w-64 bg-gradient-to-b from-navy-800 via-navy-800 to-navy-900 text-white shadow-xl overflow-y-auto">

    <!-- Perfil rápido -->
    <div class="p-4 flex items-center space-x-3 border-b border-navy-700/50">
        <div
            class="w-10 h-10 rounded-lg bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center text-white shadow-lg">
            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
        </div>
        <div class="overflow-hidden">
            <p class="font-medium text-sm text-white truncate">{{ auth()->user()->name ?? 'Usuario' }}</p>
            <p class="text-xs text-teal-300 truncate">{{ auth()->user()->email ?? 'usuario@example.com' }}</p>
        </div>
    </div>

    <!-- Navegación Principal -->
    <nav class="flex-1 px-2 py-4 space-y-2">

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
            class="{{ request()->routeIs('admin.dashboard') ? 'bg-teal-500/20 text-white border border-teal-500/30' : 'text-gray-300 hover:bg-navy-700/70 hover:text-white' }} flex items-center px-4 py-3 rounded-lg transition-all duration-200 group">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-teal-400' : 'text-gray-400 group-hover:text-teal-400' }} transition-colors duration-200"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- GESTIÓN DE COMERCIOS -->
        <div class="border-t border-navy-700/50 my-3"></div>
        <div x-data="{ open: true }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between px-4 py-3 text-gray-300 hover:bg-navy-700/70 hover:text-white rounded-lg transition-all duration-200 group">
                <div class="flex items-center">
                    <p class="text-xs font-semibold text-teal-400 uppercase tracking-wider">🏪 Gestión de Comercios</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" x-show="!open" class="h-4 w-4 text-gray-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" x-show="open" class="h-4 w-4 text-teal-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open" class="mt-1 ml-3 pl-6 pr-2 py-2 border-l-2 border-teal-500/30">
                <!-- Lista de Comercios -->
                <a href="{{ route('admin.branches.index') }}"
                    class="{{ request()->routeIs('admin.comercios.*') ? 'bg-teal-500/20 text-teal-300 border border-teal-500/30' : 'text-gray-400 hover:text-teal-300' }} flex items-center px-3 py-2 rounded-lg text-sm transition-colors duration-200">
                    <span
                        class="h-1.5 w-1.5 mr-2 rounded-full {{ request()->routeIs('admin.comercios.*') ? 'bg-teal-400' : 'bg-gray-600' }}"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Comercios
                </a>

                <!-- Gestión de Cajas -->
                <a href=""
                    class="{{ request()->routeIs('admin.cajas.*') ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : 'text-gray-400 hover:text-purple-300' }} flex items-center px-3 py-2 rounded-lg text-sm transition-colors duration-200">
                    <span
                        class="h-1.5 w-1.5 mr-2 rounded-full {{ request()->routeIs('admin.cajas.*') ? 'bg-purple-400' : 'bg-gray-600' }}"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-purple-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                    Cajas Registradoras
                </a>

            </div>
        </div>

        <!-- GESTIÓN DE TRANSACCIONES -->
        <div x-data="{ open: true }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between px-4 py-3 text-gray-300 hover:bg-navy-700/70 hover:text-white rounded-lg transition-all duration-200 group">
                <div class="flex items-center">
                    <p class="text-xs font-semibold text-teal-400 uppercase tracking-wider">💳 Transacciones</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" x-show="!open" class="h-4 w-4 text-gray-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" x-show="open" class="h-4 w-4 text-teal-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open" class="mt-1 ml-3 pl-6 pr-2 py-2 border-l-2 border-teal-500/30">
                <!-- Transacciones en Tiempo Real -->
                <a href=""
                    class="{{ request()->routeIs('admin.transacciones.tiempo-real') ? 'bg-green-500/20 text-green-300 border border-green-500/30' : 'text-gray-400 hover:text-green-300' }} flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors duration-200">
                    <div class="flex items-center">
                        <span
                            class="h-1.5 w-1.5 mr-2 rounded-full {{ request()->routeIs('admin.transacciones.tiempo-real') ? 'bg-green-400' : 'bg-gray-600' }}"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        En Tiempo Real
                    </div>
                    <span class="bg-green-500/20 text-green-400 text-xs px-2 py-0.5 rounded-full animate-pulse">
                        LIVE
                    </span>
                </a>

                <!-- Historial de Transacciones -->
                <a href=""
                    class="{{ request()->routeIs('admin.transacciones.historial') ? 'bg-teal-500/20 text-teal-300 border border-teal-500/30' : 'text-gray-400 hover:text-teal-300' }} flex items-center px-3 py-2 rounded-lg text-sm transition-colors duration-200">
                    <span
                        class="h-1.5 w-1.5 mr-2 rounded-full {{ request()->routeIs('admin.transacciones.historial') ? 'bg-teal-400' : 'bg-gray-600' }}"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    Historial Completo
                </a>

                <!-- Transacciones Pendientes -->
                <a href=""
                    class="{{ request()->routeIs('admin.transacciones.pendientes') ? 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30' : 'text-gray-400 hover:text-yellow-300' }} flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors duration-200">
                    <div class="flex items-center">
                        <span
                            class="h-1.5 w-1.5 mr-2 rounded-full {{ request()->routeIs('admin.transacciones.pendientes') ? 'bg-yellow-400' : 'bg-gray-600' }}"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pendientes
                    </div>
                    <span class="bg-yellow-500/20 text-yellow-400 text-xs px-2 py-0.5 rounded-full"
                        x-text="pendingCount || '0'">
                        5
                    </span>
                </a>
            </div>
        </div>

        <!-- GESTIÓN DE USUARIOS DEL SISTEMA -->
        <div class="border-t border-navy-700/50 my-3"></div>
        <p class="px-4 text-xs font-semibold text-teal-400 uppercase tracking-wider">👥 Usuarios del Sistema</p>

        <!-- Administradores -->
        <a href=""
            class="{{ request()->routeIs('admin.usuarios.administradores') ? 'bg-red-500/20 text-white border border-red-500/30' : 'text-gray-300 hover:bg-navy-700/70 hover:text-white' }} flex items-center px-4 py-3 rounded-lg transition-all duration-200 group">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 mr-3 {{ request()->routeIs('admin.usuarios.administradores') ? 'text-red-400' : 'text-gray-400 group-hover:text-red-400' }} transition-colors duration-200"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <span>Administradores</span>
        </a>

        <!-- Operadores -->
        <a href=""
            class="{{ request()->routeIs('admin.usuarios.operadores') ? 'bg-blue-500/20 text-white border border-blue-500/30' : 'text-gray-300 hover:bg-navy-700/70 hover:text-white' }} flex items-center px-4 py-3 rounded-lg transition-all duration-200 group">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 mr-3 {{ request()->routeIs('admin.usuarios.operadores') ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' }} transition-colors duration-200"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
            </svg>
            <span>Operadores</span>
        </a>

        <!-- Cajeros -->
        <a href=""
            class="{{ request()->routeIs('admin.usuarios.cajeros') ? 'bg-green-500/20 text-white border border-green-500/30' : 'text-gray-300 hover:bg-navy-700/70 hover:text-white' }} flex items-center px-4 py-3 rounded-lg transition-all duration-200 group">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 mr-3 {{ request()->routeIs('admin.usuarios.cajeros') ? 'text-green-400' : 'text-gray-400 group-hover:text-green-400' }} transition-colors duration-200"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span>Cajeros</span>
        </a>

        <!-- REPORTES Y ANÁLISIS -->
        <div class="border-t border-navy-700/50 my-3"></div>
        <div x-data="{ open: false }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between px-4 py-3 text-gray-300 hover:bg-navy-700/70 hover:text-white rounded-lg transition-all duration-200 group">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 mr-3 text-gray-400 group-hover:text-teal-400 transition-colors duration-200"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Reportes</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" x-show="!open" class="h-4 w-4 text-gray-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" x-show="open" class="h-4 w-4 text-teal-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" class="mt-1 ml-3 pl-6 pr-2 py-2 border-l-2 border-teal-500/30">
                <a href=""
                    class="{{ request()->routeIs('admin.reportes.ventas') ? 'bg-teal-500/20 text-teal-300 border border-teal-500/30' : 'text-gray-400 hover:text-teal-300' }} flex items-center px-3 py-2 rounded-lg text-sm transition-colors duration-200">
                    <span
                        class="h-1.5 w-1.5 mr-2 rounded-full {{ request()->routeIs('admin.reportes.ventas') ? 'bg-teal-400' : 'bg-gray-600' }}"></span>
                    Reporte de Ventas
                </a>
                <a href=""
                    class="{{ request()->routeIs('admin.reportes.comercios') ? 'bg-teal-500/20 text-teal-300 border border-teal-500/30' : 'text-gray-400 hover:text-teal-300' }} flex items-center px-3 py-2 rounded-lg text-sm transition-colors duration-200">
                    <span
                        class="h-1.5 w-1.5 mr-2 rounded-full {{ request()->routeIs('admin.reportes.comercios') ? 'bg-teal-400' : 'bg-gray-600' }}"></span>
                    Por Comercio
                </a>
                <a href=""
                    class="{{ request()->routeIs('admin.reportes.cajas') ? 'bg-teal-500/20 text-teal-300 border border-teal-500/30' : 'text-gray-400 hover:text-teal-300' }} flex items-center px-3 py-2 rounded-lg text-sm transition-colors duration-200">
                    <span
                        class="h-1.5 w-1.5 mr-2 rounded-full {{ request()->routeIs('admin.reportes.cajas') ? 'bg-teal-400' : 'bg-gray-600' }}"></span>
                    Por Caja
                </a>
            </div>
        </div>
    </nav>

    <!-- Footer -->
    <div class="p-4 border-t border-navy-700/50">
        <div class="flex items-center p-3 bg-navy-700/50 rounded-lg">
            <div class="flex-shrink-0 mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-white">Sistema de Comercios</p>
                <p class="text-xs text-gray-400 mt-0.5">v1.0.0</p>
            </div>
        </div>
    </div>
</aside>
