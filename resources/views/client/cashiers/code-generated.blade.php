@extends('client.layouts.master')

@section('title', 'Código Generado')

@section('page-title', 'Código de Vinculación Generado')
@section('page-subtitle', 'Tu código está listo para vincular el dispositivo')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Código generado exitosamente -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        
        <h3 class="text-2xl font-bold text-gray-900 mb-2">¡Código Generado Exitosamente!</h3>
        <p class="text-gray-600 mb-8">El código de vinculación para <strong>{{ $qrDevice->cashier->name }}</strong> está listo para usar.</p>
        
        <!-- Código principal -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-8 mb-8">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-700 mb-3">Código de Vinculación</p>
                <div class="text-6xl font-mono font-bold text-blue-600 mb-4 tracking-wider" id="linkingCode">
                    {{ $qrDevice->code }}
                </div>
                <div class="flex items-center justify-center space-x-4">
                    <button onclick="copyCode()" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 transition-colors"
                            id="copyBtn">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Copiar Código
                    </button>
                    
                    <button onclick="showQR()" 
                            class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                        Mostrar QR
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Información de la caja -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Información de la Caja</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                <div>
                    <p class="text-sm font-medium text-gray-700">Nombre de la Caja</p>
                    <p class="text-lg text-gray-900">{{ $qrDevice->cashier->name }}</p>
                </div>
                @if($qrDevice->cashier->location)
                <div>
                    <p class="text-sm font-medium text-gray-700">Ubicación</p>
                    <p class="text-lg text-gray-900">{{ $qrDevice->cashier->location }}</p>
                </div>
                @endif
                <div>
                    <p class="text-sm font-medium text-gray-700">Código Expira</p>
                    <p class="text-lg text-red-600 font-medium" id="expirationTime">
                        {{ $qrDevice->expires_at->format('d/m/Y H:i') }}
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Estado</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-1.5 animate-pulse"></span>
                        Activo
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Código QR (inicialmente oculto) -->
        <div id="qrContainer" class="hidden bg-white border-2 border-gray-200 rounded-lg p-6 mb-8">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Código QR</h4>
            <div class="flex justify-center">
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <img src="data:image/png;base64,{{ $qrDevice->image_qr }}" 
                         alt="Código QR" 
                         class="w-64 h-64 mx-auto">
                </div>
            </div>
            <p class="text-sm text-gray-600 mt-4">Escanea este código QR desde tu dispositivo para vincularlo automáticamente.</p>
            <button onclick="hideQR()" 
                    class="mt-4 inline-flex items-center px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200 transition-colors">
                Ocultar QR
            </button>
        </div>
        
        <!-- Instrucciones -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-left mb-8">
            <div class="flex">
                <svg class="w-6 h-6 text-blue-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-blue-800 mb-3">Instrucciones de Vinculación</h4>
                    <ol class="text-sm text-blue-700 space-y-2">
                        <li class="flex items-start">
                            <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">1</span>
                            Enciende tu dispositivo o aplicación de punto de venta
                        </li>
                        <li class="flex items-start">
                            <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">2</span>
                            Busca la opción "Vincular con comercio", "Conectar" o "Configuración inicial"
                        </li>
                        <li class="flex items-start">
                            <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">3</span>
                            Ingresa el código <strong>{{ $qrDevice->code }}</strong> o escanea el código QR
                        </li>
                        <li class="flex items-start">
                            <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">4</span>
                            Espera la confirmación de vinculación exitosa en tu dispositivo
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        
        <!-- Advertencias -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-left mb-8">
            <div class="flex">
                <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div class="ml-3">
                    <h4 class="text-sm font-semibold text-yellow-800">Información Importante</h4>
                    <ul class="mt-1 text-sm text-yellow-700 space-y-1">
                        <li>• Este código expira el {{ $qrDevice->expires_at->format('d/m/Y') }} a las {{ $qrDevice->expires_at->format('H:i') }}</li>
                        <li>• Una vez utilizado, el código se vuelve inválido automáticamente</li>
                        <li>• Solo un dispositivo puede usar este código</li>
                        <li>• No compartas este código con personas no autorizadas</li>
                        <li>• Si necesitas ayuda, contacta a soporte técnico</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Estado de vinculación -->
        <div id="linkingStatus" class="hidden bg-green-50 border border-green-200 rounded-lg p-4 text-left mb-8">
            <div class="flex">
                <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="ml-3">
                    <h4 class="text-sm font-semibold text-green-800">¡Dispositivo Vinculado!</h4>
                    <p class="mt-1 text-sm text-green-700">
                        El código ha sido utilizado exitosamente. Tu dispositivo ya está conectado y listo para procesar pagos.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Acciones -->
        <div class="flex flex-col sm:flex-row items-center justify-center space-y-3 sm:space-y-0 sm:space-x-4">
            <a href="{{ route('client.cashiers.show', $qrDevice->cashier) }}" 
               class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md text-base font-medium text-white hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Ver Detalles de la Caja
            </a>
            
            <a href="{{ route('client.cashiers.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-gray-100 border border-gray-300 rounded-md text-base font-medium text-gray-700 hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Ver Todas las Cajas
            </a>
            
            <a href="{{ route('client.dashboard') }}" 
               class="inline-flex items-center px-6 py-3 bg-gray-100 border border-gray-300 rounded-md text-base font-medium text-gray-700 hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Ir al Dashboard
            </a>
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
function copyCode() {
    const code = document.getElementById('linkingCode').textContent.trim();
    
    navigator.clipboard.writeText(code).then(function() {
        showToast();
        
        const copyBtn = document.getElementById('copyBtn');
        const originalText = copyBtn.innerHTML;
        copyBtn.innerHTML = `
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            ¡Copiado!
        `;
        copyBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        copyBtn.classList.add('bg-green-600', 'hover:bg-green-700');
        
        setTimeout(() => {
            copyBtn.innerHTML = originalText;
            copyBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
            copyBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
        }, 2000);
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

function showQR() {
    const qrContainer = document.getElementById('qrContainer');
    qrContainer.classList.remove('hidden');
    
    qrContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function hideQR() {
    document.getElementById('qrContainer').classList.add('hidden');
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

function startCountdown() {
    const expirationDate = new Date('{{ $qrDevice->expires_at->toISOString() }}');
    
    function updateCountdown() {
        const now = new Date();
        const timeLeft = expirationDate - now;
        
        if (timeLeft <= 0) {
            document.getElementById('expirationTime').textContent = 'EXPIRADO';
            document.getElementById('expirationTime').classList.add('bg-red-100', 'text-red-800', 'px-2', 'py-1', 'rounded');
            return;
        }
        
        const hours = Math.floor(timeLeft / (1000 * 60 * 60));
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
        
        const countdownElement = document.getElementById('expirationTime');
        countdownElement.textContent = `Expira en: ${hours}h ${minutes}m ${seconds}s`;
    }
    
    setInterval(updateCountdown, 1000);
    updateCountdown(); 
}

function checkCodeStatus() {
    fetch('/client/api/qr-devices/{{ $qrDevice->id }}/status', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'expired' && data.reason === 'used') {
            document.getElementById('linkingStatus').classList.remove('hidden');
            
            document.getElementById('linkingCode').parentElement.style.opacity = '0.5';
            
            const mainActions = document.querySelector('.flex.flex-col.sm\\:flex-row');
            mainActions.innerHTML = `
                <a href="{{ route('client.cashiers.show', $qrDevice->cashier) }}" 
                   class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md text-base font-medium text-white hover:bg-green-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    ¡Dispositivo Vinculado! Ver Detalles
                </a>
            `;
        }
    })
    .catch(error => {
        console.error('Error checking code status:', error);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    startCountdown();
    
    setInterval(checkCodeStatus, 10000);
});
</script>
@endsection