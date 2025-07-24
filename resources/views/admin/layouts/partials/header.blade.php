<header class="bg-gradient-to-r from-navy-800 to-navy-700 text-white shadow-lg">
    <div class="flex justify-between items-center px-4 py-2.5">

        <!-- Sección derecha -->
        <div class="flex items-center space-x-3">
            <!-- Toggle sidebar -->
            <button id="toggle-sidebar"
                class="md:hidden p-2 rounded-lg bg-navy-600 hover:bg-navy-500 transition-colors duration-300 shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>

            <!-- Notificaciones dinámicas -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                    class="p-2 rounded-lg bg-navy-600 hover:bg-navy-500 transition-colors duration-300 shadow relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>

                </button>

            </div>

            <!-- Perfil usuario -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                    class="flex items-center space-x-2 p-1.5 rounded-lg hover:bg-navy-600 transition-colors duration-300">
                    <div
                        class="w-8 h-8 rounded-lg bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center text-white shadow">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                    <span class="hidden lg:inline text-sm font-medium">{{ auth()->user()->name ?? 'Usuario' }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="hidden lg:block h-4 w-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown perfil -->
                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 w-52 mt-2 origin-top-right bg-white rounded-lg shadow-xl overflow-hidden"
                    style="display: none; z-index: 50;">
                    <div class="p-4 bg-gradient-to-r from-teal-50 to-white border-b border-gray-100">
                        <p class="text-sm font-semibold text-navy-700">{{ auth()->user()->name ?? 'Usuario' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ auth()->user()->email ?? 'usuario@example.com' }}</p>
                    </div>

                    <div class="py-2">
                        <a href="#"
                            class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-teal-50 transition-colors duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2.5 text-gray-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Mi Perfil
                        </a>
                        <a href="#"
                            class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-teal-50 transition-colors duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2.5 text-gray-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Configuración
                        </a>

                        <div class="border-t border-gray-100 my-1"></div>

                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
