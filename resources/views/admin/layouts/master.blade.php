<!-- master.blade.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Administrativo') - WyM Solutions</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/notifications.js'])
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-800">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        @include('admin.layouts.partials.header')

        <!-- Contenedor principal  -->
        <div class="flex flex-1 min-h-0">
            <!-- Sidebar -->
            @include('admin.layouts.partials.sidebar')
            <!-- Contenido principal -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-6">
                    <!-- Notificaciones o alertas -->
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <!-- Contenido principal -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>

        <!-- Footer -->
        <!-- @include('admin.layouts.partials.footer') -->
    </div>


    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleSidebarButton = document.getElementById('toggle-sidebar');
            const sidebar = document.getElementById('sidebar');

            if (toggleSidebarButton && sidebar) {
                toggleSidebarButton.addEventListener('click', function() {
                    sidebar.classList.toggle('hidden');
                    sidebar.classList.toggle('flex');
                });
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            const toggleSidebarButton = document.getElementById('toggle-sidebar');
            const sidebar = document.getElementById('sidebar');

            if (toggleSidebarButton && sidebar) {
                toggleSidebarButton.addEventListener('click', function() {
                    sidebar.classList.toggle('hidden');
                    sidebar.classList.toggle('flex');
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
