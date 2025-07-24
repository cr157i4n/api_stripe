@extends('admin.layouts.auth')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-navy-800 to-navy-900 py-12 px-4 sm:px-6 lg:px-8">
    <!-- Fondo con elementos decorativos -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/4 left-1/2 w-64 h-64 bg-navy-600/20 rounded-full blur-2xl"></div>
    </div>

    <!-- Logo y contenedor principal -->
    <div class="z-10 max-w-md w-full">
        <!-- Logo de WyM Solutions -->
        <div class="flex justify-center mb-8">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 inline-flex shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-teal-400 to-teal-600 shadow-md flex items-center justify-center overflow-hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 9l6 6 6-6"></path>
                            <path d="M6 3l6 6 6-6"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="font-bold text-xl text-white">WyM</span>
                        <span class="font-semibold text-xl text-teal-400">Solutions</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de inicio de sesión -->
        <div class="bg-white/10 backdrop-blur-lg p-8 rounded-2xl shadow-xl border border-white/20">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-white">
                    Panel Administrativo
                </h2>
                <p class="mt-2 text-teal-300">
                    Ingrese sus credenciales para acceder al sistema
                </p>
            </div>

            @if(session('error'))
                <div class="mb-6 bg-red-900/30 border-l-4 border-red-500 text-red-200 p-4 rounded-md" role="alert">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <form class="mt-8 space-y-6" method="POST" action="{{ route('admin.login') }}">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-teal-300 mb-1">
                        Correo electrónico
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            class="block w-full pl-10 pr-3 py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors @error('email') border-red-500 ring-red-500 @enderror"
                            placeholder="nombre@empresa.com"
                            value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-teal-300 mb-1">
                        Contraseña
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                            class="block w-full pl-10 pr-3 py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors @error('password') border-red-500 ring-red-500 @enderror"
                            placeholder="••••••••••">
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" 
                            class="h-4 w-4 bg-white/10 border-white/20 rounded text-teal-500 focus:ring-teal-500 focus:ring-offset-0">
                        <label for="remember" class="ml-2 block text-sm text-teal-300">
                            Recordarme
                        </label>
                    </div>
                    
                    <div class="text-sm">
                        <a href="#" class="text-teal-300 hover:text-teal-200 transition-colors">
                            ¿Olvidó su contraseña?
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-3 px-4 rounded-lg shadow-lg bg-gradient-to-r from-teal-500 to-teal-600 text-white font-medium hover:from-teal-600 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-navy-800 focus:ring-teal-500 transition-all duration-300">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                        </span>
                        Iniciar Sesión
                    </button>
                </div>
            </form>
            
            <!-- Footer del formulario -->
            <div class="mt-8 pt-6 border-t border-white/10 text-center">
                <p class="text-sm text-gray-400">
                    © {{ date('Y') }} WyM Solutions. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection